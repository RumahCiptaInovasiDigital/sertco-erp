<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InformationController extends Controller
{
    public function index()
    {
        return view('page.konten.informasi');
    }

    public function data()
    {
        $data = Information::with('user')->select('information.*')->orderBy('created_at','desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_name', function($row) {
                return $row->user ? $row->user->name : '-';
            })
            ->addColumn('type_badge', function($row) {
                $badges = [
                    'general' => '<span class="badge bg-info">Umum</span>',
                    'reminder' => '<span class="badge bg-warning">Reminder</span>',
                    'urgent' => '<span class="badge bg-danger">Urgent</span>',
                ];
                return $badges[$row->type] ?? '-';
            })
            ->addColumn('date_range', function($row) {
                return '<i class="fas fa-calendar"></i> ' . date('j M', strtotime($row->start_date)) . ' - <br> <small class="text-muted">' . date('j M Y ', strtotime($row->end_date)).'</small>';
            })
            ->addColumn('user_name', function($row) {
                return $row->user ? $row->user->fullname : '-';
            })
            ->addColumn('status_badge', function($row) {
                $badges = [
                    'active' => '<span class="badge bg-success">Aktif</span>',
                    'inactive' => '<span class="badge bg-secondary">Tidak Aktif</span>',
                ];
                return $badges[$row->status->value] ?? '-';
            })
            ->addColumn('action', function($row) {
                return '
                    <button class="btn btn-sm btn-info detail-btn" data-id="'.$row->id.'">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning edit-btn" data-id="'.$row->id.'">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['status_badge', 'type_badge', 'date_range', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'type' => 'required',
            'status' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $data = $request->except('attachment');
        $data['id'] = Str::uuid();
        $data['id_user'] = auth()->user()->id_user;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('information', 'public');
            $data['attachment_path'] = $path;
            $data['mime_type'] = $file->getMimeType();
        }

        Information::create($data);

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function show($id)
    {
        $data = Information::with('user')->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'type' => 'required',
            'status' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $information = Information::findOrFail($id);
        $data = $request->except('attachment');

        if ($request->hasFile('attachment')) {
            if ($information->attachment_path) {
                Storage::disk('public')->delete($information->attachment_path);
            }
            $file = $request->file('attachment');
            $path = $file->store('information', 'public');
            $data['attachment_path'] = $path;
            $data['mime_type'] = $file->getMimeType();
        }

        $information->update($data);

        return response()->json(['message' => 'Data berhasil diupdate']);
    }

    public function destroy($id)
    {
        $information = Information::findOrFail($id);

        if ($information->attachment_path) {
            Storage::disk('public')->delete($information->attachment_path);
        }

        $information->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
