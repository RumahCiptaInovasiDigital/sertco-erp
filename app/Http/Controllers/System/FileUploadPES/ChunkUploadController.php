<?php

namespace App\Http\Controllers\System\FileUploadPES;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChunkUploadController extends Controller
{
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'uploadId'   => 'required|string',
            'fieldName'  => 'required|string',
            'index'      => 'required|integer',
            'chunk'      => 'required|file'
        ]);

        $uploadId  = $request->input('uploadId');
        $fieldName = $request->input('fieldName');
        $index     = $request->input('index');
        $chunk     = $request->file('chunk');

        // simpan chunk ke storage/app/chunks/{uploadId}/{fieldName}/{index}
        $dir = "chunks/{$uploadId}/{$fieldName}";
        Storage::makeDirectory($dir);
        $chunk->storeAs($dir, (string)$index);

        return response()->json(['ok' => true], Response::HTTP_OK);
    }

    // gabungkan semua chunk menjadi file sementara di storage/app/tmp/{safeName}
    public function completeChunk(Request $request)
    {
        $request->validate([
            'uploadId'    => 'required|string',
            'fieldName'   => 'required|string',
            'fileName'    => 'required|string',
            'totalChunks' => 'required|integer'
        ]);

        $uploadId    = $request->input('uploadId');
        $fieldName   = $request->input('fieldName');
        $originalName= $request->input('fileName');
        $totalChunks = (int) $request->input('totalChunks');

        $tempDir = storage_path("app/private/chunks/{$uploadId}/{$fieldName}");
        if (!is_dir($tempDir)) {
            return response()->json(['error' => 'temp not found'], Response::HTTP_BAD_REQUEST);
        }

        // buat nama final sementara yang aman
        $safeName = Str::random(8) . '_' . preg_replace('/[^A-Za-z0-9\-\._]/', '_', $originalName);
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $finalPath = $tmpDir . DIRECTORY_SEPARATOR . $safeName;
        $out = fopen($finalPath, 'ab');

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkFile = "{$tempDir}/{$i}";
            if (!file_exists($chunkFile)) {
                fclose($out);
                return response()->json(['error' => "missing chunk {$i}"], Response::HTTP_BAD_REQUEST);
            }
            $in = fopen($chunkFile, 'rb');
            stream_copy_to_stream($in, $out);
            fclose($in);
        }

        fclose($out);

        // hapus temp chunks agar bersih
        \File::deleteDirectory(storage_path("app/private/chunks/{$uploadId}"));

        // kembalikan path relatif ke storage (untuk dipindah di store controller)
        // misal: tmp/abc_xyz.pdf  -> full storage path = storage_path('app/tmp/abc_xyz.pdf')
        $publicRelative = "tmp/{$safeName}";

        return response()->json([
            'success' => true,
            'tmp_path' => $publicRelative,
            'tmp_filename' => $safeName,
        ]);
    }
}
