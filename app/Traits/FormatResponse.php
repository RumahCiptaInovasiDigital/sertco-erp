<?php

namespace App\Traits;

trait FormatResponse
{
    public function success($data = null, string $message = 'Success')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => true
        ], 200);
    }

    public function successOrError($data = null, string $errorMessage = 'Data not found'){
        if($data){
            return $this->success($data);
        }else{
            return $this->error($errorMessage, null, 404);
        }
    }

    public function error(string $message, $data = null, int $statusCode = 400)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => false
        ], $statusCode);
    }
}
