@extends('layouts.master')
@section('title', 'Matrix Personil')
@section('PageTitle', 'Matrix Personil')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Matrix Personil</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Matrix Personil</h3>
                <div class="float-right d-none d-sm-inline">
                    <a href="{{ route('v1.matrix-personil.create') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-plus-circle"></i> Input Sertifikat Personil
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="jenis_serti" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Personil</th>
                            <th>Jabatan</th>
                            @foreach ($jenisSerti as $tipe)
                                <th style="text-align: center;">{{ $tipe->name }}</th>
                            @endforeach
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

{{-- <script>
    const _URL = "{{ route('v1.jenis-sertifikat.getData') }}";

    $(document).ready(function () {
        $('.page-loading').fadeIn();
        setTimeout(function () {
            $('.page-loading').fadeOut();
        }, 1000); // Adjust the timeout duration as needed

        let DT = $("#jenis_serti").DataTable({
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
                { data: "name" },
                { data: "pic" },
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
</script> --}}
{{-- <script>
    function deleteData(id) {
        Swal.fire({
            text: "Yakin Ingin Menghapus Jenis Sertifikat Ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak, batal!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.jenis-sertifikat.destroy') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_kategori").DataTable().ajax.reload(null, false);
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

</script> --}}
@endsection
