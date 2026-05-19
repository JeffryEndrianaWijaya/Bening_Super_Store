<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Log;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::latest()->get();
            return view('pages.UserDashboard.UserDashboard', compact('users'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman manajemen user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data pengguna.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used, using modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,pelanggan,kasir,gudang',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : true,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna baru berhasil ditambahkan.'
                ]);
            }

            return redirect()->route('user_admin.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Gagal menambah pengguna. Periksa kembali isian form Anda.'
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Gagal menambah pengguna. Periksa kembali isian form Anda.');
        } catch (Exception $e) {
            Log::error('Gagal menambah pengguna: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambah pengguna. Silakan coba lagi.'
                ], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menambah pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not used, using modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $rules = [
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:admin,pelanggan,kasir,gudang',
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:8|confirmed';
            }

            $request->validate($rules);

            $dataToUpdate = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : true,
            ];

            if ($request->filled('password')) {
                $dataToUpdate['password'] = Hash::make($request->password);
            }

            $user->update($dataToUpdate);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengguna berhasil diperbarui.'
                ]);
            }

            return redirect()->route('user_admin.index')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Gagal memperbarui pengguna. Periksa kembali isian form Anda.'
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Gagal memperbarui pengguna. Periksa kembali isian form Anda.');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui pengguna: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui pengguna. Silakan coba lagi.'
                ], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Mencegah admin menghapus dirinya sendiri
            if (auth()->id() == $user->id) {
                return redirect()->route('user_admin.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->delete();

            return redirect()->route('user_admin.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus pengguna: ' . $e->getMessage());
            return redirect()->route('user_admin.index')->with('error', 'Gagal menghapus pengguna.');
        }
    }
}
