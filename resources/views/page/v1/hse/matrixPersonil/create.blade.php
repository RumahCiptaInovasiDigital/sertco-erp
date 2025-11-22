@extends('layouts.master')
@section('title', 'Sertifikat Personil')
@section('PageTitle', 'Input Sertifikat Personil')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.jenis-sertifikat.index') }}">Matrix Personil</a></li>
    <li class="breadcrumb-item active">Input</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Sertifikat Personil</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.jenis-sertifikat.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="nik_karyawan">Nama Karyawan <span style="color: #ff0000;">*</span></label>
                                <select class="form-control select2 karyawanSelect" name="nik_karyawan" id="nik_karyawan" required>
                                        <option></option>
                                        @foreach ($karyawan as $data)
                                            <option value="{{ $data->id }}">{{ $data->fullName }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6"></div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="email">email</label>
                                <input type="text" class="form-control email" name="email" id="email" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="phoneNumber">Nomor Hp</label>
                                <input type="text" class="form-control nomor" name="phoneNumber" id="phoneNumber" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="namaJabatan">Jabatan</label>
                                <input type="text" class="form-control jabatan" name="namaJabatan" id="namaJabatan" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="namaDepartemen">Departemen</label>
                                <input type="text" class="form-control departemen" name="namaDepartemen" id="namaDepartemen" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        @foreach ($sertifikat as $item)
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="pic">PIC</label>
                                <input type="text" class="form-control" name="pic" id="pic" value="{{ $item->jabatan->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="name">Jenis Sertifikat</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $item->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="file_serti">File Sertifikat</label>
                                <input type="file" class="form-control departemen" name="file_serti" id="file_serti" value="{{ $item->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control departemen" name="due_date" id="due_date">
                            </div>
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <label></label>
                                <button class="btn btn-md btn-success" type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                        @endforeach
                        <div class="col-md-12">
                            <small><b><span style="color: #ff0000;">(*)</span> <em>Wajib Diisi</em></b></small>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('v1.jenis-sertifikat.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('#nik_karyawan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Karyawan',
        })
    });

    // Auto fill data karyawan
    $(document).on('change', '.karyawanSelect', function () {
        let nik_karyawan = $(this).val();
        // let row = $(this).closest('.alat-row');

        if (nik_karyawan) {
            $.ajax({
                url: '/v1/matrix-personil/karyawan/' + nik_karyawan,
                type: 'GET',
                success: function (data) {
                    $('.email').val(data.email);
                    $('.nomor').val(data.phoneNumber);
                    $('.jabatan').val(data.namaJabatan);
                    $('.departemen').val(data.namaDepartemen);
                }
            });
        } else {
            $('.email').val('');
            $('.nomor').val('');
            $('.jabatan').val('');
            $('.departemen').val('');
        }
    });
</script>
@endsection
