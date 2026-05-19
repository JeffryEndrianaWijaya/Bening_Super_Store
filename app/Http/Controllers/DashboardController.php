<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch all unique years of orders and users to build dynamic dropdown list of years
        $orderYears = Pesanan::selectRaw('YEAR(created_at) as year')->pluck('year');
        $userYears = User::selectRaw('YEAR(created_at) as year')->pluck('year');
        $years = $orderYears->merge($userYears)
            ->filter()
            ->map(fn($y) => (int)$y)
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Ensure current year is always present
        $currentYear = (int)date('Y');
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
            sort($years);
        }

        // Capture selected year
        $selectedYear = $request->get('year', 'all');

        // Setup base queries
        $queryPendapatan = Pesanan::where('status', 'paid');
        $queryPesanan = Pesanan::query();
        $queryBelumDilayani = Pesanan::whereIn('status', ['paid', 'waiting_stock']);
        $queryUser = User::where('role', 'pelanggan');
        $queryProduk = Produk::query();

        // Apply year filter if selected
        if ($selectedYear !== 'all') {
            $queryPendapatan->whereYear('created_at', $selectedYear);
            $queryPesanan->whereYear('created_at', $selectedYear);
            $queryBelumDilayani->whereYear('created_at', $selectedYear);
            $queryUser->whereYear('created_at', $selectedYear);
            $queryProduk->whereYear('created_at', $selectedYear);
        }

        // Calculate filtered stats
        $totalPendapatan = $queryPendapatan->sum('total_harga');
        $totalPesanan = $queryPesanan->count();
        $pesananBelumDilayani = $queryBelumDilayani->count();
        $totalProduk = $queryProduk->count();
        $totalUser = $queryUser->count();

        // 2. Recent orders (always show latest across all time or filtered by year too, let's keep all latest for convenience but filtered is also nice. Let's filter recent orders too if year is selected!)
        $queryRecentOrders = Pesanan::with('user');
        if ($selectedYear !== 'all') {
            $queryRecentOrders->whereYear('created_at', $selectedYear);
        }
        $recentOrders = $queryRecentOrders->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Low stock products (keep all-time since stock is absolute state, but let's take it)
        $produks = Produk::with('stoks')->get();
        $lowStockProducts = $produks->filter(function ($produk) {
            return $produk->total_stok <= 5;
        })->sortBy('total_stok')->take(5);

        return view('pages.Dashboard.Dashboard', compact(
            'totalPendapatan',
            'totalPesanan',
            'pesananBelumDilayani',
            'totalProduk',
            'totalUser',
            'recentOrders',
            'lowStockProducts',
            'years',
            'selectedYear'
        ));
    }
}
