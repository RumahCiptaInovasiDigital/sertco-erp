@extends('layouts.master')
@section('title', 'Tambah Kategori Barang')
@section('PageTitle', 'Tambah Kategori Barang')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.barang.kategori.index') }}">Kategori Barang</a></li>
    <li class="breadcrumb-item active">Tambah Kategori Barang</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Kategori Barang</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.barang.kategori.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Kategori Barang</label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" id="nama" placeholder="Masukkan Nama Kategori">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maintenance">Maintenance</label>
                                    <Select class="form-control select2" name="maintenance" id="maintenance">
                                        <option value="" disabled selected>-- pilih salah satu --</option>
                                        <option value="Y">Ya</option>
                                        <option value="T">Tidak</option>
                                    </Select>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
                                <button type="reset" class="btn btn-warning btn-lg">Batal</button>
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
