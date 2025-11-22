@extends('layouts.master')
@section('title', 'Edit Jenis Serti')
@section('PageTitle', 'Edit Jenis Sertifikat')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.jenis-sertifikat.index') }}">Edit Jenis Sertifikat</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Jenis Sertifikat</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.jenis-sertifikat.update', $data->id_sertifikat) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Sertifikat <span style="color: #ff0000;">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}"
                                    placeholder="Input Nama Sertifikat">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="pic">PIC <span style="color: #ff0000;">*</span></label>
                                <select class="form-control select2" name="pic" id="idPic" required>
                                    <option></option>
                                    @foreach ($role as $item)
                                    <option value="{{ $item->id_role }}" {{ $item->id_role == $data->pic  ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
        $('#idPic').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih PIC',
        })
    });

    $('#dt_kategori').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>
@endsection
