@extends('layouts.master')
@section('title', isset($karyawan) ? 'Edit Karyawan' : 'Tambah Karyawan')
@section('PageTitle', isset($karyawan) ? 'Edit Karyawan' : 'Tambah Karyawan')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css">
    <style>
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            margin-bottom: -2px;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-color: transparent;
            border-bottom-color: #007bff;
        }

        .profile-sidebar {
            position: sticky;
            top: 20px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: #6c757d;
            font-weight: 500;
        }

        .stat-value {
            color: #007bff;
            font-weight: 600;
        }

        .profile-photo {
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
        }

        .dropify-wrapper {
            height: 180px !important;
            border: 2px dashed #ddd !important;
            border-radius: 8px !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('master.karyawan.index') }}">Data Karyawan</a></li>
        <li class="breadcrumb-item active">{{ isset($karyawan) ? 'Edit' : 'Tambah' }}</li>
    </ol>
@endsection

@section('content')
    <form id="karyawanForm" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($karyawan))
            @method('PUT')
        @endif

        <div class="row">
            {{-- Sidebar Profile --}}
            <div class="col-md-3">
                <div class="card profile-sidebar">
                    <div class="card-body text-center">
                        <div class="profile-photo">
                            <input type="file" name="foto" id="foto" class="dropify"
                                   @if(isset($karyawan) && $karyawan->foto_url)
                                       data-default-file="{{ $karyawan->foto_url }}"
                                   @endif
                                   data-max-file-size="2M"
                                   data-allowed-file-extensions="jpg jpeg png">
                        </div>

                        <h4 class="mt-3 mb-1">
                            <span id="displayName">{{ $karyawan->fullName ?? 'Nama Karyawan' }}</span>
                        </h4>
                        <p class="text-muted">
                            <span id="displayPosition">{{ $karyawan->jabatan->name ?? 'Jabatan' }}</span>
                        </p>

                        <hr>

                        <div class="stats">
                            <div class="stat-item">
                                <span class="stat-label">NIK</span>
                                <span class="stat-value" id="displayNIK">{{ $karyawan->nik ?? '-' }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Inisial</span>
                                <span class="stat-value" id="displayInisial">{{ $karyawan->inisial ?? '-' }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Status TK</span>
                                <span class="stat-value" id="displayStatusTK">{{ $karyawan->statusTK ?? '-' }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Bergabung</span>
                                <span class="stat-value" id="displayJoinDate">
                                {{ isset($karyawan->joinDate) ? \Carbon\Carbon::parse($karyawan->joinDate)->format('d M Y') : '-' }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content with Tabs --}}
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="karyawan-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="pill" href="#basic" role="tab">
                                    Data Dasar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="personal-tab" data-toggle="pill" href="#personal" role="tab">
                                    Data Personal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="company-tab" data-toggle="pill" href="#company" role="tab">
                                    Data Perusahaan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="document-tab" data-toggle="pill" href="#document" role="tab">
                                    Dokumen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="employment-tab" data-toggle="pill" href="#employment" role="tab">
                                    Kepegawaian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="emergency-tab" data-toggle="pill" href="#emergency" role="tab">
                                    Kontak Darurat
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="karyawan-tabs-content">
                            {{-- Basic Data Tab --}}
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fullName">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="fullName" id="fullName" class="form-control" value="{{ old('fullName', $karyawan->fullName ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName">Nama Depan <span class="text-danger">*</span></label>
                                            <input type="text" name="firstName" id="firstName" class="form-control" value="{{ old('firstName', $karyawan->firstName ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName">Nama Belakang <span class="text-danger">*</span></label>
                                            <input type="text" name="lastName" id="lastName" class="form-control" value="{{ old('lastName', $karyawan->lastName ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nik">NIK <span class="text-danger">*</span></label>
                                            <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik', $karyawan->nik ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inisial">Inisial <span class="text-danger">*</span></label>
                                            <input type="text" name="inisial" id="inisial" class="form-control" value="{{ old('inisial', $karyawan->inisial ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $karyawan->email ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phoneNumber">No. Telepon</label>
                                            <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{ old('phoneNumber', $karyawan->phoneNumber ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Personal Data Tab --}}
                            <div class="tab-pane fade" id="personal" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tempatLahir">Tempat Lahir</label>
                                            <input type="text" name="tempatLahir" id="tempatLahir" class="form-control" value="{{ old('tempatLahir', $karyawan->tempatLahir ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggalLahir">Tanggal Lahir</label>
                                            <input type="date" name="tanggalLahir" id="tanggalLahir" class="form-control" value="{{ old('tanggalLahir', $karyawan->tanggalLahir ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="agama">Agama</label>
                                            <select name="agama" id="agama" class="form-control select2">
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ old('agama', $karyawan->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $karyawan->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama', $karyawan->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama', $karyawan->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama', $karyawan->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama', $karyawan->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pendidikan">Pendidikan Terakhir</label>
                                            <select name="pendidikan" id="pendidikan" class="form-control select2">
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="SD" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA/SMK" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                                <option value="D3" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('pendidikan', $karyawan->pendidikan ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <textarea name="alamat" id="alamat" class="form-control" rows="4">{{ old('alamat', $karyawan->alamat ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Company Data Tab --}}
                            <div class="tab-pane fade" id="company" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="idJabatan">Jabatan <span class="text-danger">*</span></label>
                                            <select name="idJabatan" id="idJabatan" class="form-control select2" required>
                                                <option value="">Pilih Jabatan</option>
                                                @if(isset($roles))
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id_role }}" {{ old('idJabatan', $karyawan->idJabatan ?? '') == $role->id_role ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="idDepartemen">Departemen</label>
                                            <select name="idDepartemen" id="idDepartemen" class="form-control select2">
                                                <option value="">Pilih Departemen</option>
                                                @if(isset($departemens))
                                                    @foreach($departemens as $departemen)
                                                        <option value="{{ $departemen->id_departemen }}" {{ old('idDepartemen', $karyawan->idDepartemen ?? '') == $departemen->id_departemen ? 'selected' : '' }}>
                                                            {{ $departemen->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="grade">Grade</label>
                                            <input type="text" name="grade" id="grade" class="form-control" value="{{ old('grade', $karyawan->grade ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="statusTK">Status Tenaga Kerja <span class="text-danger">*</span></label>
                                            <select name="statusTK" id="statusTK" class="form-control select2" required>
                                                <option value="">Pilih Status TK</option>
                                                <option value="FreeLance" {{ old('statusTK', $karyawan->statusTK ?? '') == 'FreeLance' ? 'selected' : '' }}>Freelance</option>
                                                <option value="PKWT" {{ old('statusTK', $karyawan->statusTK ?? '') == 'PKWT' ? 'selected' : '' }}>PKWT</option>
                                                <option value="PKWTT" {{ old('statusTK', $karyawan->statusTK ?? '') == 'PKWTT' ? 'selected' : '' }}>PKWTT</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="statusPTKP">Status PTKP <span class="text-danger">*</span></label>
                                            <select name="statusPTKP" id="statusPTKP" class="form-control select2" required>
                                                <option value="">Pilih Status PTKP</option>
                                                <option value="Non PTKP" {{ old('statusPTKP', $karyawan->statusPTKP ?? '') == 'Non PTKP' ? 'selected' : '' }}>Non PTKP</option>
                                                <option value="PTKP" {{ old('statusPTKP', $karyawan->statusPTKP ?? '') == 'PTKP' ? 'selected' : '' }}>PTKP</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Document Tab --}}
                            <div class="tab-pane fade" id="document" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="noKTP">No. KTP</label>
                                            <input type="text" name="noKTP" id="noKTP" class="form-control" value="{{ old('noKTP', $karyawan->noKTP ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="noSIM">No. SIM</label>
                                            <input type="text" name="noSIM" id="noSIM" class="form-control" value="{{ old('noSIM', $karyawan->noSIM ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="noNPWP">No. NPWP</label>
                                            <input type="text" name="noNPWP" id="noNPWP" class="form-control" value="{{ old('noNPWP', $karyawan->noNPWP ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="noRekening">No. Rekening</label>
                                            <input type="text" name="noRekening" id="noRekening" class="form-control" value="{{ old('noRekening', $karyawan->noRekening ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nppBpjsTk">NPP BPJS TK</label>
                                            <input type="text" name="nppBpjsTk" id="nppBpjsTk" class="form-control" value="{{ old('nppBpjsTk', $karyawan->nppBpjsTk ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="BpjsKes">BPJS Kesehatan</label>
                                            <select name="BpjsKes" id="BpjsKes" class="form-control">
                                                <option value="">Pilih</option>
                                                <option value="YA" {{ old('BpjsKes', $karyawan->BpjsKes ?? '') == 'YA' ? 'selected' : '' }}>YA</option>
                                                <option value="TIDAK" {{ old('BpjsKes', $karyawan->BpjsKes ?? '') == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="AXA">AXA</label>
                                            <label for="AXA">AXA</label>
                                            <select name="AXA" id="AXA" class="form-control">
                                                <option value="">Pilih</option>
                                                <option value="YA" {{ old('AXA', $karyawan->AXA ?? '') == 'YA' ? 'selected' : '' }}>YA</option>
                                                <option value="TIDAK" {{ old('AXA', $karyawan->AXA ?? '') == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ijazah">Ijazah</label>
                                            <input type="file" name="ijazah" id="ijazah" class="dropify"
                                                   @if(isset($karyawan) && $karyawan->ijazah_url)
                                                       data-default-file="{{ $karyawan->ijazah_url }}"
                                                   @endif
                                                   data-max-file-size="5M"
                                                   data-allowed-file-extensions="pdf jpg jpeg png">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Employment Tab --}}
                            <div class="tab-pane fade" id="employment" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="joinDate">Tanggal Bergabung <span class="text-danger">*</span></label>
                                            <input type="date" name="joinDate" id="joinDate" class="form-control" value="{{ old('joinDate', $karyawan->joinDate ?? '') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="empDateStart">Mulai Kontrak <span class="text-danger">*</span></label>
                                            <input type="date" name="empDateStart" id="empDateStart" class="form-control" value="{{ old('empDateStart', $karyawan->empDateStart ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="empDateEnd">Akhir Kontrak</label>
                                            <input type="date" name="empDateEnd" id="empDateEnd" class="form-control" value="{{ old('empDateEnd', $karyawan->empDateEnd ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="resignDate">Tanggal Resign</label>
                                            <input type="date" name="resignDate" id="resignDate" class="form-control" value="{{ old('resignDate', $karyawan->resignDate ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Emergency Contact Tab --}}
                            <div class="tab-pane fade" id="emergency" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergencyName">Nama Kontak Darurat</label>
                                            <input type="text" name="emergencyName" id="emergencyName" class="form-control" value="{{ old('emergencyName', $karyawan->emergencyName ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="emergencyRelation">Hubungan</label>
                                            <input type="text" name="emergencyRelation" id="emergencyRelation" class="form-control" value="{{ old('emergencyRelation', $karyawan->emergencyRelation ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="emergencyContact">No. Kontak Darurat</label>
                                            <input type="text" name="emergencyContact" id="emergencyContact" class="form-control" value="{{ old('emergencyContact', $karyawan->emergencyContact ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                        <a href="{{ route('master.karyawan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select2({ theme: 'bootstrap4' });

            // Initialize Dropify
            $('.dropify').dropify({
                messages: {
                    'default': '',
                    'replace': '',
                    'remove':  'Hapus',
                    'error':   'Error'
                }
            });

            // Update sidebar display on input change
            $('#fullName').on('input', function() {
                $('#displayName').text($(this).val() || 'Nama Karyawan');
            });

            $('#nik').on('input', function() {
                $('#displayNIK').text($(this).val() || '-');
            });

            $('#inisial').on('input', function() {
                $('#displayInisial').text($(this).val() || '-');
            });

            $('#idJabatan').on('change', function() {
                $('#displayPosition').text($('#idJabatan option:selected').text() || 'Jabatan');
            });

            $('#statusTK').on('change', function() {
                $('#displayStatusTK').text($(this).val() || '-');
            });

            $('#joinDate').on('change', function() {
                if($(this).val()) {
                    var date = new Date($(this).val());
                    var options = { day: 'numeric', month: 'short', year: 'numeric' };
                    $('#displayJoinDate').text(date.toLocaleDateString('id-ID', options));
                }
            });

            // Clear validation errors
            function clearValidationErrors() {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            }

            // Form submission
            $('#karyawanForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = "{{ isset($karyawan) ? route('master.karyawan.update', $karyawan->id) : route('master.karyawan.store') }}";

                if ("{{ isset($karyawan) }}") {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                        clearValidationErrors();
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('master.karyawan.index') }}";
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid').after('<div class="invalid-feedback">' + value[0] + '</div>');
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Validasi!',
                                text: 'Silakan periksa kembali isian Anda.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan server.'
                            });
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                    }
                });
            });
        });
    </script>
@endsection
