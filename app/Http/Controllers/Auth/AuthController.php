<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Masukan EMail',
            'email.email' => 'EMail Tidak Valid',
            'password.required' => 'Masukan Password',
        ]);

        // Membuat array $kredensil langsung
        $kredensil = $request->only('email', 'password');

        // check User
        $user = User::where('email', $request->email)->first();
        if (!empty($user) && $user->jobLvl == 'Administrator') {
            // code...
            if (\Auth::attempt($kredensil)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil, Selamat Datang di '.env('APP_NAME'),
                    'redirect' => route('v1.dashboard'),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal Silahkan Ulangi',
                    'redirect' => route('login'),
                ]);
            }
        } else {
            if (\Auth::attempt($kredensil)) {
                // $data = json_decode(auth()->user()->result, true);
                // (new LogActivityService())->handle([
                //     'perusahaan' => strtoupper($data['CompName']),
                //     'user' => strtoupper($request->email),
                //     'tindakan' => 'Login',
                //     'catatan' => 'Berhasil Login Account',
                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil, Selamat Datang di '.env('APP_NAME'),
                    'redirect' => route('v1.dashboard'),
                ]);
            } else {
                // (new LogActivityService())->handle([
                //     'perusahaan' => '-',
                //     'user' => strtoupper($request->email),
                //     'tindakan' => 'Login',
                //     'catatan' => 'Salah Password atau Username',
                // ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal Silahkan Ulangi',
                    'redirect' => route('login'),
                ]);
            }
        }
    }

    public function logout()
    {
        \Auth::logout();

        return redirect(route('login'));
    }
}
