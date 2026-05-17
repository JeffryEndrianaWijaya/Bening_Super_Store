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
            $stoks = Stok::with('produk')->latest()->get();
            $produks = Produk::all();
            
            return view('pages.StokDashboard.StokDashboard', compact('stoks', 'produks'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman stok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data stok.');
        }
    }

    public function store(StokRequest $request)
    {
        DB::beginTransaction();
        try {
            Stok::create([
                'id_produk'   => $request->id_produk,
                'jumlah_stok' => $request->jumlah_stok,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Stok baru berhasil ditambahkan.']);
            }
            return redirect()->route('stok.index')->with('success', 'Stok baru berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan stok: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambah stok. Silakan coba lagi.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambah stok. Silakan coba lagi.');
        }
    }

    public function update(StokRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::findOrFail($id);
            $stok->update([
                'id_produk'   => $request->id_produk,
                'jumlah_stok' => $request->jumlah_stok,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data stok berhasil diperbarui.']);
            }
            return redirect()->route('stok.index')->with('success', 'Data stok berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui stok ID ' . $id . ': ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui data stok. Silakan coba lagi.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data stok. Silakan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $stok = Stok::findOrFail($id);
            $stok->delete();

            DB::commit();
            return redirect()->route('stok.index')->with('success', 'Riwayat stok berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus stok ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('stok.index')->with('error', 'Gagal menghapus riwayat stok.');
        }
    }
}
