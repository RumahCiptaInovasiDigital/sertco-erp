@extends('layout.master')
@section('title')
    Permissions Manage
@endsection
@section('main-content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Permissions Manage
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="#" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Admin</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Permission Manage</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"></div>
                    <div class="card-toolbar">
                        @if (auth()->user()->jobLvl === 'Administrator')
                            <button class="btn btn-light-warning me-5" onclick="refreshPermission()">
                                <i class="ki-duotone ki-arrows-circle fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Update Permissions
                            </button>
                        @endif
                        <a href="{{ route('admin.permission.create') }}">
                            <button class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add New
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex align-items-center position-relative my-5">
                            <span class="svg-icon position-absolute ms-4">
                                <i class="ki-duotone ki-magnifier fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <input type="text" id="search_dt" class="form-control border border-2 w-250px ps-14"
                                placeholder="Search Report" />
                        </div>
                        <table id="dt_permission" class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">ID</th>
                                    <th>Job Level</th>
                                    <th>Allowed Urls</th>
                                    <th>Action</th>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">ID</th>
                                    <th>Job Level</th>
                                    <th>Allowed Urls</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        const _URL = "{{ route('admin.permission.getDataTable') }}";

        $(document).ready(function() {
            $('.page-loading').fadeIn();
            setTimeout(function() {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_permission").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: _URL,
                },
                columns: [{
                        data: "DT_RowIndex"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "urls"
                    },
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart +
                            1; // Calculate the row index
                    },
                }, ],
            });

            $('#search_dt').on('keyup', function() {
                DT.search(this.value).draw();
            });
        });
    </script>
    <script>
        function deleteRuang(id) {
            Swal.fire({
                text: "Are you sure you want to delete this Role?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.permission.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            $("#dt_permission").DataTable().ajax.reload(null, false);
                            Swal.fire("Deleted!", response.message, "success");
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                    Swal.fire("Cancelled", "Your data is safe :)", "error");
                }
            });
        }

        function refreshPermission() {
            Swal.fire({
                text: "Are you sure you want to refresh the permissions?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, refresh it!",
                cancelButtonText: "No, cancel!",
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.permission.refresh') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            Swal.fire("Refreshed!", response.message, "success").then(function() {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
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
