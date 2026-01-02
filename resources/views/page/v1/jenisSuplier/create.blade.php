@extends('layouts.master')
@section('title', 'Tambah Jenis Suplier')
@section('PageTitle', 'Tambah Jenis Suplier')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.jenis-suplier.index') }}">Jenis Suplier</a></li>
    <li class="breadcrumb-item active">Tambah Jenis Suplier</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Jenis Suplier</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.jenis-suplier.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Jenis Suplier</label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" id="nama" placeholder="Masukkan Nama Jenis Suplier">
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                <button type="reset" class="btn btn-warning btn-sm">Batal</button>
                            </div>
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
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    });
</script>
@endsection
