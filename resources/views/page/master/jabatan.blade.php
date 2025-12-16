@extends('layouts.master')
@section('title', 'Dashboard')
@section('PageTitle', 'Project Sheet Execution Dashboard')

@section('head')
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
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
                            <i class="fas fa-plus"></i> Tambah Jabatan
                        </button>
                        <button id="reload-datatable-btn" class="btn btn-secondary" title="Muat Ulang Tabel">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabelJabatan" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th style="width: 10px">No.</th>
                            <th>Nama Jabatan</th>
                            <th>Level</th>
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


    <!-- Modal Tambah/Edit Jabatan -->
    <div class="modal fade" id="modalJabatan" tabindex="-1" role="dialog" aria-labelledby="modalJabatanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJabatanLabel">Form Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formJabatan">
                    <div class="modal-body">
                        <input type="hidden" id="jabatan_id" name="id">
                        <div class="form-group">
                            <label for="nama_jabatan">Nama Jabatan</label>
                            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                        </div>
                        <div class="form-group">
                            <label for="level">Level</label>
                            <select class="form-control" id="level" name="level" style="width: 100%;" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="1">Level 1</option>
                                <option value="2">Level 2</option>
                                <option value="3">Level 3</option>
                                <option value="4">Level 4</option>
                                <option value="5">Level 5</option>
                                <option value="6">Level 6</option>
                                <option value="7">Level 7</option>
                                <option value="8">Level 8</option>

                            </select>
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
    <script>
        $(document).ready(function () {

            // Membuat dropdown parent

            // 1. SETUP & INISIALISASI
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            var tabelJabatan = $("#tabelJabatan").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.jabatan.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_jabatan', name: 'nama_jabatan' },
                    { data: 'level_badge', name: 'level_badge' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive: true,
                autoWidth: false,
            });

            $('#reload-datatable-btn').on('click', function() {
                tabelJabatan.ajax.reload();
            });

            // 2. LOGIKA MODAL (TAMBAH & EDIT)
            $('#btn-tambah').on('click', function() {
                $('#formJabatan')[0].reset();
                $('#jabatan_id').val('');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#modalJabatanLabel').text('Form Tambah Jabatan');
                $('#submitBtn').text('Simpan Data');
                $('#modalJabatan').modal('show');
            });

            $('#tabelJabatan tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#modalJabatanLabel').text('Form Edit Jabatan');
                $('#submitBtn').text('Update Data');

                $.ajax({
                    url: '{{ url("master/jabatan-edit") }}/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#jabatan_id').val(response.id);
                        $('#nama_jabatan').val(response.nama_jabatan);
                        $('#level').val(response.level).trigger('change');
                        $('#modalJabatan').modal('show');
                    }
                });
            });

            // 3. LOGIKA SUBMIT FORM (CREATE & UPDATE)
            $('#formJabatan').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var id = $('#jabatan_id').val();
                var url = id ? '{{ url("master/jabatan-update") }}/' + id : "{{ route('master.jabatan.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    beforeSend: function () { $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...'); },
                    success: function (response) {
                        $('#modalJabatan').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success, timer: 1500, showConfirmButton: false });
                        tabelJabatan.ajax.reload(null, false);
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
            $('#tabelJabatan tbody').on('click', '.delete-btn', function () {
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
                            url: '{{ url("master/jabatan-delete") }}/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({ icon: 'success', title: 'Dihapus!', text: response.success, timer: 1500, showConfirmButton: false });
                                tabelJabatan.ajax.reload(null, false);
                            },
                            error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' }); }
                        });
                    }
                });
            });

            $('#level').select2({
                dropdownParent: $('#modalJabatan .modal-content')
            });
        });
    </script>


@endsection
