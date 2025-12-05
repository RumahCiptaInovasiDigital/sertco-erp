@extends('layouts.master')
@section('title', 'Jenis ISO')
@section('PageTitle', 'Tambah Jenis ISO')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.master-iso.index') }}">Maste ISO</a></li>
    <li class="breadcrumb-item active">Tambah</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Jenis ISO</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.master-iso.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Nama ISO<span style="color: #ff0000;">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Input Nama Jenis ISO">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="petugas">Petugas Audit<span style="color: #ff0000;">*</span></label>
                                <input type="text" class="form-control" name="petugas" id="petugas"
                                    placeholder="Input Nama Petugas Audit">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="tgl_audit">Tanggal Audit<span style="color: #ff0000;">*</span></label>
                                <input type="date" class="form-control" name="tgl_audit" id="tgl_audit">
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Upload ISO <span class="text-danger">*</span></label>
                                <div class="card p-3" style="border: 1px solid #dcdcdc;">
                                    <div class="d-flex mb-2">
                                        <div class="mr-4">
                                            <input type="radio" name="upload_type" id="radioFile" value="file" checked>
                                            <label for="radioFile" class="mb-0 ml-1">Upload File</label>
                                        </div>

                                        <div>
                                            <input type="radio" name="upload_type" id="radioLink" value="link">
                                            <label for="radioLink" class="mb-0 ml-1">Upload Link</label>
                                        </div>

                                    </div>
                                    {{-- INPUT FILE --}}
                                    <div id="inputFileIso">
                                        <label for="fileIso">Pilih File ISO (PDF)</label>
                                        <input type="file" class="form-control" name="fileIso" id="fileIso" accept=".pdf">
                                    </div>
                                    {{-- INPUT LINK --}}
                                    <div id="inputLinkIso" style="display:none;">
                                        <label for="linkIso">Masukan URL ISO</label>
                                        <input type="text" class="form-control" name="linkIso" id="linkIso" placeholder="Masukkan Link URL Disini">
                                    </div>

                                </div>
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
                            <a href="{{ route('v1.master-iso.index') }}" class="btn btn-secondary">Batal</a>
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
    $(document).ready(function () {
        // Atur default: file
        $('#inputFileIso').show();
        $('#inputLinkIso').hide();

        // Ketika pilih FILE
        $('#radioFile').on('change', function () {
            $('#inputFileIso').show();
            $('#inputLinkIso').hide();
            $('#linkIso').val("");   // bersihkan link
        });

        // Ketika pilih LINK
        $('#radioLink').on('change', function () {
            $('#inputFileIso').hide();
            $('#inputLinkIso').show();
            $('#fileIso').val("");   // bersihkan file
        });
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
