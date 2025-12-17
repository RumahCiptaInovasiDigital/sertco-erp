@extends('layouts.master')
@section('title', 'Data Role')
@section('PageTitle', 'Data Role')

@section('head')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Master</li>
        <li class="breadcrumb-item active">Data Role</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Role</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" id="add-btn">
                    <i class="fas fa-plus"></i> Tambah Role
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="role-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Role</th>
                    <th>Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Form Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="role-form">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="role_id">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-name"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
                    </div>
                </form>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#role-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('presensi.master.role.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#add-btn').click(function () {
                $('#role-form').trigger("reset");
                $('#roleModalLabel').html("Tambah Role");
                $('#role-modal').modal('show');
                $('#role_id').val('');
                $('#alert-name').addClass('d-none');
            });

            $('body').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $.get("{{ url('master/role-edit') }}/" + id, function (data) {
                    $('#roleModalLabel').html("Edit Role");
                    $('#save-btn').val("edit-role");
                    $('#role-modal').modal('show');
                    $('#role_id').val(data.id_role);
                    $('#name').val(data.name);
                    $('#alert-name').addClass('d-none');
                })
            });

            $('#role-form').submit(function (e) {
                e.preventDefault();
                $("#save-btn").html('Menyimpan...').attr('disabled', 'disabled');
                var formData = new FormData(this);
                var id = $('#role_id').val();
                var url = id ? "{{ url('master/role-update') }}/" + id : "{{ route('presensi.master.role.store') }}";
                var method = id ? 'POST' : 'POST';

                if(id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#role-modal').modal('hide');
                        table.draw();
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.success, timer: 1500, showConfirmButton: false });
                        $("#save-btn").html('Simpan').removeAttr('disabled');
                    },
                    error: function (data) {
                        let errors = data.responseJSON.errors;
                        if(errors.name){
                            $('#alert-name').removeClass('d-none');
                            $('#alert-name').html(errors.name[0]);
                        }
                        $("#save-btn").html('Simpan').removeAttr('disabled');
                    }
                });
            });

            $('body').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('master/role-delete') }}/" + id,
                            success: function (data) {
                                table.draw();
                                Swal.fire({ icon: 'success', title: 'Dihapus!', text: data.success, timer: 1500, showConfirmButton: false });
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
