@extends('layouts.master')
@section('title', 'Role Manage')
@section('PageTitle', 'Role Manage')
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
    <li class="breadcrumb-item"><a href="{{ route('v1.role.index') }}">Role/Jabatan</a></li>
    <li class="breadcrumb-item active">Assigning User</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Users: <strong>{{ $role->name }}</strong></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_data" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIK</th>
                            <th>FullName</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add User</h3>
            </div>
            <div class="card-body">
                <form id="employeeForm" name="employeeForm">
                    <input type="hidden" name="employee_id" id="employee_id">
                    <input type="text" class="d-none" name="fullname">
                    <input type="text" class="d-none" name="nik">

                    <div class="mb-3">
                        <label for="employee" class="form-label">Cari Employee</label>
                        <select class="form-control select2" name="employee" id="employee">
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-block" id="savedata" value="create">Submit
                        Data</button>
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
    const _URL = "{{ route('v1.role.assign.getData', [$role->name, $role->id_role]) }}";

    $(document).ready(function () {
        $('.page-loading').fadeIn();
        setTimeout(function () {
            $('.page-loading').fadeOut();
        }, 1000); // Adjust the timeout duration as needed

        let DT = $("#dt_data").DataTable({
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
                { data: "fullname" },
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

        $('#employee').select2({
            theme: 'bootstrap4',
            minimumInputLength: 2,
            placeholder: 'Pilih Employee',
            ajax: {
                url: "{{ route('v1.role.assign.getEmployee', [strtolower($role->name), $role->id_role]) }}",
                dataType: 'json',
                delay: 150,
                processResults: data => {
                    return {
                        results: data.map(res => {
                            var text = res.fullname
                            return {
                                text: text,
                                id: res.nik,
                                fullname: res.fullname,
                            }
                        })
                    }
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            // Display the selected employee details in the HTML
            $("input[name='nik']").val(data.id);
            $("input[name='fullname']").val(data.fullname);
        });

        $('#savedata').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#employeeForm').serialize(),
                url: "{{ route('v1.role.assign.store', [strtolower($role->name), $role->id_role]) }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {
                    $('#savedata').html('Submit Data');
                    // console.log(data);
                    if (data.success) {
                        Swal.fire({
                            title: "Berhasil !",
                            text: data.message,
                            icon: "success"
                        });
                        $('#employeeForm').trigger("reset");
                        DT.draw();
                    } else {
                        Swal.fire({
                            title: "Gagal !",
                            text: data.message,
                            icon: "info"
                        });
                        $('#employeeForm').trigger("reset");
                        DT.draw();
                    }

                },
                error: function(data) {
                    $('#savedata').html('Submit Data');
                    Swal.fire({
                        title: "Error !",
                        text: data.message,
                        icon: "error"
                    });

                    console.log('Error:', data);
                },
                complete: function() {
                    $('#savedata').html('Submit Data');
                    $("#dt_data").DataTable().ajax.reload(null, false);
                }
            });
        });

        $('body').on('click', '.deletePost', function() {
            var url = $(this).attr("data-url");
            Swal.fire({
                title: "Apakah anda yakin ?",
                text: "Menghapus data users dapat mengakibatkan data yang berelasi akan terhapus",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: "Terhapus !",
                                    text: data.message,
                                    icon: "success"
                                });
                                DT.draw();
                            } else {
                                Swal.fire({
                                    title: "Error System !",
                                    text: data.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                title: "Galat System !",
                                text: data,
                                icon: "error"
                            });
                            console.log('Error:', data);
                        }
                    });

                }
            });
        });
    });
</script>
@endsection
