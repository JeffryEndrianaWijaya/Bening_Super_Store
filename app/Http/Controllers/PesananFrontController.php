<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class PesananFrontController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with('details')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pages.frontend.Orders', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('details.produk')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('pages.frontend.OrderDetail', compact('pesanan'));
    }

    public function konfirmasi($id)
    {
        $pesanan = Pesanan::where('user_id', auth()->id())->findOrFail($id);

        if ($pesanan->status === 'shipped') {
            $pesanan->update(['status' => 'completed']);
            return redirect()->back()->with('success', 'Terima kasih! Pesanan Anda telah ditandai sebagai selesai.');
        }

        return redirect()->back()->with('error', 'Status pesanan tidak valid.');
    }

    public function simulasiBayar($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk melunaskan pesanan ini.');
        }

        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status === 'pending') {
            try {
                \Illuminate\Support\Facades\DB::transaction(function () use ($pesanan) {
                    $pesanan->decreaseStock();
                    $pesanan->update(['status' => 'paid']);
                });
                return redirect()->back()->with('success', 'Berhasil! Pembayaran telah ditandai LUNAS secara manual oleh Admin.');
            } catch (\Exception $e) {
                $pesanan->update(['status' => 'waiting_stock']);
                return redirect()->back()->with('error', 'Gagal memproses pembayaran (Stok Habis / Tidak Mencukupi). Status pesanan diubah menjadi Menunggu Stok. Detail error: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Pesanan ini sudah dibayar atau tidak valid.');
    }
}
