<?php

namespace App\Http\Controllers\System\AuditTrail;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function getData(Request $request)
    {
        $query = ActivityLog::query()->orderBy('created_at', 'Desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                $parse = Carbon::parse($row->created_at);

                return $parse->translatedFormat('l, ').
                    $parse->locale('en-ID')->translatedFormat('d M Y H:i');
            })
            ->editColumn('action', function ($row) {
                return ucwords($row->action) ?? '-';
            })
            ->editColumn('description', function ($row) {
                return ucwords($row->description) ?? '-';
            })
            ->addColumn('user', function ($row) {
                $email = optional($row->users)->email;

                return strtoupper($email ?? $row->userEmail ?? '-');
            })
            ->addColumn('compName', function ($row) {
                $resultJson = optional($row->users)->result;

                // Validate that result is a JSON string
                $resultArray = [];
                if (!empty($resultJson) && is_string($resultJson)) {
                    $decoded = json_decode($resultJson, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $resultArray = $decoded;
                    }
                }

                return strtoupper($resultArray['CompName'] ?? '-');
            })
            ->rawColumns(['user', 'compName'])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.auditTrail.index');
    }
}
