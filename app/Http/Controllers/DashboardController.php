<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate stats
        $totalPendapatan = Pesanan::where('status', 'paid')->sum('total_harga');
        $totalPesanan = Pesanan::count();
        $totalProduk = Produk::count();
        $totalUser = User::where('role', 'customer')->count();

        // 2. Recent orders
        $recentOrders = Pesanan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Low stock products
        $produks = Produk::with('stoks')->get();
        $lowStockProducts = $produks->filter(function($produk) {
            return $produk->total_stok <= 5;
        })->sortBy('total_stok')->take(5);

        return view('pages.Dashboard.Dashboard', compact(
            'totalPendapatan',
            'totalPesanan',
            'totalProduk',
            'totalUser',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
