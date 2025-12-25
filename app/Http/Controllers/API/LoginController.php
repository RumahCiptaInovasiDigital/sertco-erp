<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserCredential;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    use \App\Traits\FormatResponse;
    public function index()
    {
        return $this->successOrError(
            data: Auth::guard('api')->user()
        );
    }

    public function store(LoginRequest $request){
       $credential = $request->validated();

       $user = UserCredential::query()->where('nik', $credential['nik'])->first();
       if( !Hash::check($credential['password'], $user?->pass ?? '') ){
           return $this->error( 'Credentials is not match', statusCode: 401);
       }

       $token = $user->createToken();

       return $this->success(
           data: [
               "user" => $user,
               "token" => $token,
           ]
       );
    }

    public function show($id){
        return $this->successOrError(  \request()->user() );
    }

    public function destroy(Request $request){
        $auth = $request->header('Authorization');
        $token = explode(" ", $auth)[1] ?? null;
        PersonalAccessToken::findToken($token)->delete();
        return $this->success(
            message: "Logout success",
        );
    }
}
