<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class PesananAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pesanans = Pesanan::with(['user', 'details.produk'])->latest()->get();
            return view('pages.PesananDashboard.PesananDashboard', compact('pesanans'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman pesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data pesanan.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed, orders are created by customers
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $pesanan = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
            return view('pages.PesananDashboard.DetailPesanan', compact('pesanan'));
        } catch (Exception $e) {
            Log::error('Gagal memuat detail pesanan: ' . $e->getMessage());
            return redirect()->route('pesanan_admin.index')->with('error', 'Pesanan tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,paid,expired,cancelled,shipped,completed,waiting_stock',
            ]);

            $pesanan = Pesanan::findOrFail($id);

            if ($request->status === 'paid' && $pesanan->status !== 'paid') {
                try {
                    \Illuminate\Support\Facades\DB::transaction(function () use ($pesanan) {
                        $pesanan->decreaseStock();
                        $pesanan->update(['status' => 'paid']);
                    });
                } catch (Exception $e) {
                    if ($pesanan->status !== 'waiting_stock') {
                        $pesanan->update(['status' => 'waiting_stock']);
                    }
                    throw $e;
                }
            } else {
                $pesanan->update([
                    'status' => $request->status
                ]);
            }

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui status pesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->delete();

            return redirect()->route('pesanan_admin.index')->with('success', 'Pesanan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus pesanan: ' . $e->getMessage());
            return redirect()->route('pesanan_admin.index')->with('error', 'Gagal menghapus pesanan.');
        }
    }
}
