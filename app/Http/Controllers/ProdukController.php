<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukImage;
use App\Models\Kategori;
use App\Http\Requests\ProdukRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProdukController extends Controller
{
    public function index()
    {
        try {
            $produks = Produk::with('kategori', 'images')->latest()->get();
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
            if (!Kategori::where('id_kategori', $idOrName)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'id_kategori' => ['Kategori yang dipilih tidak valid.']
                ]);
            }
            return $idOrName;
        }

        $existing = Kategori::whereRaw('LOWER(nama_kategori) = ?', [strtolower($idOrName)])->first();

        if ($existing) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'id_kategori' => ['Kategori dengan nama tersebut sudah ada, silakan ketik dan pilih dari daftar.']
            ]);
        }

        $newKategori = Kategori::create([
            'nama_kategori' => $idOrName
        ]);

        return $newKategori->id_kategori;
    }

    private function handleImages($request, $produk)
    {
        // 1. Hapus gambar yang ditandai untuk dihapus (jika ada)
        if ($request->filled('delete_images')) {
            $deleteIds = explode(',', $request->delete_images);
            $imagesToDelete = ProdukImage::whereIn('id_image', $deleteIds)
                ->where('id_produk', $produk->id_produk)
                ->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // 2. Simpan gambar-gambar baru yang diunggah
        $newImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('produk_images', 'public');
                $newImages[] = ProdukImage::create([
                    'id_produk' => $produk->id_produk,
                    'image_path' => $path,
                    'sort_order' => 99, // Urutan sementara
                ]);
            }
        }

        // 3. Atur urutan (sort_order) berdasarkan input 'image_order' dari frontend
        if ($request->filled('image_order')) {
            $orderItems = explode(',', $request->image_order);
            foreach ($orderItems as $index => $item) {
                if (str_starts_with($item, 'existing-')) {
                    $imgId = (int) str_replace('existing-', '', $item);
                    ProdukImage::where('id_image', $imgId)
                        ->where('id_produk', $produk->id_produk)
                        ->update(['sort_order' => $index]);
                } elseif (str_starts_with($item, 'new-')) {
                    $newIdx = (int) str_replace('new-', '', $item);
                    if (isset($newImages[$newIdx])) {
                        $newImages[$newIdx]->update(['sort_order' => $index]);
                    }
                }
            }
        } else {
            // Fallback: Urutkan semua gambar yang tersisa secara berurutan
            $remainingImages = ProdukImage::where('id_produk', $produk->id_produk)
                ->orderBy('sort_order')
                ->orderBy('id_image')
                ->get();
            foreach ($remainingImages as $index => $img) {
                $img->update(['sort_order' => $index]);
            }
        }
    }

    public function store(ProdukRequest $request)
    {
        DB::beginTransaction();
        try {
            $id_kategori = $this->resolveKategori($request->id_kategori);

            $produk = Produk::create([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
                'id_kategori' => $id_kategori,
                'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : true,
            ]);

            $this->handleImages($request, $produk);

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
                'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : true,
            ]);

            $this->handleImages($request, $produk);

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

            // Delete image files
            foreach ($produk->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }

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
