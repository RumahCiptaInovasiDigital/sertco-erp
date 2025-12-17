@extends('layouts.master')
@section('title', 'Presensi Manual')
@section('PageTitle', 'Presensi Manual')
@section('head')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Filter Periode</label>
                                        <select class="form-control select2" id="filterPeriode">
                                            @for($i = 0; $i < 12; $i++)
                                                @php
                                                    $date = now()->subMonths($i);
                                                    $value = $date->format('Y-m');
                                                    $text = $date->translatedFormat('F Y');
                                                @endphp
                                                <option value="{{ $value }}" {{ $i == 0 ? 'selected' : '' }}>
                                                    {{ $text }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-7 col-lg-8 text-md-right mt-3 mt-md-0">
                                    <button type="button" class="btn btn-success" id="btnTambah">
                                        <i class="fas fa-plus mr-1"></i> Tambah Presensi Manual
                                    </button>
                                    <button type="button" class="btn btn-info ml-2" id="btnRefresh">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover w-100" id="resumeTable">
                                    <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Lokasi</th>
                                        <th>Waktu Input</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form Tambah/Edit -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="modalFormTitle">Tambah Presensi Manual</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formPresensi">
                    <input type="hidden" id="presensi_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Karyawan <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="karyawan_id" name="karyawan_id" required style="width: 100%">
                                <option value="">Pilih Karyawan</option>
                            </select>
                            <small class="form-text text-muted karyawan-info" style="display:none;">
                                <i class="fas fa-info-circle"></i> Data karyawan tidak dapat diubah saat edit
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>

                        <!-- Info Shift Kerja -->
                        <div id="shiftInfo" class="alert alert-info" style="display:none;">
                            <h6 class="mb-2"><i class="fas fa-clock mr-1"></i> Informasi Jadwal Kerja</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Shift:</strong> <span id="info_shift">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Jam Masuk:</strong> <span id="info_jam_masuk">-</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Jam Pulang:</strong> <span id="info_jam_pulang">-</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>Tipe:</strong> <span id="info_tipe">-</span>
                                </div>
                                <div class="col-md-8">
                                    <strong>Lokasi:</strong> <span id="info_lokasi">-</span>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-lightbulb"></i> Jam masuk dan pulang akan diisi otomatis berdasarkan jadwal
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Masuk <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Pulang <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lokasi <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="lokasi" name="lokasi" required style="width: 100%">
                                <option value="">Pilih Lokasi</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Alasan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Masukkan alasan presensi manual" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Catatan Approver</label>
                            <textarea class="form-control" id="catatan_approver" name="catatan_approver" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Detail Presensi Manual</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nama Karyawan</th>
                            <td id="det_nama">-</td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td id="det_nik">-</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td id="det_tanggal">-</td>
                        </tr>
                        <tr>
                            <th>Jam Masuk</th>
                            <td id="det_jam_masuk">-</td>
                        </tr>
                        <tr>
                            <th>Jam Pulang</th>
                            <td id="det_jam_pulang">-</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td id="det_lokasi">-</td>
                        </tr>
                        <tr>
                            <th>Alasan</th>
                            <td id="det_alasan">-</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="det_status">-</td>
                        </tr>
                        <tr>
                            <th>Diinput Oleh</th>
                            <td id="det_approved_by">-</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td id="det_catatan">-</td>
                        </tr>
                        <tr>
                            <th>Waktu Input</th>
                            <td id="det_created_at">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .select2-container--disabled {
            opacity: 0.65;
        }

        .select2-container--disabled .select2-selection {
            background-color: #e9ecef !important;
            cursor: not-allowed !important;
        }

        .karyawan-info {
            color: #6c757d;
            font-style: italic;
            margin-top: 5px;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2 untuk filter periode
            $('#filterPeriode').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: Infinity
            });

            // Load data karyawan untuk select2
            function loadKaryawan(callback) {
                $.ajax({
                    url: '{{ route("presensi.master.karyawan.select2") }}',
                    type: 'GET',
                    success: function(data) {
                        if ($('#karyawan_id').hasClass("select2-hidden-accessible")) {
                            $('#karyawan_id').select2('destroy');
                        }

                        let options = '<option value="">Pilih Karyawan</option>';
                        data.forEach(function(item) {
                            options += `<option value="${item.id}">${item.text}</option>`;
                        });

                        $('#karyawan_id').html(options);

                        $('#karyawan_id').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#modalForm'),
                            width: '100%',
                            placeholder: 'Pilih Karyawan',
                            allowClear: true
                        });

                        if (typeof callback === 'function') {
                            callback();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading karyawan:', xhr);
                        Swal.fire('Error', 'Gagal memuat data karyawan', 'error');
                    }
                });
            }

            // Load data kantor/lokasi untuk select2
            function loadLokasi(callback) {
                $.ajax({
                    url: '{{ url("presensi/master/kantor-get") }}',
                    type: 'GET',
                    success: function(response) {
                        if ($('#lokasi').hasClass("select2-hidden-accessible")) {
                            $('#lokasi').select2('destroy');
                        }

                        let options = '<option value="">Pilih Lokasi</option>';
                        if (response.data && Array.isArray(response.data)) {
                            response.data.forEach(function(item) {
                                options += `<option value="${item.name}">${item.name} - ${item.city}</option>`;
                            });
                        }

                        $('#lokasi').html(options);

                        $('#lokasi').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#modalForm'),
                            width: '100%',
                            placeholder: 'Pilih Lokasi',
                            allowClear: true,
                            tags: true
                        });

                        if (typeof callback === 'function') {
                            callback();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading lokasi:', xhr);
                        Swal.fire('Error', 'Gagal memuat data lokasi', 'error');
                    }
                });
            }

            // Load jadwal kerja karyawan
            function loadJadwalKerja() {
                const karyawanId = $('#karyawan_id').val();
                const tanggal = $('#tanggal').val();

                if (!karyawanId || !tanggal) {
                    $('#shiftInfo').slideUp();
                    return;
                }

                $.ajax({
                    url: '{{ url("presensi/presensi/manual/jadwal") }}',
                    type: 'GET',
                    data: {
                        karyawan_id: karyawanId,
                        tanggal: tanggal
                    },
                    success: function(res) {
                        if (res.success && res.data) {
                            const data = res.data;

                            // Update info shift
                            $('#info_shift').html(`<span class="badge badge-primary">${data.shift_nama || '-'}</span>`);
                            $('#info_jam_masuk').html(`<strong class="text-success">${data.jam_masuk_max || '-'}</strong>`);
                            $('#info_jam_pulang').html(`<strong class="text-danger">${data.jam_pulang_min || '-'}</strong>`);
                            $('#info_tipe').html(`<span class="badge badge-${data.tipe === 'WFO' ? 'success' : 'danger'}">${data.tipe || '-'}</span>`);

                            // Update info lokasi
                            if (data.lokasi && data.lokasi.full_name) {
                                $('#info_lokasi').text(data.lokasi.full_name);
                            } else {
                                $('#info_lokasi').text('-');
                            }

                            // Auto-fill jam masuk dan pulang (format sudah HH:mm dari server)
                            if (data.jam_masuk_max) {
                                $('#jam_masuk').val(data.jam_masuk_max);
                            }
                            if (data.jam_pulang_min) {
                                $('#jam_pulang').val(data.jam_pulang_min);
                            }

                            // Auto-select lokasi berdasarkan name
                            if (data.lokasi && data.lokasi.name) {
                                const lokasiName = data.lokasi.name;

                                // Cek apakah option dengan value name sudah ada
                                if ($('#lokasi option[value="' + lokasiName + '"]').length > 0) {
                                    $('#lokasi').val(lokasiName).trigger('change');
                                } else {
                                    // Buat option baru dengan full_name sebagai text
                                    const newOption = new Option(data.lokasi.full_name, lokasiName, true, true);
                                    $('#lokasi').append(newOption).trigger('change');
                                }
                            }

                            // Tampilkan info
                            $('#shiftInfo').slideDown();
                        } else {
                            $('#shiftInfo').slideUp();
                            // Reset jam
                            $('#jam_masuk').val('');
                            $('#jam_pulang').val('');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading jadwal:', xhr);
                        $('#shiftInfo').slideUp();
                    }
                });
            }

            // Event listener untuk karyawan dan tanggal
            $('#karyawan_id').on('change', function() {
                loadJadwalKerja();
            });

            $('#tanggal').on('change', function() {
                loadJadwalKerja();
            });

            // Inisialisasi DataTable
            const table = $('#resumeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url("presensi/presensi/manual/data") }}',
                    type: 'GET',
                    data: function (d) {
                        d.periode = $('#filterPeriode').val();
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        toastr.error('Gagal memuat data presensi manual');
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nama', name: 'nama'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'jam_masuk', name: 'jam_masuk'},
                    {data: 'jam_pulang', name: 'jam_pulang'},
                    {data: 'lokasi', name: 'lokasi'},
                    {data: 'waktu_input', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[2, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    processing: "Memuat data...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    loadingRecords: "Memuat...",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data",
                    paginate: {
                        first: "Pertama",
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                        last: "Terakhir"
                    }
                }
            });

            // Filter Periode
            $('#filterPeriode').on('change', function () {
                table.ajax.reload();
            });

            // Tombol Refresh
            $('#btnRefresh').on('click', function () {
                table.ajax.reload(null, false);
                const icon = $(this).find('i');
                icon.addClass('fa-spin');
                setTimeout(function () {
                    icon.removeClass('fa-spin');
                }, 1000);
            });

            // Tombol Tambah
            $('#btnTambah').on('click', function() {
                $('#modalFormTitle').text('Tambah Presensi Manual');
                $('#formPresensi')[0].reset();
                $('#presensi_id').val('');

                loadKaryawan(function() {
                    // Enable select karyawan saat tambah
                    $('#karyawan_id').prop('disabled', false);
                    $('#karyawan_id').next('.select2-container').removeClass('select2-container--disabled');
                    $('.karyawan-info').hide();
                });

                loadLokasi();

                $('#modalForm').modal('show');
            });

            // Submit Form
            $('#formPresensi').on('submit', function(e) {
                e.preventDefault();

                const id = $('#presensi_id').val();
                const url = id ? '{{ url("presensi/presensi/manual/update") }}/' + id : '{{ url("presensi/presensi/manual/store") }}';
                const method = id ? 'PUT' : 'POST';

                const jamMasuk = $('#jam_masuk').val().substring(0, 5);
                const jamPulang = $('#jam_pulang').val().substring(0, 5);

                // Ambil value karyawan_id meskipun disabled
                const karyawanId = $('#karyawan_id').prop('disabled')
                    ? $('#karyawan_id').find(':selected').val()
                    : $('#karyawan_id').val();

                let formData = {
                    _token: '{{ csrf_token() }}',
                    karyawan_id: karyawanId,
                    tanggal: $('#tanggal').val(),
                    jam_masuk: jamMasuk,
                    jam_pulang: jamPulang,
                    lokasi: $('#lokasi').val(),
                    alasan: $('#alasan').val(),
                    catatan_approver: $('#catatan_approver').val()
                };

                $('#btnSubmit').html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(res) {
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if(errors) {
                            let errorMsg = '';
                            Object.keys(errors).forEach(key => {
                                errorMsg += '• ' + errors[key][0] + '\n';
                            });
                            Swal.fire('Validasi Gagal', errorMsg, 'error');
                        } else {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
                        }
                    },
                    complete: function() {
                        $('#btnSubmit').html('<i class="fas fa-save mr-1"></i> Simpan').prop('disabled', false);
                    }
                });
            });

            // Tombol Detail
            $(document).on('click', '.detail-btn', function() {
                const id = $(this).data('id');
                const url = '{{ url("presensi/presensi/manual/detail") }}/' + id;

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memuat data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        Swal.close();
                        const data = res.data;
                        $('#det_nama').text(data.karyawan || '-');
                        $('#det_nik').text(data.nik || '-');
                        $('#det_tanggal').text(data.tanggal || '-');
                        $('#det_jam_masuk').text(data.jam_masuk || '-');
                        $('#det_jam_pulang').text(data.jam_pulang || '-');
                        $('#det_lokasi').text(data.lokasi || '-');
                        $('#det_alasan').text(data.alasan || '-');
                        $('#det_status').text(data.status || '-');
                        $('#det_approved_by').text(data.approved_by || '-');
                        $('#det_catatan').text(data.catatan_approver || '-');
                        $('#det_created_at').text(data.created_at || '-');
                        $('#modalDetail').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal mengambil data detail', 'error');
                    }
                });
            });

            // Tombol Edit
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const url = '{{ url("presensi/presensi/manual/detail") }}/' + id;

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Memuat data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        Swal.close();
                        const data = res.data;

                        $('#modalFormTitle').text('Edit Presensi Manual');
                        $('#presensi_id').val(id);

                        loadKaryawan(function() {
                            $('#karyawan_id').val(data.karyawan_id).trigger('change');

                            // Disable select karyawan saat edit
                            $('#karyawan_id').prop('disabled', true);
                            $('#karyawan_id').next('.select2-container').addClass('select2-container--disabled');

                            // Tampilkan info message
                            $('.karyawan-info').show();
                        });

                        loadLokasi(function() {
                            if ($('#lokasi option[value="' + data.lokasi + '"]').length > 0) {
                                $('#lokasi').val(data.lokasi).trigger('change');
                            } else {
                                const newOption = new Option(data.lokasi, data.lokasi, true, true);
                                $('#lokasi').append(newOption).trigger('change');
                            }
                        });

                        $('#tanggal').val(data.tanggal_raw);
                        $('#jam_masuk').val(data.jam_masuk_raw);
                        $('#jam_pulang').val(data.jam_pulang_raw);
                        $('#alasan').val(data.alasan);
                        $('#catatan_approver').val(data.catatan_approver);

                        $('#modalForm').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal mengambil data', 'error');
                    }
                });
            });

            // Tombol Hapus
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("presensi/presensi/manual/destroy") }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus data', 'error');
                            }
                        });
                    }
                });
            });

            // Reset modal saat ditutup
            $('#modalForm').on('hidden.bs.modal', function () {
                $('#formPresensi')[0].reset();
                $('#presensi_id').val('');

                if ($('#karyawan_id').hasClass("select2-hidden-accessible")) {
                    $('#karyawan_id').val('').trigger('change');
                    $('#karyawan_id').prop('disabled', false);
                    $('#karyawan_id').next('.select2-container').removeClass('select2-container--disabled');
                }

                $('.karyawan-info').hide();

                if ($('#lokasi').hasClass("select2-hidden-accessible")) {
                    $('#lokasi').val('').trigger('change');
                }

                // Reset shift info
                $('#shiftInfo').slideUp();
            });
        });
    </script>
@endsection
