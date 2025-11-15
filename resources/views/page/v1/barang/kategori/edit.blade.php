@extends('layouts.master')
@section('title', 'Edit Kategori Barang')
@section('PageTitle', 'Edit Kategori Barang')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.barang.kategori.index') }}">Kategori Barang</a></li>
    <li class="breadcrumb-item active">Edit Kategori Barang</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Kategori Barang</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.barang.kategori.update', $data->id_kategori_barang) }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Kategori Barang</label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $data->nama_kategori) }}" id="nama" placeholder="Masukkan Nama Kategori">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode">Kode Kategori</label>
                                    <input type="text" class="form-control" name="kode" value="{{ old('kode', $data->kode_kategori) }}" id="kode" placeholder="Masukkan Kode Kategori">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maintenance">Maintenance</label>
                                    <Select class="form-control select2" name="maintenance" id="maintenance">
                                        <option value="" disabled>-- pilih salah satu --</option>
                                        <option value="Y" {{ $data->maintenance=="Y" ? 'selected' : '' }}>Ya</option>
                                        <option value="T" {{ $data->maintenance=="T" ? 'selected' : '' }}>Tidak</option>
                                    </Select>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                <a href="{{ route('v1.barang.kategori.index') }}"><button type="button" class="btn btn-warning btn-sm">Batal</button></a>
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
