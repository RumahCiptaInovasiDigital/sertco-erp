@extends('layouts.master')
@section('title', $title)
@section('PageTitle', 'Jadwal Kerja')

@section('head')
    <style>
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }



        /* Shift Cards */
        .shift-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .shift-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .shift-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .shift-card-header {
            margin-bottom: 0.5rem;
        }

        .shift-name {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .shift-time {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .shift-count {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-top: 1rem;
        }

        /* Fix tinggi Select2 agar sesuai dengan Bootstrap input */
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important; /* Standar Bootstrap 4 */
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
        }

        /* Mengatur posisi teks di dalam agar vertikal tengah */
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
            color: #495057;
        }

        /* Mengatur posisi panah dropdown */
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px) !important;
            top: 0 !important;
            right: 5px !important;
        }

        /* Efek fokus (opsional agar border jadi biru saat diklik) */
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }


    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">@yield('PageTitle')</li>
    </ol>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Jadwal Kerja</h1>
            <p>Master jadwal - assign shift ke karyawan</p>
        </div>

    </div>

    <!-- Shift Cards -->
    <div class="shift-cards-container">
        @foreach($shifts as $shift)
            <div class="shift-card" data-shift-id="{{ $shift->id }}">
                <div class="shift-card-header">
                    <div class="shift-name">{{ $shift->nama_shift }}</div>
                    <div class="shift-time">{{ \Carbon\Carbon::parse($shift->jam_masuk_min)->format('H:i') }}-{{ \Carbon\Carbon::parse($shift->jam_pulang_max)->format('H:i') }}</div>
                </div>
                <div class="shift-count">
                    <span id="count-{{ $shift->id }}">{{ $shift->karyawan_count ?? 0 }}</span> Karyawan
                </div>
            </div>
        @endforeach
    </div>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('PageTitle')</h3>
            <div class="card-tools">
                <button class="btn btn-success" data-toggle="modal" data-target="#assignShiftModal">
                    <i class="fas fa-plus"></i>
                    Assign Shift
                </button>
                <button id="sync-btn" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Sinkronkan Jadwal
                </button>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="jadwal-kerja-table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Karyawan</th>
                    <th>Departemen</th>
                    <th>Shift</th>
                    <th>Jam Kerja</th>
                    <th>Tipe</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <!-- Data populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assign Shift Modal -->
    <div class="modal fade" id="assignShiftModal" tabindex="-1" role="dialog" aria-labelledby="assignShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignShiftModalLabel">Assign Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignShiftForm">
                    @csrf
                    <input type="hidden" name="id" id="jadwal_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_karyawan">Karyawan</label>
                            <select class="form-control select2" id="id_karyawan" name="id_karyawan" required style="width: 100%;"></select>
                        </div>
                        <div class="form-group">
                            <label for="id_shift_kerja">Shift</label>
                            <select class="form-control select2" id="id_shift_kerja" name="id_shift_kerja" required>
                                 @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->nama_shift }} - {{ $shift->tipe }} | {{ $shift->jam_masuk_max }} - {{ $shift->jam_pulang_min }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <script>
        $(function () {
            // Initialize DataTable
            var table = $("#jadwal-kerja-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.jadwal-kerja.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'karyawan', name: 'karyawan' },
                    { data: 'jabatan', name: 'jabatan' },
                    { data: 'shift', name: 'shift' },
                    { data: 'jam_kerja', name: 'jam_kerja' },
                    { data: 'tipe', name: 'tipe' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
            });

             // Initialize Select2 for employees
             $('#id_karyawan').select2({
                 theme: 'bootstrap4',
                dropdownParent: $('#assignShiftModal'),
                placeholder: 'Cari NIK atau Nama Karyawan',
                ajax: {
                    url: "{{ route('master.karyawan.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Open modal for creating
            $('.card-tools .btn-success').on('click', function() {
                $('#assignShiftForm').trigger("reset");
                $('#jadwal_id').val('');
                $('#id_karyawan').val(null).trigger('change');
                $('#id_karyawan').prop('disabled', false);
                $('#assignShiftModalLabel').html("Assign Shift Baru");
                $('#saveBtn').html('Simpan');
                // No need to show modal via JS, data-target does it.
            });

            // Edit button handler
            $('#jadwal-kerja-table').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $.get("{{ url('jadwal/jadwal-kerja') }}/" + id + "/edit", function (data) {
                    $('#assignShiftModalLabel').html("Edit Jadwal Kerja");
                    $('#saveBtn').html('Update');
                    $('#assignShiftModal').modal('show');
                    $('#jadwal_id').val(data.id);
                    $('#id_shift_kerja').val(data.id_shift_kerja);

                    // For Select2, create a new option and select it
                    if (data.karyawan) {
                        var karyawanOption = new Option(data.karyawan.fullName + ' (' + data.karyawan.nik + ')', data.id_karyawan, true, true);
                        $('#id_karyawan').append(karyawanOption).trigger('change').prop('disabled', true);
                    }
                })
            });

            // Re-enable select on modal close
            $('#assignShiftModal').on('hidden.bs.modal', function () {
                $('#id_karyawan').prop('disabled', false);
            });


            // Form submission
            $('#assignShiftForm').on('submit', function(e) {
                e.preventDefault();
                var btn = $('#saveBtn');
                btn.html('Menyimpan...');
                btn.prop('disabled', true);


                var id = $('#jadwal_id').val();
                var url = id ? "{{ url('jadwal/jadwal-kerja-update') }}/" + id : "{{ route('master.jadwal-kerja.store') }}";
                var formData = $(this).serialize();
                // As the id_karyawan is disabled on edit, it's not included in serialize().
                // We add it manually for the update request if needed by backend, though our backend logic for update doesn't use it.
                if(id) {
                    var disabledKaryawanId = $('#id_karyawan').val();
                    formData += "&id_karyawan=" + disabledKaryawanId;
                }

                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST", // Using POST for both, Laravel handles PUT/PATCH via _method field if needed. Our simple case does not. Let's make it PUT for update.
                    type: id ? 'PUT' : 'POST',
                    dataType: 'json',
                    success: function (data) {
                        btn.html(id ? 'Update' : 'Simpan');
                        btn.prop('disabled', false);
                        $('#assignShiftForm').trigger("reset");
                        $('#assignShiftModal').modal('hide');
                        table.ajax.reload();
                        updateShiftCounts();
                        Swal.fire('Sukses!', data.success, 'success');
                    },
                    error: function (data) {
                        btn.html(id ? 'Update' : 'Simpan');
                        btn.prop('disabled', false);
                        var errors = data.responseJSON.errors;
                        var errorMessages = '';
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMessages += value[0] + '<br>';
                            });
                        } else {
                            errorMessages = 'Terjadi kesalahan, silakan coba lagi.';
                        }
                        Swal.fire('Error!', errorMessages, 'error');
                    }
                });
            });


            $('#sync-btn').on('click', function() {
                Swal.fire({
                    title: 'Sinkronkan Jadwal?',
                    text: "Ini akan membuat atau memperbarui jadwal untuk semua karyawan berdasarkan shift yang berlaku.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Sinkronkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('master.jadwal-kerja.sync') }}",
                            type: 'POST',
                            data: { _token: "{{ csrf_token() }}" },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Mohon Tunggu...',
                                    html: 'Sedang menyinkronkan data.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.success,
                                });
                                table.ajax.reload();
                                updateShiftCounts();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat sinkronisasi.'
                                });
                            }
                        });
                    }
                });
            });

            // Delete handler
            $('#jadwal-kerja-table').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data jadwal ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('master/jadwal-kerja/delete') }}/" + id,
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                Swal.fire('Dihapus!', response.success, 'success');
                                table.ajax.reload();
                                updateShiftCounts();
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });

            // Update shift counts
            function updateShiftCounts() {
                $.ajax({
                    url: "{{ route('master.jadwal-kerja.shift-counts') }}",
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(shiftId, count) {
                            $('#count-' + shiftId).text(count);
                        });
                    }
                });
            }

            updateShiftCounts();
        });
    </script>
@endsection
