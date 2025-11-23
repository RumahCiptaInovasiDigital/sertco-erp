@extends('layouts.master')
@section('title', 'Data Peralatan')
@section('PageTitle', 'Edit Data Peralatan')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataPeralatan</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Data Peralatan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.data-peralatan.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Peralatan</label>
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}" id="name" placeholder="Input Nama Peralatan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Merk</label>
                                <input type="text" class="form-control" name="merk" value="{{ $data->merk }}" id="name" placeholder="Input Merk">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Tipe</label>
                                <input type="text" class="form-control" name="tipe" value="{{ $data->tipe }}" id="name" placeholder="Input Tipe">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Sn (Serial Number)</label>
                                <input type="text" class="form-control" name="serial_number" value="{{ $data->serial_number }}" id="name" placeholder="Input Serial Number">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Tanggal Kalibrasi</label>
                                <input type="date" class="form-control" name="last_calibration" value="{{ $data->last_calibration }}" id="name" placeholder="Input last Calibration">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Kalibrasi Selanjutnya</label>
                                <input type="date" class="form-control" name="due_calibration" value="{{ $data->due_calibration }}" id="name" placeholder="Input Due Calibration">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <select class="form-control select2" name="lokasi" id="lokasi">
                                    <option></option>
                                    <option value="Rusak" {{ $data->lokasi == 'Internal' ? 'selected' : ''}}>Internal</option>
                                    <option value="Expired" {{ $data->lokasi == 'Eksternal' ? 'selected' : ''}}>Eksternal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Status Alat</label>
                                <input type="text" class="form-control" name="status_alat" value="{{ $data->status_alat }}" id="name" placeholder="Input Status Alat">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="kondisi_alat">Kondisi Alat</label>
                                <select class="form-control select2" name="kondisi_alat" id="kondisi_alat">
                                    <option></option>
                                    <option value="Baik" {{ $data->kondisi_alat == 'Baik' ? 'selected' : ''}}>Baik</option>
                                    <option value="Rusak" {{ $data->kondisi_alat == 'Rusak' ? 'selected' : ''}}>Rusak</option>
                                    <option value="Expired" {{ $data->kondisi_alat == 'Expired' ? 'selected' : ''}}>Expired</option>
                                    <option value="Sedang di kalibrasi" {{ $data->kondisi_alat == 'Sedang di kalibrasi' ? 'selected' : ''}}>Sedang di kalibrasi</option>
                                    <option value="Di pinjam" {{ $data->kondisi_alat == 'Di pinjam' ? 'selected' : ''}}>Di pinjam</option>
                                    <option value="Kembali" {{ $data->kondisi_alat == 'Kembali' ? 'selected' : ''}}>Kembali</option>
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
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('#lokasi').select2({
            theme: 'bootstrap4',
            placeholder: 'Lokasi',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#kondisi_alat').select2({
            theme: 'bootstrap4',
            placeholder: 'Kondisi Alat',
        })
    });
</script>
@endsection