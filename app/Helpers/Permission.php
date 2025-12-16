<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

function hasPermission(string $url): bool
{
    $user = Auth::user();

    if (!$user || !$user->hasRole || !$user->hasRole->role) {
        return false;
    }

    // ambil array permission dari role user
    $permissions = $user->hasRole->role->permission ?? [];

    foreach ($permissions as $perm) {
        // cek pattern match, tetap pakai Str::is() biar fleksibel
        if (Str::is($url, $perm->url)) {
            return true;
        }
    }

    return false;
}
