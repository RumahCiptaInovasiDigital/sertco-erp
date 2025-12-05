@extends('layouts.master')
@section('title', 'Data Purchase Order')
@section('PageTitle', 'Data Purchase Order')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Purchase Order</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List Data Purchase Order</h3>
                <div class="float-right d-none d-sm-inline">
                    <a href="{{ route('v1.poso-request.po.export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Rekap (.xlsx)
                    </a>
                    <a href="{{ route('v1.poso-request.po.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> PO Request
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_data" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor PO</th>
                            <th>Nama Suplier</th>
                            <th>Tanggal PO</th>
                            <th>Tanggal Dibutuhkan</th>
                            <th>Status PO</th>
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
    const _URL = "{{ route('v1.poso-request.po.getData') }}";

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
                { data: "no_po" },
                { data: "nama_suplier" },
                { data: "tanggal_po" },
                { data: "tanggal_dibutuhkan" },
                { data: "status" },
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
                    url: "{{ route('v1.poso-request.po.destroy') }}",
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

    function sendData(id) {
        Swal.fire({
            text: "Yakin Data Ini Akan Dikirim?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yakin, kirim!",
            cancelButtonText: "Tidak, batal!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.poso-request.po.send') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_data").DataTable().ajax.reload(null, false);
                        Swal.fire("Sending!", response.message, "success");
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    },
                });
            } else if (result.dismiss === "cancel") {
                Swal.fire("Cancelled", "Aman Bro :)", "info");
            }
        });
    }

    function cancelData(id) {
        Swal.fire({
            text: "Yakin Data Ini Akan Ditarik Kembali?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yakin, tarik!",
            cancelButtonText: "Tidak, batal!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.poso-request.po.cancel') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_data").DataTable().ajax.reload(null, false);
                        Swal.fire("Canceled!", response.message, "success");
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    },
                });
            } else if (result.dismiss === "cancel") {
                Swal.fire("Canceled", "Aman Bro :)", "info");
            }
        });
    }
</script>
@endsection
