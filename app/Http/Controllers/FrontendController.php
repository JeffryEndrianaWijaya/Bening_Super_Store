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
        $produks = Produk::with('kategori', 'images')
            ->where('status', true)
            ->whereHas('kategori', function ($query) {
                $query->where('status', true);
            })
            ->latest()
            ->take(8)
            ->get();

        // Produk yang sedang cuci gudang (aktif saat ini)
        $now = Carbon::now();
        $cuciGudangAktif = CuciGudang::with('produk')
            ->whereHas('produk', function ($query) {
                $query->where('status', true)
                      ->whereHas('kategori', function ($q) {
                          $q->where('status', true);
                      });
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
            // If the requested category is inactive, redirect or return empty (or let it be if we still want direct links to work, but it's better to secure it)
            if (!$kategoriAktif->status) {
                return redirect()->route('shop.category.index')->with('error', 'Kategori tidak tersedia.');
            }
            $produks = Produk::with('kategori', 'images')->where('id_kategori', $id)->where('status', true)->latest()->get();
        } else {
            $kategoriAktif = null;
            $produks = Produk::with('kategori', 'images')
                ->where('status', true)
                ->whereHas('kategori', function ($query) {
                    $query->where('status', true);
                })
                ->latest()
                ->get();
        }

        // Get active discounts indexed by product id
        $diskonAktif = CuciGudang::where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->get()
            ->keyBy('id_produk');

        return view('pages.frontend.Category', compact('kategoris', 'produks', 'kategoriAktif', 'diskonAktif'));
    }

    public function productDetail($id)
    {
        $produk = Produk::with(['kategori', 'images', 'ulasans.user'])
            ->where('status', true)
            ->whereHas('kategori', function($q) {
                $q->where('status', true);
            })
            ->findOrFail($id);
        
        $now = Carbon::now();
        $diskon = CuciGudang::where('id_produk', $id)
            ->where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->first();

        $jumlahTerjual = \App\Models\PesananDetail::where('id_produk', $id)
            ->whereHas('pesanan', function($q) {
                $q->whereIn('status', ['paid', 'shipped', 'completed']);
            })->sum('qty');

        return view('pages.frontend.ProductDetail', compact('produk', 'diskon', 'jumlahTerjual'));
    }

    public function storeReview(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        // Verify if user actually purchased this product in a completed order
        $hasPurchased = \App\Models\Pesanan::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('details', function($q) use ($request) {
                $q->where('id_produk', $request->id_produk);
            })->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang sudah Anda beli dan terima.');
        }

        // Check if already reviewed
        $existing = \App\Models\Ulasan::where('id_user', auth()->id())
            ->where('id_produk', $request->id_produk)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        \App\Models\Ulasan::create([
            'id_user' => auth()->id(),
            'id_produk' => $request->id_produk,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim! Terima kasih.');
    }
}
