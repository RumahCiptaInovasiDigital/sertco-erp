<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAPIKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apikey = $request->header("x-api-key");
        $locale = $request->header("locale", 'ID');
        $useragent = $request->userAgent();

        \App::setLocale($locale);

        $listapikeywhitelist = ['aksjdlj9i3joijsljdfoijflkjsdkjr9834ur0ijespok', '__alksjd89j3rjoisjdlfkjs9dijf94'];
        if( in_array($apikey, $listapikeywhitelist) && $useragent == 'sertco-mobile' ) {
            $this->addUser();
            return $next($request);
        }

        return \response()->json([
            'message' => __('apikey.invalid'),
            'api-key' => $apikey,
        ], 401);
    }

    private function addUser(){
        $authorization = \request()->header('Authorization');
        $token = explode(" ", $authorization)[1] ?? null;
        if( $token ) {
            $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($personalAccessToken) {
                $name = $personalAccessToken->name; // e.g., "mobile:{nik}"
                $nik = explode(":", $name)[1] ?? null;
                $user = \App\Models\UserCredential::query()->where('nik', $nik)->first();
                if ( ! $user ) {
                    return;
                }
                $personalAccessToken->last_used_at = now();
                $personalAccessToken->save();

                \auth()->guard('api')->setUser($user);
            }
        }
    }
}
