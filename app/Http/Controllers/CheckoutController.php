<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\CuciGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function process(Request $request)
    {
        $cartItems = Keranjang::with('produk')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validate stock before checkout
        foreach ($cartItems as $cartItem) {
            $currentStock = $cartItem->produk->total_stok;
            if ($currentStock < $cartItem->qty) {
                return redirect()->route('keranjang.index')->with('error', "Stok untuk produk '{$cartItem->produk->nama_produk}' tidak mencukupi (Tersisa: {$currentStock}, Keranjang: {$cartItem->qty}).");
            }
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $orderId = 'ORD-' . time() . '-' . auth()->id();
            $totalHarga = 0;

            $pesanan = Pesanan::create([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'total_harga' => 0,
                'status' => 'pending',
            ]);

            $midtransItems = [];

            foreach ($cartItems as $cartItem) {
                $hargaAsli = $cartItem->produk->harga;
                $diskonPersen = 0;

                // Cek diskon cuci gudang aktif
                $cuciGudang = CuciGudang::where('id_produk', $cartItem->id_produk)
                    ->where('waktu_mulai', '<=', $now)
                    ->where('waktu_selesai', '>=', $now)
                    ->first();

                if ($cuciGudang) {
                    $diskonPersen = $cuciGudang->persen_diskon;
                }

                $hargaSetelahDiskon = $hargaAsli * (1 - ($diskonPersen / 100));
                $subtotal = $hargaSetelahDiskon * $cartItem->qty;
                $totalHarga += $subtotal;

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => $cartItem->id_produk,
                    'nama_produk' => $cartItem->produk->nama_produk,
                    'qty' => $cartItem->qty,
                    'harga_satuan' => $hargaAsli,
                    'diskon_persen' => $diskonPersen,
                    'subtotal' => $subtotal,
                ]);

                $midtransItems[] = [
                    'id' => (string)$cartItem->id_produk,
                    'price' => (int)round($hargaSetelahDiskon),
                    'quantity' => $cartItem->qty,
                    'name' => substr($cartItem->produk->nama_produk, 0, 50),
                ];
            }

            $pesanan->update(['total_harga' => $totalHarga]);

            // Buat Midtrans Snap Token
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)round($totalHarga),
                ],
                'item_details' => $midtransItems,
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'enabled_payments' => [
                    'credit_card', 'gopay', 'shopeepay', 'qris',
                    'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va',
                    'indomaret', 'alfamart'
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $pesanan->update(['snap_token' => $snapToken]);

            // Kosongkan keranjang
            Keranjang::where('user_id', auth()->id())->delete();

            DB::commit();

            return view('pages.frontend.Checkout', compact('pesanan', 'snapToken'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->route('keranjang.index')->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $pesanan = Pesanan::where('order_id', $request->order_id)->first();

            if ($pesanan) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    if ($pesanan->status !== 'paid') {
                        try {
                            DB::transaction(function () use ($pesanan) {
                                $pesanan->decreaseStock();
                                $pesanan->update(['status' => 'paid']);
                            });
                        } catch (\Exception $e) {
                            Log::error('Callback Stock Deduction Error: ' . $e->getMessage());
                            $pesanan->update(['status' => 'waiting_stock']);
                        }
                    }
                } elseif ($request->transaction_status == 'expire') {
                    $pesanan->update(['status' => 'expired']);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $pesanan->update(['status' => 'cancelled']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function updateStatusAfterPay(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string',
                'status' => 'required|in:paid,pending,expired,cancelled',
            ]);

            $pesanan = Pesanan::where('order_id', $request->order_id)->first();
            if ($pesanan) {
                if ($request->status === 'paid' && $pesanan->status !== 'paid') {
                    try {
                        DB::transaction(function () use ($pesanan) {
                            $pesanan->decreaseStock();
                            $pesanan->update(['status' => 'paid']);
                        });
                    } catch (\Exception $e) {
                        $pesanan->update(['status' => 'waiting_stock']);
                        return response()->json(['success' => false, 'message' => 'Stok habis, pesanan dialihkan ke status Menunggu Stok: ' . $e->getMessage()], 422);
                    }
                } else {
                    $pesanan->update(['status' => $request->status]);
                }
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui status pasca bayar: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
