<?php

if(!function_exists('clientIP')) {
    function clientIP(){
        $ipcf = request()->header('cf-connecting-ip');
        $ip1 = request()->header('X-Forwarded-For');
        $ip = request()->ip();

        if ($ipcf) {
            $ip = $ipcf;
        } elseif ($ip1) {
            $ipList = explode(',', $ip1);
            $ip = trim($ipList[0]);
        }

        return $ip;
    }
}
