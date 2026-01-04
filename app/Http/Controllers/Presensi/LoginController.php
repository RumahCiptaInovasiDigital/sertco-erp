<?php

namespace App\Http\Controllers\Presensi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    private $clientidWhitelist = ['A930d001-5F8B-4D6C-8C2D-3E2F3B4C5D6E'];
    private $clientSecretWhitelist = ['BF7E8A9C-0D1E-4F2A-9B3C-4D5E6F7A8B9C'];
    private $redirecturiWhitelist = [
        'https://presensi.sertcoquality.com/sso/callback',
    ];

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

    public function sso(Request $request)
    {

        $clientid = $request->query('client_id');
        $redirecturi = $request->query('redirect_uri');


        if(!in_array($redirecturi, $this->redirecturiWhitelist)){
            return redirect()->route('login')->with('error', 'Redirect URI tidak valid!');
        }

        if(!in_array($clientid, $this->clientidWhitelist)){
            return redirect()->route('login')->with('error', 'Client ID '.$clientid.' tidak valid!');
        }

        $code = Str::uuid();
        Cache::remember('sso_token_'.$code, 300, function() use ($code) {
            return $code;
        });

        return redirect()->away($redirecturi.'?code='.$code.'&client_id='.$clientid);
    }

    public function token(Request $request)
    {
       $clientid = $request->post('client_id');
       $clientSecret = $request->post('client_secret');
       $code = $request->post('code');

        if(!in_array($clientid, $this->clientidWhitelist)){
            return response()->json(['error' => 'Client ID "'.$clientid.'" tidak valid!'], 400);
        }

        if(!in_array($clientSecret, $this->clientSecretWhitelist)){
            return response()->json(['error' => 'Client Secret "'.$clientSecret.'" tidak valid!'], 400);
        }
        if(!Cache::has('sso_token_'.$code)){
            return response()->json(['error' => 'Kode tidak valid atau sudah kadaluarsa!'], 400);
        }

        $user = auth()?->user();
        if(!$user){
            return response()->json(['error' => 'User tidak ditemukan!', 'user'=>$user], 404);
        }

        $accesstoken = Str::uuid();

        Cache::remember('sso_access_token_'.$accesstoken, 3600, function() use ($user) {
           return base64_encode(json_encode([
                'id_user' => $user->id_user,
                'nama' => $user->nama,
                'email' => $user->email,
            ]));
        });

        return response()->json([
            'access_token' => $accesstoken,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]);
    }

    public function userinfo(){
        $accesstoken = request()->bearerToken();
        if(!Cache::has('sso_access_token_'.$accesstoken)){
            return response()->json(['error' => 'Access Token tidak valid atau sudah kadaluarsa!'], 400);
        }

        $accessdata = Cache::get('sso_access_token_'.$accesstoken);
        $userdata = json_decode(base64_decode($accessdata), true);
        return response()->json([
            'id_user' => $userdata['id_user'],
            'nama' => $userdata['nama'],
            'email' => $userdata['email'],
        ]);
    }
}
