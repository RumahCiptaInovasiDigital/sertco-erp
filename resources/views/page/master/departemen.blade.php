@extends('layouts.master')
@section('title', 'Dashboard')
@section('PageTitle', 'Project Sheet Execution Dashboard')

@section('head')
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <button id="btn-tambah" type="button" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Departemen
                        </button>
                        <button id="reload-datatable-btn" class="btn btn-secondary" title="Muat Ulang Tabel">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabelDepartemen" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th style="width: 10px">No.</th>
                            <th>Nama Departemen</th>

                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDepartemen" tabindex="-1" role="dialog" aria-labelledby="modalDepartemenLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDepartemenLabel">Form Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formDepartemen">
                    <div class="modal-body">
                        <input type="hidden" id="departemen_id" name="id_departemen">
                        <div class="form-group">
                            <label for="name">Nama Departemen</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Departemen" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Simpan</button>
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
        $(document).ready(function () {
            // 1. SETUP & INISIALISASI
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            var tabelDepartemen = $("#tabelDepartemen").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('presensi.master.departemen.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },

                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive: true,
                autoWidth: false,
            });

            $('#reload-datatable-btn').on('click', function() {
                tabelDepartemen.ajax.reload();
            });

            // 2. LOGIKA MODAL (TAMBAH & EDIT)
            $('#btn-tambah').on('click', function() {
                $('#formDepartemen')[0].reset();
                $('#departemen_id').val('');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#modalDepartemenLabel').text('Form Tambah Departemen');
                $('#submitBtn').text('Simpan Data');
                $('#modalDepartemen').modal('show');
            });

            $('#tabelDepartemen tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#modalDepartemenLabel').text('Form Edit Departemen');
                $('#submitBtn').text('Update Data');

                $.ajax({
                    url: '{{ url("presensi/master/departemen-edit") }}/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#departemen_id').val(response.id_departemen);
                        $('#name').val(response.name);

                        $('#modalDepartemen').modal('show');
                    }
                });
            });

            // 3. LOGIKA SUBMIT FORM (CREATE & UPDATE)
            $('#formDepartemen').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var id = $('#departemen_id').val();
                var url = id ? '{{ url("presensi/master/departemen-update") }}/' + id : "{{ route('presensi.master.departemen.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    beforeSend: function () { $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...'); },
                    success: function (response) {
                        $('#modalDepartemen').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success, timer: 1500, showConfirmButton: false });
                        tabelDepartemen.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        var errors = xhr.responseJSON.errors;
                        if(errors){
                            $.each(errors, function (key, value) {
                                $('#' + key).addClass('is-invalid').after('<div class="invalid-feedback">' + value[0] + '</div>');
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan.' });
                        }
                    },
                    complete: function() { $('#submitBtn').prop('disabled', false).text(id ? 'Update Data' : 'Simpan Data'); }
                });
            });

            // 4. LOGIKA HAPUS DATA
            $('#tabelDepartemen tbody').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("presensi/master/departemen-delete") }}/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({ icon: 'success', title: 'Dihapus!', text: response.success, timer: 1500, showConfirmButton: false });
                                tabelDepartemen.ajax.reload(null, false);
                            },
                            error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' }); }
                        });
                    }
                });
            });
        });
    </script>

@endsection
