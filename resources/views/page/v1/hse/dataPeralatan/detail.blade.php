@extends('layouts.master')
@section('title', 'Peralatan')
@section('PageTitle', 'Detail Peralatan')
@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataPeralatan</a></li>
    <li class="breadcrumb-item active">Detail</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Data Peralatan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                {{-- <form action="{{ route('v1.data-peralatan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf --}}
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Peralatan</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}" placeholder="Input Nama Peralatan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Merk</label>
                                <input type="text" class="form-control" name="merk" id="merk" value="{{ $data->merk }}" placeholder="Merk Peralatan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Tipe</label>
                                <input type="text" class="form-control" name="tipe" id="tipe" value="{{ $data->tipe }}" placeholder="Tipe Peralatan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">SN (Serial Number)</label>
                                <input type="text" class="form-control" name="serial_number" id="serial_number" value="{{ $data->serial_number }}" placeholder="Serial Number" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Last Calibration</label>
                                <input type="text" class="form-control" name="last_calibration" id="last_calibration" value="{{  $data->last_calibration }}" placeholder="Last Calibration" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Due Calibration</label>
                                <input type="text" class="form-control" name="due_calibration" id="due_calibration" value="{{  $data->due_calibration  }}" placeholder="Due Calibration" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Lokasi</label>
                                <input type="text" class="form-control" name="lokasi" id="lokasi" value="{{  $data->lokasi }}" placeholder="Lokasi" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Status Alat</label>
                                <input type="text" class="form-control" name="status_alat" id="status_alat" value="{{ $data->status_alat }}" placeholder="Status Alat" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="kondisi_alat">Kondisi Alat</label>
                                <input type="text" class="form-control" name="kondisi_alat" id="kondisi_alat" value="{{ $data->kondisi_alat }}" placeholder="Status Alat" disabled>
                            </div>
                        </div>
                        <div class="col-12">
                            {{-- <button type="submit" class="btn btn-success">Simpan Data</button> --}}
                            <a href="{{ route('v1.data-peralatan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                {{-- </form> --}}
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
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kondisi',
        })
    });
</script>
@endsection