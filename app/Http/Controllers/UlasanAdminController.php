<?php
 
namespace App\Http\Controllers;
 
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
 
class UlasanAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ulasans = Ulasan::with(['user', 'produk'])->latest()->get();
            return view('pages.UlasanDashboard.UlasanDashboard', compact('ulasans'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman manajemen ulasan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data ulasan.');
        }
    }
 
    /**
     * Update the specified resource in storage (Specifically for replying).
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'balasan' => 'required|string|max:2000',
            ]);
 
            $ulasan = Ulasan::findOrFail($id);
            $ulasan->update([
                'balasan' => $request->balasan,
            ]);
 
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Ulasan berhasil dibalas.']);
            }
 
            return redirect()->route('ulasan_admin.index')->with('success', 'Ulasan berhasil dibalas.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Gagal membalas ulasan. Periksa kembali isian form Anda.');
        } catch (Exception $e) {
            Log::error('Gagal membalas ulasan: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal membalas ulasan.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal membalas ulasan. Silakan coba lagi.');
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ulasan = Ulasan::findOrFail($id);
            $ulasan->delete();
 
            return redirect()->route('ulasan_admin.index')->with('success', 'Ulasan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus ulasan: ' . $e->getMessage());
            return redirect()->route('ulasan_admin.index')->with('error', 'Gagal menghapus ulasan.');
        }
    }
}
