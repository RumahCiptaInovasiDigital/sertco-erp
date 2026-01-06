<?php

namespace App\Http\Controllers\System\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::latest();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return view('page.admin.feedback.index', [
            'feedbacks' => $query->get()
        ]);
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved'
        ]);

        $feedback->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }
}

