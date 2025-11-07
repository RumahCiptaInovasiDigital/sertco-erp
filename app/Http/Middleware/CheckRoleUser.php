<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        // Dapatkan pengguna yang sedang login
        $user = $request->user();

        // Cek apakah Administrator
        if ($user->jabatan === 'Administrator') {
            return $next($request);
        }

        // Cek apakah pengguna sudah login dan memiliki jabatan
        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        // Cek APakah user memiliki Role
        if (!$user->hasRole) {
            // Forbidden fallback
            return $request->ajax() ? response()->json(['success' => false, 'message' => 'Anda Belum Memiliki Role dan Tidak Dapat Mengakses Halaman ini']) : response()->view('layouts.forbidden');
        }

        // Dapatkan nama rute saat ini
        $currentRoute = $request->route()->getName();
        // dd($currentRoute);

        // Cek apakah permission untuk jobLvl dan URL saat ini ada di database
        $hasPermission = Permission::where('role_id', $user->hasRole->role->id_role)
            ->where('url', $currentRoute)
            ->exists();

        // Jika tidak ada izin, tampilkan halaman forbidden
        if ($hasPermission) {
            return $next($request);
        }

        // Forbidden fallback
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'Anda Tidak Memiliki Akses Pada Action ini'])
            : response()->view('layouts.forbidden');
    }
}
