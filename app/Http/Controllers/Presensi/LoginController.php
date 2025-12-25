<?php

namespace App\Http\Controllers\Presensi;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function index()
    {
        return view('page.login');
    }

    public function validasi(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email salah',
            'password.required' => 'Password harus diisi',
        ]);

        if (auth()->attempt($validatedData)) {
            $request->session()->put('user', auth()->user());
            return redirect()->route('dashboard');
        }else{
            return redirect()->route('login')->with('error', 'Email atau password salah!');
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
