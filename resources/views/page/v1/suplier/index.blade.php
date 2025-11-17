@extends('layouts.master')
@section('title', 'Data Suplier')
@section('PageTitle', 'Data Suplier')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Suplier</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List Data Suplier</h3>
                <div class="float-right d-none d-sm-inline">
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalImport">
                        <i class="fas fa-upload"></i> Import (.xlsx)
                    </button>
                    <a href="{{ route('v1.suplier.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Rekap (.xlsx)
                    </a>
                    <a href="{{ route('v1.suplier.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Tambah Data
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_data" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Suplier</th>
                            <th>Telp Suplier</th>
                            <th>Alamat Suplier</th>
                            <th>Email Suplier</th>
                            <th>Nomor Rekening</th>
                            <th>Kontak Person</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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

<script>
    const _URL = "{{ route('v1.suplier.getData') }}";

    $(document).ready(function () {
        $('.page-loading').fadeIn();
        setTimeout(function () {
            $('.page-loading').fadeOut();
        }, 1000); // Adjust the timeout duration as needed

        let DT = $("#dt_data").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: _URL,
            },
            columns: [
                { data: "DT_RowIndex" },
                { data: "nama_suplier" },
                { data: "telp_suplier" },
                { data: "alamat_suplier" },
                { data: "email_suplier" },
                { data: "norek" },
                { data: "cp" },
                {
                    data: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Calculate the row index
                    },
                },
            ],
        });

        // $('#search_dt').on('keyup', function () {
        //     DT.search(this.value).draw();
        // });
    });
</script>
<script>
    function deleteData(id) {
        Swal.fire({
            text: "Yakin Data Ini Akan Dihapus?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yakin, hapus!",
            cancelButtonText: "Tidak, batal!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.suplier.destroy') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_data").DataTable().ajax.reload(null, false);
                        Swal.fire("Deleted!", response.message, "success");
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    },
                });
            } else if (result.dismiss === "cancel") {
                Swal.fire("Cancelled", "Data Anda Aman :)", "info");
            }
        });
    }

</script>
<!-- Import Modal -->
<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">Import Data Suplier (.xlsx)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formImport" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih file Excel (.xlsx)</label>
                        <input type="file" accept=".xlsx" name="file" id="file" class="form-control" required />
                    </div>
                    <p class="text-muted">Format kolom: <a href="{{ asset('assets/format-import-dokumen/FormatDataSuplier.xlsx') }}" target="_blank"><b>Download File Format Import (.xlsx)</b></a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnImportSubmit" class="btn btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formImport').on('submit', function (e) {
            e.preventDefault();

            var fileInput = $('#file');
            if (fileInput.get(0).files.length === 0) {
                Swal.fire('Peringatan', 'Silakan pilih file terlebih dahulu', 'warning');
                return;
            }

            var fd = new FormData(this);
            fd.append('_token', '{{ csrf_token() }}');

            $('#btnImportSubmit').attr('disabled', true).text('Mengunggah...');

            $.ajax({
                url: '{{ route('v1.suplier.import') }}',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#modalImport').modal('hide');
                    $('#btnImportSubmit').attr('disabled', false).text('Upload & Import');
                    $('#file').val('');
                    $('#dt_data').DataTable().ajax.reload(null, false);
                    Swal.fire('Sukses', res.message || 'Import berhasil', 'success');
                },
                error: function (xhr) {
                    var msg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    $('#btnImportSubmit').attr('disabled', false).text('Upload & Import');
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });
</script>
@endsection
