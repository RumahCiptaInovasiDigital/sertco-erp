@extends('layouts.master')
@section('title', 'Peminjaman')
@section('PageTitle', 'Tambah Peminjaman Alat')
@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataPeminjaman</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Peminjaman Alat</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.data-peminjaman.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="hidden" name="name" value="{{ auth()->user()->id_user }}">
                                <input type="text" value="{{ auth()->user()->fullname }}" class="form-control" name="name" id="name" placeholder="Input FullName" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="nik">Nik</label>
                                <input type="hidden" name="prepared_by" value="{{ auth()->user()->id_user }}">
                                <input type="text" class="form-control" value="{{ auth()->user()->nik }}" name="nik" id="nik" placeholder="Input Nama Peralatan" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="departemen">Departemen</label>
                                <input type="hidden" name="departemen" value="{{ auth()->user()->id_user }}">
                                <input type="text" class="form-control" value="{{ auth()->user()->namaDepartemen }}" name="departemen" id="departemen" placeholder="Input Nama Peralatan" readonly>
                            </div>
                        </div>
                        <hr>
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