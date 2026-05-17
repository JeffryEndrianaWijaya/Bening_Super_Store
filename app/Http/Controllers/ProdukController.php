<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Http\Requests\ProdukRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProdukController extends Controller
{
    public function index()
    {
        try {
            $produks = Produk::with('kategori')->latest()->get();
            $kategoris = Kategori::all();
            
            return view('pages.ProdukDashboard.ProdukDashboard', compact('produks', 'kategoris'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman produk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data produk.');
        }
    }

    public function create() {}

    private function resolveKategori($idOrName)
    {
        if (is_numeric($idOrName)) {
            // Periksa apakah ID benar-benar ada di database
            if (!Kategori::where('id_kategori', $idOrName)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'id_kategori' => ['Kategori yang dipilih tidak valid.']
                ]);
            }
            return $idOrName;
        }

        // Jika teks, periksa apakah sudah ada (case-insensitive)
        $existing = Kategori::whereRaw('LOWER(nama_kategori) = ?', [strtolower($idOrName)])->first();

        if ($existing) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'id_kategori' => ['Kategori dengan nama tersebut sudah ada, silakan ketik dan pilih dari daftar.']
            ]);
        }

        // Buat kategori baru
        $newKategori = Kategori::create([
            'nama_kategori' => $idOrName
        ]);

        return $newKategori->id_kategori;
    }

    public function store(ProdukRequest $request)
    {
        DB::beginTransaction();
        try {
            $id_kategori = $this->resolveKategori($request->id_kategori);

            Produk::create([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
                'id_kategori' => $id_kategori,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Produk baru berhasil ditambahkan.']);
            }

            return redirect()->route('produk.index')->with('success', 'Produk baru berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan produk: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menambah produk. Silakan coba lagi.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambah produk. Silakan coba lagi.');
        }
    }

    public function show(string $id) {}
    public function edit(string $id) {}

    public function update(ProdukRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $id_kategori = $this->resolveKategori($request->id_kategori);

            $produk = Produk::findOrFail($id);
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
                'id_kategori' => $id_kategori,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui.']);
            }

            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui produk ID ' . $id . ': ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui produk. Silakan coba lagi.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);
            $produk->delete();

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus produk ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk.');
        }
    }
}
