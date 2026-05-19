<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\CuciGudang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('produk.kategori')->where('user_id', auth()->id())->get();
        $now = Carbon::now();

        $diskonAktif = CuciGudang::where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->get()
            ->keyBy('id_produk');

        return view('pages.frontend.Cart', compact('items', 'diskonAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'qty' => 'nullable|integer|min:1',
        ]);

        $produk = \App\Models\Produk::findOrFail($request->id_produk);
        $requestedQty = ($request->qty ?? 1);

        $existing = Keranjang::where('user_id', auth()->id())
            ->where('id_produk', $request->id_produk)
            ->first();

        $totalRequested = $existing ? ($existing->qty + $requestedQty) : $requestedQty;

        if ($produk->total_stok < $totalRequested) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Tersisa: {$produk->total_stok}."
                ], 422);
            }
            return redirect()->back()->with('error', "Stok tidak mencukupi. Tersisa: {$produk->total_stok}.");
        }

        if ($existing) {
            $existing->update(['qty' => $existing->qty + $requestedQty]);
        } else {
            Keranjang::create([
                'user_id' => auth()->id(),
                'id_produk' => $request->id_produk,
                'qty' => $requestedQty,
            ]);
        }

        if ($request->ajax()) {
            $cartCount = Keranjang::where('user_id', auth()->id())->sum('qty');
            return response()->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang!', 'cart_count' => $cartCount]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        $item = Keranjang::where('id_keranjang', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($item->produk->total_stok < $request->qty) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok tidak mencukupi. Tersisa: {$item->produk->total_stok}."
                ], 422);
            }
            return redirect()->route('keranjang.index')->with('error', "Stok tidak mencukupi untuk '{$item->produk->nama_produk}'. Tersisa: {$item->produk->total_stok}.");
        }

        $item->update(['qty' => $request->qty]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('keranjang.index');
    }

    public function destroy($id)
    {
        $item = Keranjang::where('id_keranjang', $id)->where('user_id', auth()->id())->firstOrFail();
        $item->delete();

        return redirect()->route('keranjang.index')->with('success', 'Item dihapus dari keranjang.');
    }
}
