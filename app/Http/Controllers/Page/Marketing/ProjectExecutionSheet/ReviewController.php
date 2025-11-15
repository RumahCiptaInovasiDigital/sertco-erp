<?php

namespace App\Http\Controllers\Page\Marketing\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetNote;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function show($id)
    {
        $data = ProjectSheet::query()
            ->where('id_project', $id)
            ->first();
        $projectSheet = ProjectSheet::query()
        ->where('id_project', $id)
        ->first();
        $serviceKategori = KategoriService::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $serviceType = ServiceType::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $notes = ProjectSheetNote::where('project_no', $data->project_no)->get();

        return view('page.v1.marketing.review.show', compact('data', 'projectSheet', 'serviceKategori', 'serviceType', 'notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_no' => 'required',
            'notes' => 'required|array|min:1',
            'notes.*' => 'required|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();

            foreach ($request->notes as $noteText) {
                ProjectSheetNote::create([
                    'project_no' => $request->project_no,
                    'note' => $noteText,
                    'id_user' => auth()->id(),
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil disimpan.',
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan catatan.',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
