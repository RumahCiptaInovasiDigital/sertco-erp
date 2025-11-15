@extends('layouts.master')
@section('title', 'Peralatan')
@section('PageTitle', 'Tambah Peralatan')
@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataPeralatan</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Peralatan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.data-peralatan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Peralatan</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Input Nama Peralatan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Merk</label>
                                <input type="text" class="form-control" name="merk" id="merk" placeholder="Merk Peralatan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Tipe</label>
                                <input type="text" class="form-control" name="tipe" id="tipe" placeholder="Tipe Peralatan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">SN</label>
                                <input type="text" class="form-control" name="serial_number" id="serial_number" placeholder="Serial Number">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Tanggal Kalibrasi</label>
                                <input type="date" class="form-control" name="last_calibration" id="last_calibration" placeholder="Last Calibration">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Kalibrasi Selanjutnya</label>
                                <input type="date" class="form-control" name="due_calibration" id="due_calibration" placeholder="Due Calibration">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control select2" name="lokasi" id="lokasi">
                                    <option></option>
                                    <option value="Internal">Internal</option>
                                    <option value="Eksternal">Eksternal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Status Alat</label>
                                <input type="text" class="form-control" name="status_alat" id="status_alat" placeholder="Status Alat">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="kondisi_alat">Kondisi Alat</label>
                                <select class="form-control select2" name="kondisi_alat" id="kondisi_alat">
                                    <option></option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Sedang di kalibrasi">Sedang di kalibrasi</option>
                                    <option value="Di pinjam">Di pinjam</option>
                                    <option value="Kembali">Kembali</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.data-peralatan.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>

    $(function () {
        //Initialize Select2 Elements
        $('#lokasi').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Lokasi',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#kondisi_alat').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kondisi',
        })
    });
</script>
@endsection