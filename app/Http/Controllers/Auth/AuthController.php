<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\User;
use App\Models\UserCredential;
use App\Services\System\LogActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ], [
            'nik.required' => 'Masukan Nomor Induk Karyawan',
            'password.required' => 'Masukan Password',
        ]);

        // Membuat array $kredensil langsung
        $kredensil = $request->only('nik', 'password');

        // check User
        $user = User::where('nik', $request->nik)->first();
        if (!empty($user) && $user->jabatan == 'Administrator') {
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
            $data = DataKaryawan::query()
                ->select(['nik', 'fullName', 'email', 'namaJabatan'])
                ->where('nik', $request->nik)
                ->first();

            if (empty($data)) {
                // $logData = [
                //     'model' => null,
                //     'model_id' => null,
                //     'user_id' => null,
                //     'userEmail' => $kredensil['email'],
                //     'action' => 'LOGIN',
                //     'description' => 'Salah Username atau Password',
                //     'old_data' => null,
                //     'new_data' => null,
                // ];
                // (new LogService())->handle($logData);

                return response()->json([
                    'success' => false,
                    'message' => 'Data Karyawan Tidak Ditemukan di Sistem HR',
                    'redirect' => route('login'),
                ]);
            } else {
                $employee = $this->getAccount($request);
                if (\Auth::attempt($kredensil)) {
                    (new LogActivityService())->handle([
                        'user' => strtoupper($employee['fullname'].' ('.$employee['inisial'].')'),
                        'tindakan' => 'Login',
                        'catatan' => 'Berhasil Login Account',
                    ]);

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
                        'message' => 'NIK atau Password Salah, Silahkan Coba Lagi',
                        'redirect' => route('login'),
                    ]);
                }
            }
        }
    }

    public function logout(Request $request)
    {
        if (auth()->check() && auth()->user()->jabatan != 'Administrator') {
            (new LogActivityService())->handle([
                'user' => strtoupper(auth()->user()->karyawan->fullName.' ('.auth()->user()->karyawan->inisial.')'),
                'tindakan' => 'LOGOUT',
                'catatan' => 'User Berhasil Logout Sistem',
            ]);
            auth()->user()->delete();
        }

        \Auth::logout(); // Log out the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the session token

        return redirect(route('login'))->with('success', 'Logout Berhasil');
    }

    public function getAccount($request)
    {
        try {
            \DB::beginTransaction();

            $employee = DataKaryawan::where('nik', $request->nik)->first();
            $check = User::where('nik', $request->nik)->first();

            $userCredential = UserCredential::where('nik', $request->nik)->first();
            if (!Hash::check($request->password, $userCredential->pass)) {
                return back()->withErrors(['password' => 'Password salah']);
            }

            if (empty($check)) {
                // code...
                User::create([
                    'id_user' => $employee->id,
                    'nik' => $employee->nik,
                    'fullname' => $employee->fullName,
                    'email' => $employee->email,
                    'jabatan' => $employee->namaJabatan,
                    'password' => \Hash::make($request->password),
                ]);
            } else {
                $check->update([
                    'nik' => $employee->nik,
                    'fullname' => $employee->fullName,
                    'email' => $employee->email,
                    'jabatan' => $employee->namaJabatan,
                    'password' => \Hash::make($request->password),
                ]);
            }

            \DB::commit();

            return [
                'nik' => $employee->nik,
                'fullname' => $employee->fullName,
                'inisial' => $employee->inisial,
                'email' => $employee->email,
                'jabatan' => $employee->namaJabatan,
            ];
        } catch (\Throwable $th) {
            // throw $th;
            \Log::error($th);
            \DB::rollBack();

            return null;
        }
    }

    public function fetchData()
    {
        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-API-Key' => 'sq_sxUhbDBq7N+wGnEFAZ9DI8aQRIoxOM2VefhieXOYbVvuSYqo',
        ])->get('http://127.0.0.1:8090/api/getAllKaryawan');

        return $response->json();
    }
}
