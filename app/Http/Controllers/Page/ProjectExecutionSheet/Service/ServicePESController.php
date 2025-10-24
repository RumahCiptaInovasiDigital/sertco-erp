<?php

namespace App\Http\Controllers\Page\ProjectExecutionSheet\Service;

use App\Http\Controllers\Controller;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ServiceFormData;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServicePESController extends Controller
{
    public function index($project_no)
    {
        $projectSheet = ProjectSheet::query()
            ->where('project_no', strtoupper($project_no))
            ->first();
        $serviceKategori = KategoriService::all();
        $serviceType = ServiceType::all();

        return view('page.v1.pes.service.index', compact('projectSheet', 'serviceKategori', 'serviceType'));
    }

    public function store(Request $request)
    {
        $is_draft = $request->has('is_draft') ? true : false;

        // ambil semua input manual
        $project_no = $request->project_no;
        $id_kategori = $request->id_kategori;
        $kategori_qty = $request->kategori_qty;
        $service_type = $request->service_type;
        $other_value = $request->other_value;

        if (!$is_draft) {
            // definisikan rules validasi
            $rules = [
                'kategori_qty' => 'required|array',
                'kategori_qty.*' => 'required|integer|min:1',
                'service_type' => [
                    'required',
                    'array',
                    'size:'.(is_array($id_kategori) ? count($id_kategori) : 0),
                ],
                'service_type.*' => 'required|string',
                'other_value' => 'nullable|array',
                'other_value.*' => 'nullable|required_if:service_type.*,"0"|string|max:255',
            ];

            $messages = [
                // JUMLAH (QTY)
                'kategori_qty.required' => 'Semua Quantity item wajib diisi.',
                'kategori_qty.array' => 'Format Quantity kategori tidak valid.',
                'kategori_qty.*.required' => 'Quantity pada kategori nomor :sort_num belum diisi.',
                'kategori_qty.*.integer' => 'Quantity pada kategori nomor :sort_num harus berupa angka.',
                'kategori_qty.*.min' => 'Quantity pada kategori nomor :sort_num minimal 1.',

                // SERVICE TYPE
                'service_type.required' => 'Semua Service Type wajib diisi.',
                'service_type.array' => 'Format Service Type tidak valid.',
                'service_type.size' => 'Semua Service Type wajib diisi.',
                'service_type.*.required' => 'Service Type pada kategori nomor :sort_num belum dipilih.',

                // OTHER VALUE (jika pilih “Other”)
                'other_value.array' => 'Format Other Value tidak valid.',
                'other_value.*.required_if' => 'Other Value pada kategori nomor :sort_num wajib diisi karena Service Type dipilih sebagai Other.',
            ];
            $validator = \Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $formattedErrors = [];

                // Ganti placeholder :position dengan nomor kategori yang sesuai
                foreach ($errors as $key => $msgArray) {
                    if (preg_match('/\.(\d+)$/', $key, $matches)) {
                        $index = (int) $matches[1]; // mulai dari 1
                        foreach ($msgArray as $msg) {
                            $formattedErrors[] = str_replace(':sort_num', $index, $msg);
                        }
                    } else {
                        $formattedErrors = $msgArray;
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => $formattedErrors[0],
                    'errors' => $formattedErrors,
                ]);
            }
        }

        try {
            \DB::beginTransaction();

            // Hapus data lama biar gak dobel kalau update
            ServiceFormData::where('project_no', $project_no)->delete();

            foreach ($id_kategori as $key => $idKategoriService) {
                $qty = $kategori_qty[$key] ?? 0;
                $type = $service_type[$key] ?? null;
                $otherVal = $other_value[$key] ?? null;

                // Cek apakah kategori ini pakai opsi "Other"
                $isOther = $type == '0' ? true : false;

                ServiceFormData::create([
                    'project_no' => $project_no,
                    'id_kategori_service' => $idKategoriService,
                    'id_service_type' => $isOther ? null : $type, // null kalau Other
                    'other' => $isOther,
                    'other_value' => $isOther ? $otherVal : null,
                    'qty' => $qty,
                ]);
            }

            \DB::commit();

            if ($is_draft) {
                return response()->json([
                    'success' => true,
                    'message' => $is_draft ? 'Draft saved successfully.' : 'Data saved successfully.',
                    'redirect' => route('v1.pes.index'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Project Service Saved Sucessfully',
                'redirect' => route('v1.pes.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function edit($project_no)
    {
        $projectSheet = ProjectSheet::query()
            ->where('project_no', strtoupper($project_no))
            ->first();
        $serviceKategori = KategoriService::all();
        $serviceType = ServiceType::all();

        $serviceData = ServiceFormData::query()->where('project_no', strtoupper($project_no));

        return view('page.v1.pes.service.edit', compact('projectSheet', 'serviceKategori', 'serviceType', 'serviceData'));
    }

    public function update(Request $request, $project_no)
    {
        $is_draft = $request->has('is_draft') ? true : false;

        // ambil semua input manual
        $project_no = $request->project_no;
        $id_kategori = $request->id_kategori;
        $kategori_qty = $request->kategori_qty;
        $service_type = $request->service_type;
        $other_value = $request->other_value;

        if (!$is_draft) {
            // definisikan rules validasi
            $rules = [
                'kategori_qty' => 'required|array',
                'kategori_qty.*' => 'required|integer|min:1',
                'service_type' => [
                    'required',
                    'array',
                    'size:'.(is_array($id_kategori) ? count($id_kategori) : 0),
                ],
                'service_type.*' => 'required|string',
                'other_value' => 'nullable|array',
                'other_value.*' => 'nullable|required_if:service_type.*,"0"|string|max:255',
            ];

            $messages = [
                // JUMLAH (QTY)
                'kategori_qty.required' => 'Semua Quantity item wajib diisi.',
                'kategori_qty.array' => 'Format Quantity kategori tidak valid.',
                'kategori_qty.*.required' => 'Quantity pada kategori nomor :sort_num belum diisi.',
                'kategori_qty.*.integer' => 'Quantity pada kategori nomor :sort_num harus berupa angka.',
                'kategori_qty.*.min' => 'Quantity pada kategori nomor :sort_num minimal 1.',

                // SERVICE TYPE
                'service_type.required' => 'Semua Service Type wajib diisi.',
                'service_type.array' => 'Format Service Type tidak valid.',
                'service_type.size' => 'Semua Service Type wajib diisi.',
                'service_type.*.required' => 'Service Type pada kategori nomor :sort_num belum dipilih.',

                // OTHER VALUE (jika pilih “Other”)
                'other_value.array' => 'Format Other Value tidak valid.',
                'other_value.*.required_if' => 'Other Value pada kategori nomor :sort_num wajib diisi karena Service Type dipilih sebagai Other.',
            ];
            $validator = \Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $formattedErrors = [];

                // Ganti placeholder :position dengan nomor kategori yang sesuai
                foreach ($errors as $key => $msgArray) {
                    if (preg_match('/\.(\d+)$/', $key, $matches)) {
                        $index = (int) $matches[1]; // mulai dari 1
                        foreach ($msgArray as $msg) {
                            $formattedErrors[] = str_replace(':sort_num', $index, $msg);
                        }
                    } else {
                        $formattedErrors = $msgArray;
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => $formattedErrors[0],
                    'errors' => $formattedErrors,
                ]);
            }
        }

        try {
            \DB::beginTransaction();

            // Hapus data lama biar gak dobel kalau update
            ServiceFormData::where('project_no', $project_no)->delete();

            foreach ($id_kategori as $key => $idKategoriService) {
                $qty = $kategori_qty[$key] ?? 0;
                $type = $service_type[$key] ?? null;
                $otherVal = $other_value[$key] ?? null;

                // Cek apakah kategori ini pakai opsi "Other"
                $isOther = $type == '0' ? true : false;

                ServiceFormData::create([
                    'project_no' => $project_no,
                    'id_kategori_service' => $idKategoriService,
                    'id_service_type' => $isOther ? null : $type, // null kalau Other
                    'other' => $isOther,
                    'other_value' => $isOther ? $otherVal : null,
                    'qty' => $qty,
                ]);
            }

            \DB::commit();

            if ($is_draft) {
                return response()->json([
                    'success' => true,
                    'message' => $is_draft ? 'Draft saved successfully.' : 'Data saved successfully.',
                    'redirect' => route('v1.pes.index'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Project Service Saved Sucessfully',
                'redirect' => route('v1.pes.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
