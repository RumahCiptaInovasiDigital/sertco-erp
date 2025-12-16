@extends('layouts.master')
@section('title', 'Master Jenis Kerja')
@section('PageTitle', 'Master Jenis Kerja')

@section('head')
    <!-- Additional head content if needed -->
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Master Jenis Kerja</li>
    </ol>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Jenis Kerja</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary" id="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Data
            </button>
            <button type="button" class="btn btn-secondary" id="reload-datatable-btn">
                <i class="fas fa-sync-alt"></i> Reload
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="tabelJenisKerja" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">No</th>
                    <th>Nama Jenis</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th style="width: 100px" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalJenisKerja" tabindex="-1" role="dialog" aria-labelledby="modalJenisKerjaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJenisKerjaLabel">Form Jenis Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formJenisKerja">
                <div class="modal-body">
                    <input type="hidden" id="jenis_kerja_id" name="id">
                    <div class="form-group">
                        <label for="nama_jenis_kerja">Nama Jenis Kerja</label>
                        <input type="text" class="form-control" id="nama_jenis_kerja" name="nama_jenis_kerja" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="nonactive">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // 1. SETUP & INISIALISASI
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        var tabelJenisKerja = $("#tabelJenisKerja").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('master/jenis-kerja-get') }}", // Adjusted URL
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_jenis_kerja', name: 'nama_jenis_kerja' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'status', name: 'status',
                    render: function(data, type, row) {
                        let badgeClass = data === 'active' ? 'badge-success' : 'badge-danger';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            responsive: true,
            autoWidth: false,
        });

        $('#reload-datatable-btn').on('click', function() {
            tabelJenisKerja.ajax.reload();
        });

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        // 2. LOGIKA MODAL (TAMBAH & EDIT)
        $('#btn-tambah').on('click', function() {
            $('#formJenisKerja')[0].reset();
            $('#jenis_kerja_id').val('');
            clearValidationErrors();
            $('#modalJenisKerjaLabel').text('Form Tambah Jenis Kerja');
            $('#submitBtn').text('Simpan Data');
            $('#modalJenisKerja').modal('show');
        });

        $('#tabelJenisKerja tbody').on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            clearValidationErrors();
            $('#modalJenisKerjaLabel').text('Form Edit Jenis Kerja');
            $('#submitBtn').text('Update Data');

            $.ajax({
                url: '{{ url("master/jenis-kerja-edit") }}/' + id,
                type: 'GET',
                success: function(response) {
                    $('#jenis_kerja_id').val(response.id);
                    $('#nama_jenis_kerja').val(response.nama_jenis_kerja);
                    $('#keterangan').val(response.keterangan);
                    $('#status').val(response.status);
                    $('#modalJenisKerja').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal mengambil data. Silakan coba lagi.' });
                }
            });
        });

        // 3. LOGIKA SUBMIT FORM (CREATE & UPDATE)
        $('#formJenisKerja').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var id = $('#jenis_kerja_id').val();
            var url = id ? `{{ url("master/jenis-kerja-update") }}/${id}` : "{{ url('master/jenis-kerja-store') }}";
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                beforeSend: function () {
                    $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                    clearValidationErrors();
                },
                success: function (response) {
                    $('#modalJenisKerja').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success, timer: 1500, showConfirmButton: false });
                    tabelJenisKerja.ajax.reload(null, false);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
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
        $('#tabelJenisKerja tbody').on('click', '.delete-btn', function () {
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
                        url: `{{ url("master/jenis-kerja-delete") }}/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Dihapus!', text: response.success, timer: 1500, showConfirmButton: false });
                            tabelJenisKerja.ajax.reload(null, false);
                        },
                        error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' }); }
                    });
                }
            });
        });
    });
</script>
@endsection
