@extends('layouts.master')
@section('title', 'Service Type')
@section('PageTitle', 'Service Type')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Service Type</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Service Type</h3>
                <div class="float-right d-none d-sm-inline">
                    <a href="{{ route('v1.service.type.create') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-plus-circle"></i> New Type
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_employee" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Jabatan</th>
                            <th>Action</th>
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
    const _URL = "{{ route('v1.data-karyawan.getData') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_employee").DataTable({
                "paging": true,
                "lengthChange": false,
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
                    { data: "nik" },
                    { data: "fullName" },
                    { data: "email" },
                    { data: "phoneNumber" },
                    { data: "namaJabatan" },
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
            text: "Are you sure you want to delete this Role?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.data-karyawan.destroy') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_employee").DataTable().ajax.reload(null, false);
                        Swal.fire("Deleted!", response.message, "success");
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    },
                });
            } else if (result.dismiss === "cancel") {
                Swal.fire("Cancelled", "Your data is safe :)", "error");
            }
        });
    }

</script>
@endsection
