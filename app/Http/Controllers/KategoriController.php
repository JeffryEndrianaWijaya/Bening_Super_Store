<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Http\Requests\KategoriRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     */
    public function index(Request $request)
    {
        try {
         
            $kategori = Kategori::latest()->get();
            
            return view('pages.CategoryDashboard.CategoryDashboard', compact('kategori'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman kategori: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Biasanya dilewati jika menggunakan Modal Bootstrap di halaman Index
        //return view('pages.CategoryDashboard.create'); 
    }

    /**
     * Menyimpan kategori baru ke database dengan proteksi transaksi.
     */
    public function store(KategoriRequest $request)
    {
        DB::beginTransaction();
        try {
            Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Kategori baru berhasil ditambahkan.']);
            }

            return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan kategori: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambah kategori. Silakan coba lagi.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambah kategori. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //return view('pages.CategoryDashboard.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        // Biasanya dilewati jika form edit berupa Modal Bootstrap di halaman Index
        //return view('pages.CategoryDashboard.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori berdasarkan Route Model Binding.
     */
    public function update(KategoriRequest $request, Kategori $kategori)
    {
        DB::beginTransaction();
        try {
            $kategori->update([
                'nama_kategori' => $request->nama_kategori
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
            }

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui kategori ID ' . $kategori->id_kategori . ': ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui kategori. Silakan coba lagi.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus kategori menggunakan fitur Soft Delete.
     */
    public function destroy(Kategori $kategori)
    {
        DB::beginTransaction();
        try {
            // Karena model 'Kategori' menggunakan trait SoftDeletes, 
            // ini otomatis hanya mengisi kolom deleted_at (data tidak hilang permanen).
            $kategori->delete();

            DB::commit();
            return redirect()->route('route-nama-kamu-di-sini-atau-kategori.index')->with('success', 'Kategori berhasil dihapus.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus kategori ID ' . $kategori->id_kategori . ': ' . $e->getMessage());

            return redirect()->route('kategori.index')->with('error', 'Gagal menghapus kategori. Data ini mungkin masih digunakan oleh relasi data lainnya.');
        }
    }
}