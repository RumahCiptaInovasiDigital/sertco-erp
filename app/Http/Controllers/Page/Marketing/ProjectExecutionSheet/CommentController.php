<?php

namespace App\Http\Controllers\Page\Marketing\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\CommentLike;
use App\Models\ProjectSheetNote;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function load($project_no)
    {
        $comments = ProjectSheetNote::with('user')
        ->where('project_no', $project_no)
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('page.v1.pes.partials.comment', compact('comments'));

        // return response()->json([
        //     'html' => view('page.v1.pes.partials.comment', compact('comments'))->render()
        // ]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'project_no' => 'required|string',
            'comment' => 'nullable|string',
            'parent_id' => 'nullable|uuid|exists:project_sheet_notes,id',
            'image' => 'nullable|image|max:5120' // max 5MB
        ]);

        // validate parent belongs to same project (optional but recommended)
        if ($request->parent_id) {
            $parent = ProjectSheetNote::find($request->parent_id);
            if (!$parent || $parent->project_no !== $request->project_no) {
                return response()->json(['success' => false, 'message' => 'Invalid parent'], 422);
            }
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('comment-images', 'public');
        }

        $note = ProjectSheetNote::create([
            'project_no' => $request->project_no,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'id_user' => auth()->user()->id_user,
            'image_path' => $path
        ]);

        // return response()->json(['success' => true, 'id' => $note->id]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added'
        ]);
    }

    public function toggleLike($id)
    {
        $comment = ProjectSheetNote::findOrFail($id);
        $userId = auth()->user()->id_user;

        $existing = CommentLike::where('comment_id', $id)
                    ->where('id_user', $userId)
                    ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['liked' => false, 'count' => $comment->likes()->count()]);
        }

        CommentLike::create([
            'comment_id' => $id,
            'id_user' => $userId
        ]);

        return response()->json(['liked' => true, 'count' => $comment->likes()->count()]);
    }

}
