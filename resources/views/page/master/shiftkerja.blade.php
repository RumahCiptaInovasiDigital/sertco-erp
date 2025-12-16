@extends('layouts.master')
@section('title', 'Master Shift Kerja')
@section('PageTitle', 'Master Shift Kerja')

@section('head')
    <!-- Additional head content if needed -->
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Master Shift Kerja</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Shift Kerja</h3>
                    <div class="card-tools">
                        <button id="btn-tambah" type="button" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Shift
                        </button>
                        <button id="reload-datatable-btn" class="btn btn-secondary" title="Muat Ulang Tabel">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabelShiftKerja" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th style="width: 10px">No.</th>
                            <th>Nama Shift</th>
                            <th>Jam Kerja</th>
                            <th>Durasi (Jam)</th>
                            <th>Tipe</th>
                            <th>Berlaku Untuk</th>
                            <th>Status</th>
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

    <!-- Modal Tambah/Edit Shift Kerja -->
    <div class="modal fade" id="modalShiftKerja" tabindex="-1" role="dialog" aria-labelledby="modalShiftKerjaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalShiftKerjaLabel">Form Shift Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formShiftKerja">
                    <div class="modal-body">
                        <input type="hidden" id="shift_id" name="id">
                        <div class="form-group">
                            <label for="nama_shift">Nama Shift</label>
                            <input type="text" class="form-control" id="nama_shift" name="nama_shift" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_masuk_min">Jam Masuk (Minimal)</label>
                                    <input type="time" class="form-control" id="jam_masuk_min" name="jam_masuk_min" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_masuk_max">Jam Masuk (Maksimal)</label>
                                    <input type="time" class="form-control" id="jam_masuk_max" name="jam_masuk_max" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_pulang_min">Jam Pulang (Minimal)</label>
                                    <input type="time" class="form-control" id="jam_pulang_min" name="jam_pulang_min" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_pulang_max">Jam Pulang (Maksimal)</label>
                                    <input type="time" class="form-control" id="jam_pulang_max" name="jam_pulang_max" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Tipe Kerja</label>
                            <select class="form-control select2" id="tipe" name="tipe" required>
                                <option value="">-- Pilih Tipe Kerja --</option>
                               <option value="WFO">Work From Office (WFO)</option>
                               <option value="WFA">Work From Anywhere (WFA) </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Berlaku Khusus Untuk</label>
                            <div class="row">
                                @foreach ($roles as $role)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="berlaku_untuk[]" value="{{ $role->id_role }}" id="role_{{ $role->id_role }}">
                                            <label class="form-check-label" for="role_{{ $role->id_role }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                             <div id="berlaku_untuk_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
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
            // 1. SETUP & INISIALISASI
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#modalShiftKerja')
            });

            var tabelShiftKerja = $("#tabelShiftKerja").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.shift-kerja.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_shift', name: 'nama_shift' },
                    { data: 'jam_kerja', name: 'jam_kerja', orderable: false, searchable: false },
                    { data: 'durasi', name: 'durasi', orderable: false, searchable: false },
                    { data: 'tipe', name: 'tipe', orderable: false, searchable: false },
                    { data: 'berlaku_untuk_badge', name: 'berlaku_untuk_badge', orderable: false, searchable: false },
                    { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive: true,
                autoWidth: false,
            });

            $('#reload-datatable-btn').on('click', function() {
                tabelShiftKerja.ajax.reload();
            });

            function clearValidationErrors() {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#berlaku_untuk_error').html('');
            }

            // 2. LOGIKA MODAL (TAMBAH & EDIT)
            $('#btn-tambah').on('click', function() {
                $('#formShiftKerja')[0].reset();
                $('#shift_id').val('');
                $('#tipe').val('').trigger('change'); // Reset Select2
                clearValidationErrors();
                $('#modalShiftKerjaLabel').text('Form Tambah Shift Kerja');
                $('#submitBtn').text('Simpan Data');
                $('#modalShiftKerja').modal('show');
            });

            $('#tabelShiftKerja tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                clearValidationErrors();
                $('#modalShiftKerjaLabel').text('Form Edit Shift Kerja');
                $('#submitBtn').text('Update Data');

                $.ajax({
                    url: '{{ url("master/shift-kerja/edit") }}/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#shift_id').val(response.id);
                        $('#nama_shift').val(response.nama_shift);
                        $('#jam_masuk_min').val(response.jam_masuk_min);
                        $('#jam_masuk_max').val(response.jam_masuk_max);
                        $('#jam_pulang_min').val(response.jam_pulang_min);
                        $('#jam_pulang_max').val(response.jam_pulang_max);
                        $('#tipe').val(response.tipe).trigger('change'); // Set Select2 value
                        $('#status').val(response.status);

                        // Uncheck all checkboxes first
                        $('input[name="berlaku_untuk[]"]').prop('checked', false);
                        // Check the relevant ones
                        if(response.berlaku_untuk && Array.isArray(response.berlaku_untuk)) {
                            response.berlaku_untuk.forEach(function(roleId) {
                                $('#role_' + roleId).prop('checked', true);
                            });
                        }

                        $('#modalShiftKerja').modal('show');
                    }
                });
            });

            // 3. LOGIKA SUBMIT FORM (CREATE & UPDATE)
            $('#formShiftKerja').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var id = $('#shift_id').val();
                var url = id ? '{{ url("master/shift-kerja/update") }}/' + id : "{{ route('master.shift-kerja.store') }}";
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
                        $('#modalShiftKerja').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.success, timer: 1500, showConfirmButton: false });
                        tabelShiftKerja.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        if(errors){
                            $.each(errors, function (key, value) {
                                if(key === 'berlaku_untuk') {
                                     $('#berlaku_untuk_error').html('<div class="text-danger">' + value[0] + '</div>');
                                } else {
                                     $('#' + key).addClass('is-invalid').after('<div class="invalid-feedback">' + value[0] + '</div>');
                                }
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan.' });
                        }
                    },
                    complete: function() { $('#submitBtn').prop('disabled', false).text(id ? 'Update Data' : 'Simpan Data'); }
                });
            });

            // 4. LOGIKA HAPUS DATA
            $('#tabelShiftKerja tbody').on('click', '.delete-btn', function () {
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
                            url: '{{ url("master/shift-kerja/delete") }}/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({ icon: 'success', title: 'Dihapus!', text: response.success, timer: 1500, showConfirmButton: false });
                                tabelShiftKerja.ajax.reload(null, false);
                            },
                            error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' }); }
                        });
                    }
                });
            });
        });
    </script>
@endsection
