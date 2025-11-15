@extends('layouts.master')
@section('title', 'Project Execution Sheet')
@section('PageTitle', 'Project Execution Sheet')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Project Execution Sheet</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        {{-- <hr class="mt-3">
        <h5>Newest Project</h5> --}}
        <span class="badge badge-success mb-1">Newest Project</span>
        <div class="row row-cols-2 row-cols-md-5">
            @foreach ($data2 as $sheet)
            <div class="col mb-4">
                <div class="card">
                    <img src="{{ asset('dist/img/project-img.jpg') }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title badge badge-info text-bold mb-2">{{ $sheet->project_no }}</h5>
                        <div class="float-right">
                            <i class="fas fa-user"></i> {{ $sheet->karyawan->inisial ?? '-' }}
                        </div>
                        <div class="card-text">
                            <div class="row">
                                <div class="col-12">
                                    Client: {{ $sheet->project_sheet_detail->client ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="#" class="btn btn-sm bg-teal">
                                <i class="fas fa-comments"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-user"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Project Execution Sheet List</h3>
                <div class="float-right d-none d-sm-inline">
                    {{-- Filter: 
                    <div class="d-inline-block mr-4 ml-1">
                        <select id="pes_filter" class="form-control btn btn-sm" onchange="changePesFilter(this.value)">
                            <option value="all">All</option>
                            <option value="draft">Draft</option>
                            <option value="non-draft">Non-draft</option>
                        </select>
                    </div> --}}
                    <div class="float-right">
                        <a href="{{ route('v1.pes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle"></i> New Project
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_pes" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 10%;">Project No.</th>
                            <th>Prepared By</th>
                            <th>Issued Date</th>
                            <th>To</th>
                            <th>Attention</th>
                            <th>Created at</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
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
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    var _URL = "{{ route('v1.pes.getData', 'all') }}";

    $(document).ready(function () {
        $('.page-loading').fadeIn();
        setTimeout(function () {
            $('.page-loading').fadeOut();
        }, 1000); // Adjust the timeout duration as needed

        let DT = $("#dt_pes").DataTable({
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
                { data: "project_no" },
                { data: "prepared_by" },
                { data: "issued_date" },
                { data: "to" },
                { data: "attn" },
                { data: "created_at" },
                { data: "is_draft" },
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
    });

    function deleteData(id) {
        Swal.fire({
            text: "Are you sure you want to delete this Project Sheet?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.pes.destroy') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_pes").DataTable().ajax.reload(null, false);
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

    function changePesFilter(val){
        if (typeof _URL === 'undefined') return;
        var newURL = _URL.replace(/\/[^\/]+$/, '/' + val);
        _URL = newURL;
        $('#dt_pes').DataTable().ajax.url(newURL).load();
    }

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
        })
    });
</script>
@endsection
