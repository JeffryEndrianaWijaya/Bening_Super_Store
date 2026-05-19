<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Produk;
use App\Http\Requests\StokRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class StokController extends Controller
{
    public function index()
    {
        try {
            // Only show positive (masuk) entries to admin — system deduction entries are hidden
            $stoks  = Stok::with('produk')->where('jumlah_stok', '>', 0)->latest()->get();
            $produks = Produk::with('stoks')->where('status', true)->get();

            return view('pages.StokDashboard.StokDashboard', compact('stoks', 'produks'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman stok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data stok.');
        }
    }

    /**
     * Only allow ADDING new positive stock entries (stok masuk).
     */
    public function store(StokRequest $request)
    {
        DB::beginTransaction();
        try {
            Stok::create([
                'id_produk'   => $request->id_produk,
                'jumlah_stok' => abs($request->jumlah_stok), // Always positive for admin input
                'status'      => 'pending',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Stok berhasil ditambahkan dan menunggu persetujuan.']);
            }
            return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan dan menunggu persetujuan.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan stok: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambah stok. Silakan coba lagi.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambah stok. Silakan coba lagi.');
        }
    }

    /**
     * Editing individual stock ledger entries is DISABLED.
     * Stock can only be added via store(), and deducted automatically by the payment system.
     */
    public function update(StokRequest $request, string $id)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Mengubah riwayat stok tidak diizinkan. Tambahkan catatan stok baru.'], 403);
        }
        return redirect()->route('stok.index')->with('error', 'Mengubah riwayat stok tidak diizinkan.');
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::findOrFail($id);
            $stok->update(['status' => 'approved']);
            DB::commit();

            return redirect()->route('stok.index')->with('success', 'Stok berhasil disetujui.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyetujui stok ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('stok.index')->with('error', 'Gagal menyetujui stok.');
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::findOrFail($id);
            $stok->update(['status' => 'cancelled']);
            DB::commit();

            return redirect()->route('stok.index')->with('success', 'Stok berhasil dibatalkan.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal membatalkan stok ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('stok.index')->with('error', 'Gagal membatalkan stok.');
        }
    }

    public function destroy(string $id)
    {
        return redirect()->route('stok.index')->with('error', 'Menghapus riwayat stok tidak diizinkan. Catatan bersifat permanen.');
    }
}
