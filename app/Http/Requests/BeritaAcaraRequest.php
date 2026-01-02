<?php

namespace App\Http\Requests;

use App\Models\DataKaryawan;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BeritaAcaraRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:data_karyawans,id',
            'tanggal' => 'required|date',
            'uraian_kegiatan' => 'required|string',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'hasil_yang_dicapai' => 'nullable|string',
            'file_lampiran' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
        ];
    }

    protected function prepareForValidation()
    {
        $karyawan = DataKaryawan::query()->where('nik', auth('api')->user()->nik)->first();
        $this->merge([
           'karyawan_id' => $karyawan?->id,
           'tanggal' => $this->tanggal ?? Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function messages()
    {
        return [
          'waktu_selesai.after_or_equal' => 'Waktu selesaiharus sama dengan atau setelah waktu mulai.',
          'karyawan_id.exists' => 'Karyawan tidak ditemukan.',
          'tanggal.date' => 'Format tanggal tidak valid.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
