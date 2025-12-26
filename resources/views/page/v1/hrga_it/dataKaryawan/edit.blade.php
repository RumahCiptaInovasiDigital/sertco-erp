@extends('layouts.master')
@section('title', 'Edit Data')
@section('PageTitle', 'Edit Data Karyawan')
@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataKaryawan</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection

@section('content')
<form method="POST" action="{{ route('v1.data-karyawan.update', $karyawan->id) }}" enctype="multipart/form-data">
@csrf
    <div class="row">
        <div class="col-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>Edit Data Karyawan
                    </h3>
                </div>

                <div class="card-body">
                    {{-- ================= FOTO KARYAWAN ================= --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Foto Karyawan</strong>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">

                                <div class="col-md-3 text-center">
                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                        style="width:180px;height:180px;">
                                        <img id="photoPreview"
                                            src="{{ $karyawan->foto 
                                                    ? asset('storage/foto/'.$karyawan->foto) 
                                                    : asset('assets/img/user-default.png') }}"
                                            class="img-fluid rounded"
                                            style="width:100%;height:100%;object-fit:cover;">
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Upload Foto Baru</label>
                                        <input type="file"
                                            name="foto"
                                            class="form-control-file"
                                            accept="image/*"
                                            onchange="previewPhoto(this)">
                                        <small class="text-muted">
                                            JPG / PNG • Max 2MB
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= INFORMASI PRIBADI ================= --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Informasi Pribadi</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="firstName"
                                            value="{{ old('firstName', $karyawan->firstName) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lastName"
                                            value="{{ old('lastName', $karyawan->lastName) }}">
                                    </div>
                                </div>
                                <div class="col-md-4"></div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempatLahir"
                                            value="{{ old('tempatLahir', $karyawan->tempatLahir) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggalLahir"
                                            value="{{ old('tanggalLahir', $karyawan->tanggalLahir) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Agama</label>
                                        <select name="agama" class="form-control">
                                            <option value="">-- Pilih Agama --</option>
                                            @php
                                                $listAgama = [
                                                    'Islam',
                                                    'Kristen Protestan',
                                                    'Kristen Katolik',
                                                    'Hindu',
                                                    'Buddha',
                                                    'Konghucu'
                                                ];
                                            @endphp
                                
                                            @foreach($listAgama as $agama)
                                                <option value="{{ $agama }}"
                                                    {{ old('agama', $karyawan->agama) === $agama ? 'selected' : '' }}>
                                                    {{ $agama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea class="form-control" rows="2" name="alamat">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= INFORMASI KEPEGAWAIAN ================= --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Informasi Kepegawaian</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="{{ $karyawan->nik }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" class="form-control" name="namaJabatan"
                                            value="{{ old('namaJabatan', $karyawan->namaJabatan) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departemen</label>
                                        <input type="text" class="form-control" name="namaDepartemen"
                                            value="{{ old('namaDepartemen', $karyawan->namaDepartemen) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Grade</label>
                                        <input type="text" class="form-control" name="grade"
                                            value="{{ old('grade', $karyawan->grade) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Join</label>
                                        <input type="date" class="form-control" name="joinDate"
                                            value="{{ old('joinDate', $karyawan->joinDate) }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= LEGAL & KEUANGAN ================= --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Legal & Keuangan</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No KTP</label>
                                        <input type="text" class="form-control" name="noKTP"
                                            value="{{ old('noKTP', $karyawan->noKTP) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NPWP</label>
                                        <input type="text" class="form-control" name="noNPWP"
                                            value="{{ old('noNPWP', $karyawan->noNPWP) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No Rekening</label>
                                        <input type="text" class="form-control" name="noRekening"
                                            value="{{ old('noRekening', $karyawan->noRekening) }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= KONTAK DARURAT ================= --}}
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Kontak Darurat</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="emergencyName"
                                            value="{{ old('emergencyName', $karyawan->emergencyName) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Hubungan</label>
                                        <input type="text" class="form-control" name="emergencyRelation"
                                            value="{{ old('emergencyRelation', $karyawan->emergencyRelation) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>No Kontak</label>
                                        <input type="text" class="form-control" name="emergencyContact"
                                            value="{{ old('emergencyContact', $karyawan->emergencyContact) }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('v1.data-karyawan.show', $karyawan->id) }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader()
            reader.onload = e => {
                document.getElementById('photoPreview').src = e.target.result
            }
            reader.readAsDataURL(input.files[0])
        }
    }
    $(function () {
        //Initialize Select2 Elements
        $('#agama').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Agama',
        });

        // Ketika pilihan agama berubah
        $('#agama').on('change', function () {
            if ($(this).val() === 'lainnya') {
                $('#agamaLain').removeClass('d-none').attr('required', true);
            } else {
                $('#agamaLain').addClass('d-none').val('').removeAttr('required');
            }
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#statusTK').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status TK',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#statusPTKP').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status PTKP',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#BpjsKes').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Bpjs',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#AXA').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status AXA',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#emergencyRelation').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Hubungan Kontak',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#grade').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Grade',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#departemen').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Departemen',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#jabatan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Jabatan',
        })
    });

</script>
@endsection
