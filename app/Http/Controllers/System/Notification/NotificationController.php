<?php

namespace App\Http\Controllers\System\Notification;

use App\Http\Controllers\Controller;
use App\Models\MasterNotifikasi;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function getData()
    {
        $query = MasterNotifikasi::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('admin.notification.edit', $row->id).'" class="btn btn-warning btn-sm ms-5"><i class="fas fa-edit"></i></a>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.admin.notification.index');
    }

    public function create()
    {
        return view('page.admin.notification.create');
    }

    public function store()
    {
    }

    public function edit($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy()
    {
    }
}
