<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\CuciGudang;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function home()
    {
        $kategoris = Kategori::where('status', true)->get();
        $produks = Produk::with('kategori', 'images')->where('status', true)->latest()->take(8)->get();

        // Produk yang sedang cuci gudang (aktif saat ini)
        $now = Carbon::now();
        $cuciGudangAktif = CuciGudang::with('produk')
            ->whereHas('produk', function ($query) {
                $query->where('status', true);
            })
            ->where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->get();

        return view('pages.frontend.Home', compact('kategoris', 'produks', 'cuciGudangAktif'));
    }

    public function category($id = null)
    {
        $kategoris = Kategori::where('status', true)->get();
        $now = Carbon::now();

        if ($id) {
            $kategoriAktif = Kategori::findOrFail($id);
            $produks = Produk::with('kategori', 'images')->where('id_kategori', $id)->where('status', true)->latest()->get();
        } else {
            $kategoriAktif = null;
            $produks = Produk::with('kategori', 'images')->where('status', true)->latest()->get();
        }

        // Get active discounts indexed by product id
        $diskonAktif = CuciGudang::where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->get()
            ->keyBy('id_produk');

        return view('pages.frontend.Category', compact('kategoris', 'produks', 'kategoriAktif', 'diskonAktif'));
    }
}
