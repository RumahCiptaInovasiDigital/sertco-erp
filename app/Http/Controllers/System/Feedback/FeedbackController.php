<?php

namespace App\Http\Controllers\System\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bug,ui,feature,performance,other',
            'feedback' => 'required|string|max:1500',
            'page' => 'nullable|string'
        ]);

        Feedback::create([
            'user_id' => auth()->user()->karyawan->id,
            'type' => $request->type,
            'message' => $request->feedback,
            'page' => $request->page,
            'browser' => $request->header('User-Agent'),
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dikirim'
        ]);
    }
}

