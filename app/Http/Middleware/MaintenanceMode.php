<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
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

        // Cek apakah pengguna sudah login dan memiliki jobLvl
        if (!$user || !$user->jobLvl) {
            return redirect('/login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        if ($user->jobLvl != 'Administrator') {
            // code...
            $data = \App\Models\MaintenanceMode::first();
            // Jika tidak ada izin, tampilkan halaman forbidden
            if ($data->maintenance == true) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sedang Dalam Perbaikan System',
                    ]);
                } else {
                    return response()->view('layouts.maintenance', compact('data'));
                }
            }

            // Lanjutkan ke rute berikutnya jika izin ditemukan
            return $next($request);
        }

        // Lanjutkan ke rute berikutnya jika izin ditemukan
        return $next($request);
    }
}
