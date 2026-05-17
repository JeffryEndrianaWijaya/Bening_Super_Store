<?php

namespace App\Http\Controllers;

use App\Models\CuciGudang;
use App\Models\Produk;
use App\Http\Requests\CuciGudangRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class CuciGudangController extends Controller
{
    public function index()
    {
        try {
            $cuci_gudangs = CuciGudang::with('produk')->latest()->get();
            $produks = Produk::all();
            
            return view('pages.CuciGudangDashboard.CuciGudangDashboard', compact('cuci_gudangs', 'produks'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman cuci gudang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data cuci gudang.');
        }
    }

    public function store(CuciGudangRequest $request)
    {
        DB::beginTransaction();
        try {
            CuciGudang::create([
                'id_produk'     => $request->id_produk,
                'persen_diskon' => $request->persen_diskon,
                'waktu_mulai'   => $request->waktu_mulai . ' 00:00:00',
                'waktu_selesai' => $request->waktu_selesai . ' 23:59:59',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Program Cuci Gudang berhasil ditambahkan.']);
            }
            return redirect()->route('cuci_gudang.index')->with('success', 'Program Cuci Gudang berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan cuci gudang: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambah program Cuci Gudang.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambah program. Silakan coba lagi.');
        }
    }

    public function update(CuciGudangRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $cuciGudang = CuciGudang::findOrFail($id);
            $cuciGudang->update([
                'id_produk'     => $request->id_produk,
                'persen_diskon' => $request->persen_diskon,
                'waktu_mulai'   => $request->waktu_mulai . ' 00:00:00',
                'waktu_selesai' => $request->waktu_selesai . ' 23:59:59',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Program Cuci Gudang berhasil diperbarui.']);
            }
            return redirect()->route('cuci_gudang.index')->with('success', 'Program Cuci Gudang berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui cuci gudang ID ' . $id . ': ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui data.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $cuciGudang = CuciGudang::findOrFail($id);
            $cuciGudang->delete();

            DB::commit();
            return redirect()->route('cuci_gudang.index')->with('success', 'Program Cuci Gudang berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus cuci gudang ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('cuci_gudang.index')->with('error', 'Gagal menghapus data.');
        }
    }
}
