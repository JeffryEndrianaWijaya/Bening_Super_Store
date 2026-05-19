<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Please login.'], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $role = $user->role;

        // Allow backend access to admin, kasir, and gudang
        if (!in_array($role, ['admin', 'kasir', 'gudang'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Access denied.'], 403);
            }
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Check routes permissions
        $routeName = $request->route()->getName();

        if ($role === 'kasir') {
            // Kasir can only access dashboard, profile_admin, and pesanan routes
            $allowedRoutes = [
                'dashboard',
                'profile_admin',
                'profile_admin.update',
            ];
            
            $isAllowed = in_array($routeName, $allowedRoutes) || str_starts_with($routeName, 'pesanan_admin.');
            
            if (!$isAllowed) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Akses dibatasi! Kasir hanya dapat mengelola Penjualan.'], 403);
                }
                return redirect()->route('dashboard')->with('error', 'Akses dibatasi! Kasir hanya dapat mengelola Penjualan.');
            }
        }

        if ($role === 'gudang') {
            // Gudang can only access dashboard, profile_admin, kategori, produk, and stok
            $allowedRoutes = [
                'dashboard',
                'profile_admin',
                'profile_admin.update',
            ];
            
            $allowedPrefixes = ['kategori.', 'produk.', 'stok.'];
            $isAllowed = in_array($routeName, $allowedRoutes);
            
            if (!$isAllowed) {
                foreach ($allowedPrefixes as $prefix) {
                    if (str_starts_with($routeName, $prefix)) {
                        $isAllowed = true;
                        break;
                    }
                }
            }

            if (!$isAllowed) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Akses dibatasi! Gudang hanya dapat mengelola Kategori, Produk, dan Stok.'], 403);
                }
                return redirect()->route('dashboard')->with('error', 'Akses dibatasi! Gudang hanya dapat mengelola Kategori, Produk, dan Stok.');
            }
        }

        return $next($request);
    }
}
