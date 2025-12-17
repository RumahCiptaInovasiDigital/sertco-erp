@extends('layouts.master')
@section('title', $title)
@section('PageTitle', 'Jadwal Karyawan')
@section('head')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('head')
    <style>
        /* Day Card Styling */
        .day-card {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .day-card:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
            transform: translateY(-2px);
        }

        .day-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            padding: 12px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .day-card-body {
            padding: 15px;
        }

        .shift-info {
            margin-bottom: 8px;
        }

        .shift-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 4px;
        }

        .shift-detail {
            font-size: 0.85rem;
            color: #6b7280;
            display: block;
        }

        .edit-day-btn {
            padding: 4px 10px;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        .no-shift-text {
            color: #9ca3af;
            font-style: italic;
            font-size: 0.9rem;
        }

        /* Modal Shift Styling */
        #editShiftModal .modal-content {
            border-radius: 12px;
        }

        #editShiftModal .form-control {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }

        #editShiftModal .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        #editJadwalModal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Ensure Bootstrap modals appear above SweetAlert */
        #addJadwalModal {
            z-index: 10000 !important;
        }

        #addJadwalModal .modal-backdrop {
            z-index: 9999 !important;
        }

        #editShiftModal {
            z-index: 10001 !important;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        /* SweetAlert Select2 Styling */
        .swal-select-karyawan .select2-container {
            z-index: 10000 !important;
        }

        .swal-select-karyawan .select2-container--bootstrap4 .select2-selection {
            min-height: 42px !important;
            border: 2px solid #e3e6f0 !important;
            border-radius: 8px !important;
            padding: 0.375rem 0.75rem !important;
        }

        .swal-select-karyawan .select2-container--bootstrap4 .select2-selection:hover {
            border-color: #28a745 !important;
        }

        .swal-select-karyawan .select2-container--bootstrap4.select2-container--focus .select2-selection,
        .swal-select-karyawan .select2-container--bootstrap4.select2-container--open .select2-selection {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
        }

        .swal-select-karyawan .select2-container--bootstrap4 .select2-selection__rendered {
            line-height: 24px !important;
            padding-left: 0 !important;
            color: #5a5c69 !important;
        }

        .swal-select-karyawan .select2-container--bootstrap4 .select2-selection__placeholder {
            color: #858796 !important;
        }

        .swal-select-karyawan .select2-dropdown {
            border: 2px solid #28a745 !important;
            border-radius: 8px !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .swal-select-karyawan .select2-search--dropdown .select2-search__field {
            border: 2px solid #e3e6f0 !important;
            border-radius: 6px !important;
            padding: 0.5rem !important;
        }

        .swal-select-karyawan .select2-search--dropdown .select2-search__field:focus {
            border-color: #28a745 !important;
            outline: none !important;
        }

        .swal-select-karyawan .select2-results__option {
            padding: 0.75rem 1rem !important;
        }

        .swal-select-karyawan .select2-results__option--highlighted {
            background-color: #28a745 !important;
            color: white !important;
        }

        .swal2-popup.rounded-lg {
            border-radius: 12px !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">@yield('PageTitle')</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@yield('PageTitle')</h3>
            <div class="card-tools">

                <button id="tambah-btn" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                <button id="reload-btn" class="btn btn-info" title="Reload Data (Tekan F5)">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
                <button id="sync-btn" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Sinkronkan
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="jadwal-karyawan-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th style="width: 10px">No</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Senin</th>
                    <th>Selasa</th>
                    <th>Rabu</th>
                    <th>Kamis</th>
                    <th>Jumat</th>
                    <th>Sabtu</th>
                    <th class="text-center align-middle header-hari text-danger">Minggu</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Jadwal -->
    <div class="modal fade" id="editJadwalModal" tabindex="-1" role="dialog" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editJadwalModalLabel">
                        <i class="fas fa-calendar-alt"></i> Edit Jadwal Karyawan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong id="karyawan-info">Loading...</strong>
                    </div>

                    <input type="hidden" id="edit-jadwal-id">

                    <div class="row" id="jadwal-days-container">
                        <!-- Days will be dynamically loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Shift per Hari -->
    <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editShiftModalLabel">
                        <i class="fas fa-edit"></i> Edit Shift <span id="modal-day-name"></span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="current-day-index">

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-clock text-primary"></i> Pilih Shift
                        </label>
                        <select id="shift-select" class="form-control select2-single">
                            <option value="">Pilih Shift...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-map-marker-alt text-danger"></i> Pilih Lokasi
                        </label>
                        <select id="lokasi-select" class="form-control select2-single">
                            <option value="">Pilih Lokasi...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="saveShiftBtn">
                        <i class="fas fa-save"></i> Simpan
                    </button>
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
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            // Global reload function
            window.reloadJadwalTable = function() {
                if (typeof table !== 'undefined') {
                    table.ajax.reload(null, false); // false = stay on current page

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil dimuat ulang',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }
            };

            // Initialize DataTable
            const table = $('#jadwal-karyawan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('presensi.master.jadwal-kerja.get') }}',
                    type: 'GET',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_lengkap', name: 'nama_lengkap' },
                    { data: 'jabatan', name: 'jabatan' },
                    {
                        data: 'senin',
                        name: 'senin',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'selasa',
                        name: 'selasa',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'rabu',
                        name: 'rabu',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'kamis',
                        name: 'kamis',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'jumat',
                        name: 'jumat',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'sabtu',
                        name: 'sabtu',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'minggu',
                        name: 'minggu',
                        orderable: false,
                        render: function(data) {
                            if (!data || data === '-' || typeof data !== 'object') {
                                return '<span class="text-muted">-</span>';
                            }
                            return `
                                <div style="font-size: 0.875rem;">
                                    <strong>${data.shift || '-'}</strong><br>
                                    <small class="text-muted">
                                        ${data.jam_masuk || '-'} - ${data.jam_pulang || '-'}<br>
                                        <span class="badge badge-${data.type === 'WFO' ? 'success' : 'info'} badge-sm">${data.type || '-'}</span> | ${data.lokasi || '-'}
                                    </small>
                                </div>
                            `;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[1, 'asc']],
                responsive: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    zeroRecords: 'Data tidak ditemukan',
                    info: 'Menampilkan halaman _PAGE_ dari _PAGES_',
                    infoEmpty: 'Tidak ada data yang tersedia',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: 'Cari:',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            // Reload Button
            $('#reload-btn').on('click', function() {
                const btn = $(this);
                const icon = btn.find('i');

                // Add spinning animation
                icon.addClass('fa-spin');
                btn.prop('disabled', true);

                table.ajax.reload(function() {
                    // Remove spinning animation after reload
                    icon.removeClass('fa-spin');
                    btn.prop('disabled', false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil dimuat ulang',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                }, false);
            });

            // Keyboard shortcut F5 for reload
            $(document).on('keydown', function(e) {
                if (e.key === 'F5' || e.keyCode === 116) {
                    e.preventDefault();
                    $('#reload-btn').click();
                }
            });

            // Sync Button
            $('#sync-btn').on('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Sinkronisasi',
                    text: 'Apakah Anda yakin ingin mensinkronkan jadwal karyawan? Ini akan memperbarui semua jadwal berdasarkan shift yang berlaku.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-sync-alt"></i> Ya, Sinkronkan!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: '{{ route('presensi.master.jadwal-kerja.sync') }}',
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}' }
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.value.success,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        table.ajax.reload();
                    }
                }).catch((error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.responseJSON?.error || 'Terjadi kesalahan saat sinkronisasi',
                    });
                });
            });

            // Add Button
            $('#tambah-btn').on('click', function() {
                // First check if there are karyawan without jadwal
                $.ajax({
                    url: '{{ route('presensi.master.karyawan-without-jadwal.select2') }}',
                    type: 'GET',
                    data: { q: '' },
                    success: function(karyawanData) {
                        if (karyawanData.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Informasi',
                                text: 'Semua karyawan sudah memiliki jadwal. Silakan edit jadwal yang sudah ada atau tambah karyawan baru terlebih dahulu.',
                                confirmButtonColor: '#2563eb',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Show modal to select karyawan first
                        Swal.fire({
                            title: 'Pilih Karyawan',
                            html: `
                                <div class="form-group text-left">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-user"></i> Karyawan
                                    </label>
                                    <select id="swal-karyawan" class="form-control" style="width: 100%;">
                                        <option value="">Pilih Karyawan...</option>
                                    </select>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle"></i> Hanya menampilkan karyawan yang belum memiliki jadwal
                                    </small>
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: '<i class="fas fa-arrow-right"></i> Lanjutkan',
                            cancelButtonText: '<i class="fas fa-times"></i> Batal',
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d',
                            width: '550px',
                            customClass: {
                                container: 'swal-select-karyawan',
                                popup: 'rounded-lg',
                                content: 'text-left'
                            },
                            didOpen: () => {
                                $('#swal-karyawan').select2({
                                    theme: 'bootstrap4',
                                    dropdownParent: $('.swal-select-karyawan'),
                                    ajax: {
                                        url: '{{ route('presensi.master.karyawan-without-jadwal.select2') }}',
                                        dataType: 'json',
                                        delay: 250,
                                        data: function(params) {
                                            return { q: params.term };
                                        },
                                        processResults: function(data) {
                                            return { results: data };
                                        },
                                        cache: true
                                    },
                                    placeholder: 'Ketik untuk mencari karyawan...',
                                    minimumInputLength: 0,
                                    allowClear: true,
                                    width: '100%'
                                });
                            },
                            preConfirm: () => {
                                const karyawanId = $('#swal-karyawan').val();
                                if (!karyawanId) {
                                    Swal.showValidationMessage('Harap pilih karyawan!');
                                    return false;
                                }
                                return karyawanId;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const selectedKaryawanId = result.value;
                                const selectedKaryawan = $('#swal-karyawan option:selected').text();

                                // Close SweetAlert completely before opening Bootstrap modal
                                Swal.close();

                                // Delay to ensure SweetAlert is fully closed
                                setTimeout(() => {
                                    openAddJadwalModal(selectedKaryawanId, selectedKaryawan);
                                }, 300);
                            }
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat memuat data karyawan'
                        });
                    }
                });
            });

            // Function to open add jadwal modal
            function openAddJadwalModal(karyawanId, karyawanName) {
                // Remove any remaining SweetAlert backdrops
                $('.swal2-container').remove();
                $('.swal2-backdrop-show').remove();
                $('body').removeClass('swal2-shown swal2-height-auto');

                // Initialize temp jadwal data
                window.tempJadwalData = {};
                const hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

                // Initialize with empty data for all days
                for (let i = 0; i <= 6; i++) {
                    window.tempJadwalData[i] = {
                        shift_id: '',
                        hari: hariNames[i],
                        lokasi: ''
                    };
                }

                // Load shifts and locations
                $.when(
                    $.ajax({ url: '{{ route('presensi.master.shift-kerja.data') }}', type: 'GET' }),
                    $.ajax({ url: '{{ route('presensi.master.kantor.get') }}', type: 'GET' })
                ).done(function(shiftResponse, lokasiResponse) {
                    window.shiftsData = shiftResponse[0].data;
                    window.lokasiData = lokasiResponse[0].data;

                    // Build day cards
                    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const dayColors = ['dark', 'primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                    const dayIcons = ['🌙', '📅', '📅', '📅', '📅', '📅', '🎉'];

                    let cardsHtml = '';

                    dayNames.forEach((dayName, dayIndex) => {
                        cardsHtml += `
                            <div class="col-md-6 mb-3">
                                <div class="card day-card" data-day-index="${dayIndex}">
                                    <div class="day-card-header">
                                        <strong class="text-${dayColors[dayIndex]}">
                                            ${dayIcons[dayIndex]} ${dayName}
                                        </strong>
                                        <button class="btn btn-sm btn-outline-primary add-day-shift-btn" data-day-index="${dayIndex}" data-day-name="${dayName}">
                                            <i class="fas fa-plus"></i> Atur Shift
                                        </button>
                                    </div>
                                    <div class="day-card-body" id="add-day-${dayIndex}">
                                        <p class="no-shift-text mb-0">Belum ada shift</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    // Show modal with Bootstrap
                    const modalHtml = `
                        <div class="modal fade" id="addJadwalModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-calendar-plus"></i> Tambah Jadwal Karyawan
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        <div class="alert alert-success">
                                            <i class="fas fa-user"></i>
                                            <strong>Karyawan: ${karyawanName}</strong>
                                        </div>
                                        <div class="row">${cardsHtml}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                        <button type="button" class="btn btn-success" id="saveAddJadwalBtn" data-karyawan-id="${karyawanId}">
                                            <i class="fas fa-save"></i> Simpan Jadwal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal if any
                    $('#addJadwalModal').remove();

                    // Append and show modal
                    $('body').append(modalHtml);
                    $('#addJadwalModal').modal('show');

                    // Handle modal close
                    $('#addJadwalModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                        window.tempJadwalData = null;
                        // Remove any lingering backdrops
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');
                    });
                });
            }

            // Add day shift button handler
            $(document).on('click', '.add-day-shift-btn', function(e) {
                e.stopPropagation();
                const dayIndex = $(this).data('day-index');
                const dayName = $(this).data('day-name');
                const hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

                $('#current-day-index').val(dayIndex);
                $('#modal-day-name').text(dayName);

                // Get current data for this day
                const currentData = window.tempJadwalData[dayIndex];

                // Build shift options
                let shiftOptions = '<option value="">Pilih Shift...</option>';
                if (window.shiftsData) {
                    window.shiftsData.forEach(function(shift) {
                        const tipeBadge = shift.tipe ? `[${shift.tipe}]` : '';
                        const selected = shift.id === currentData.shift_id ? 'selected' : '';
                        shiftOptions += `<option value="${shift.id}" ${selected}>${shift.nama_shift} ${tipeBadge} (${shift.jam_masuk_max} - ${shift.jam_pulang_min})</option>`;
                    });
                }

                // Build location options
                let lokasiOptions = '<option value="">Pilih Lokasi...</option>';
                if (window.lokasiData) {
                    window.lokasiData.forEach(function(lokasi) {
                        const selected = lokasi.id === currentData.lokasi ? 'selected' : '';
                        lokasiOptions += `<option value="${lokasi.id}" ${selected}>${lokasi.name}</option>`;
                    });
                }

                $('#shift-select').html(shiftOptions).select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#editShiftModal'),
                    placeholder: 'Pilih Shift...',
                    allowClear: true
                });

                $('#lokasi-select').html(lokasiOptions).select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#editShiftModal'),
                    placeholder: 'Pilih Lokasi...',
                    allowClear: true
                });

                // Store that we're in add mode
                $('#editShiftModal').data('mode', 'add');
                $('#editShiftModal').modal('show');
            });

            // Save add jadwal button
            $(document).on('click', '#saveAddJadwalBtn', function() {
                const karyawanId = $(this).data('karyawan-id');
                const btn = $(this);

                // Validate: at least one day must have a shift
                let hasShift = false;
                for (let i = 0; i <= 6; i++) {
                    if (window.tempJadwalData[i].shift_id) {
                        hasShift = true;
                        break;
                    }
                }

                if (!hasShift) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Harap atur shift minimal untuk satu hari!',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                // Disable button
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                // Save to server
                $.ajax({
                    url: '{{ route('presensi.master.jadwal-kerja.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_karyawan: karyawanId,
                        jadwal_json: JSON.stringify(window.tempJadwalData)
                    },
                    success: function(response) {
                        $('#addJadwalModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan';
                        if (xhr.responseJSON?.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                        } else if (xhr.responseJSON?.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: errorMsg
                        });
                        btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Jadwal');
                    }
                });
            });

            // Edit Button - Show schedule overview
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $('#edit-jadwal-id').val(id);

                $.ajax({
                    url: '{{ url('presensi/jadwal/jadwal-kerja') }}/' + id + '/edit',
                    type: 'GET',
                    success: function(jadwal) {
                        // Check if jadwal_json is already an object or needs parsing
                        let jadwalData;
                        if (typeof jadwal.jadwal_json === 'string') {
                            jadwalData = JSON.parse(jadwal.jadwal_json);
                        } else if (typeof jadwal.jadwal_json === 'object') {
                            jadwalData = jadwal.jadwal_json;
                        } else {
                            jadwalData = {};
                            console.error('Invalid jadwal_json format:', jadwal.jadwal_json);
                        }

                        $('#karyawan-info').html(`
                            Karyawan: <strong>${jadwal.karyawan.fullName}</strong>
                            <small class="text-muted">(NIK: ${jadwal.karyawan.nik})</small>
                        `);

                        // Store jadwal data globally for later use
                        window.currentJadwalData = jadwalData;
                        window.currentJadwalId = id;

                        // Load shifts and locations
                        $.when(
                            $.ajax({ url: '{{ route('presensi.master.shift-kerja.data') }}', type: 'GET' }),
                            $.ajax({ url: '{{ route('presensi.master.kantor.get') }}', type: 'GET' })
                        ).done(function(shiftResponse, lokasiResponse) {
                            // Store for later use
                            window.shiftsData = shiftResponse[0].data;
                            window.lokasiData = lokasiResponse[0].data;

                            // Build day cards
                            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            const dayColors = ['dark', 'primary', 'success', 'info', 'warning', 'danger', 'secondary'];
                            const dayIcons = ['🌙', '📅', '📅', '📅', '📅', '📅', '🎉'];

                            let cardsHtml = '';

                            dayNames.forEach((dayName, dayIndex) => {
                                const dayData = jadwalData[dayIndex];
                                let shiftId = '';
                                let lokasiId = '';
                                let shiftName = 'Belum Ada Shift';
                                let shiftTime = '-';
                                let shiftType = '';
                                let lokasiName = '-';

                                if (typeof dayData === 'string') {
                                    shiftId = dayData;
                                } else if (typeof dayData === 'object' && dayData !== null) {
                                    shiftId = dayData.shift_id || '';
                                    lokasiId = dayData.lokasi || '';
                                }

                                // Find shift details
                                if (shiftId && window.shiftsData) {
                                    const shift = window.shiftsData.find(s => s.id === shiftId);
                                    if (shift) {
                                        shiftName = shift.nama_shift;
                                        shiftTime = `${shift.jam_masuk_max} - ${shift.jam_pulang_min}`;
                                        shiftType = shift.tipe || '';
                                    }
                                }

                                // Find location name
                                if (lokasiId && window.lokasiData) {
                                    const lokasi = window.lokasiData.find(l => l.id === lokasiId);
                                    if (lokasi) {
                                        lokasiName = lokasi.name;
                                    }
                                }

                                const typeBadge = shiftType ? `<span class="badge badge-${shiftType === 'WFO' ? 'success' : 'info'} badge-sm">${shiftType}</span>` : '';

                                cardsHtml += `
                                    <div class="col-md-6 mb-3">
                                        <div class="card day-card" data-day-index="${dayIndex}" data-day-name="${dayName}">
                                            <div class="day-card-header">
                                                <strong class="text-${dayColors[dayIndex]}">
                                                    ${dayIcons[dayIndex]} ${dayName}
                                                </strong>
                                                <button class="btn btn-sm btn-outline-primary edit-day-btn" data-day-index="${dayIndex}" data-day-name="${dayName}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                            <div class="day-card-body">
                                                ${shiftId ? `
                                                    <div class="shift-info">
                                                        <span class="shift-name">${shiftName} ${typeBadge}</span>
                                                        <span class="shift-detail">
                                                            <i class="fas fa-clock text-muted"></i> ${shiftTime}
                                                        </span>
                                                    </div>
                                                    <div class="shift-info">
                                                        <span class="shift-detail">
                                                            <i class="fas fa-map-marker-alt text-danger"></i> ${lokasiName}
                                                        </span>
                                                    </div>
                                                ` : `
                                                    <p class="no-shift-text mb-0">Belum ada shift</p>
                                                `}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            $('#jadwal-days-container').html(cardsHtml);
                            $('#editJadwalModal').modal('show');
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Tidak dapat memuat data jadwal'
                        });
                    }
                });
            });

            // Edit Day Button - Open shift selection modal
            $(document).on('click', '.edit-day-btn', function(e) {
                e.stopPropagation();
                const dayIndex = $(this).data('day-index');
                const dayName = $(this).data('day-name');
                const hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

                $('#current-day-index').val(dayIndex);
                $('#modal-day-name').text(dayName);

                // Get current shift and location for this day
                const dayData = window.currentJadwalData[dayIndex];
                let currentShiftId = '';
                let currentLokasiId = '';

                if (typeof dayData === 'string') {
                    currentShiftId = dayData;
                } else if (typeof dayData === 'object' && dayData !== null) {
                    currentShiftId = dayData.shift_id || '';
                    currentLokasiId = dayData.lokasi || '';
                }

                // Build shift options
                let shiftOptions = '<option value="">Pilih Shift...</option>';
                if (window.shiftsData) {
                    window.shiftsData.forEach(function(shift) {
                        const tipeBadge = shift.tipe ? `[${shift.tipe}]` : '';
                        const selected = shift.id === currentShiftId ? 'selected' : '';
                        shiftOptions += `<option value="${shift.id}" ${selected}>${shift.nama_shift} ${tipeBadge} (${shift.jam_masuk_max} - ${shift.jam_pulang_min})</option>`;
                    });
                }

                // Build location options
                let lokasiOptions = '<option value="">Pilih Lokasi...</option>';
                if (window.lokasiData) {
                    window.lokasiData.forEach(function(lokasi) {
                        const selected = lokasi.id === currentLokasiId ? 'selected' : '';
                        lokasiOptions += `<option value="${lokasi.id}" ${selected}>${lokasi.name}</option>`;
                    });
                }

                $('#shift-select').html(shiftOptions).select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#editShiftModal'),
                    placeholder: 'Pilih Shift...',
                    allowClear: true
                });

                $('#lokasi-select').html(lokasiOptions).select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#editShiftModal'),
                    placeholder: 'Pilih Lokasi...',
                    allowClear: true
                });

                // Set to edit mode (default)
                $('#editShiftModal').data('mode', 'edit');
                $('#editShiftModal').modal('show');
            });

            // Save Shift Button
            $('#saveShiftBtn').on('click', function() {
                const dayIndex = parseInt($('#current-day-index').val());
                const shiftId = $('#shift-select').val();
                const lokasiId = $('#lokasi-select').val();
                const hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const mode = $('#editShiftModal').data('mode');

                if (mode === 'add') {
                    // Update temp jadwal data for add mode
                    window.tempJadwalData[dayIndex] = {
                        shift_id: shiftId,
                        hari: hariNames[dayIndex],
                        lokasi: lokasiId
                    };

                    // Update the card display in add modal
                    let shiftName = 'Belum Ada Shift';
                    let shiftTime = '-';
                    let shiftType = '';
                    let lokasiName = '-';
                    let typeBadge = '';

                    if (shiftId && window.shiftsData) {
                        const shift = window.shiftsData.find(s => s.id === shiftId);
                        if (shift) {
                            shiftName = shift.nama_shift;
                            shiftTime = `${shift.jam_masuk_max} - ${shift.jam_pulang_min}`;
                            shiftType = shift.tipe || '';
                            typeBadge = shiftType ? `<span class="badge badge-${shiftType === 'WFO' ? 'success' : 'info'} badge-sm">${shiftType}</span>` : '';
                        }
                    }

                    if (lokasiId && window.lokasiData) {
                        const lokasi = window.lokasiData.find(l => l.id === lokasiId);
                        if (lokasi) {
                            lokasiName = lokasi.name;
                        }
                    }

                    // Update card body in add modal
                    const cardBody = $(`#add-day-${dayIndex}`);
                    if (shiftId) {
                        cardBody.html(`
                            <div class="shift-info">
                                <span class="shift-name">${shiftName} ${typeBadge}</span>
                                <span class="shift-detail">
                                    <i class="fas fa-clock text-muted"></i> ${shiftTime}
                                </span>
                            </div>
                            <div class="shift-info">
                                <span class="shift-detail">
                                    <i class="fas fa-map-marker-alt text-danger"></i> ${lokasiName}
                                </span>
                            </div>
                        `);
                    } else {
                        cardBody.html('<p class="no-shift-text mb-0">Belum ada shift</p>');
                    }

                    $('#editShiftModal').modal('hide');

                } else {
                    // Edit mode - save to server immediately
                    window.currentJadwalData[dayIndex] = {
                        shift_id: shiftId,
                        hari: hariNames[dayIndex],
                        lokasi: lokasiId
                    };

                    // Disable button
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                    // Save to server
                    $.ajax({
                        url: '{{ url('presensi/jadwal/jadwal-kerja-update') }}/' + window.currentJadwalId,
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            jadwal_json: JSON.stringify(window.currentJadwalData)
                        },
                        success: function(response) {
                            $('#editShiftModal').modal('hide');

                            // Update the card display
                            let shiftName = 'Belum Ada Shift';
                            let shiftTime = '-';
                            let shiftType = '';
                            let lokasiName = '-';
                            let typeBadge = '';

                            if (shiftId && window.shiftsData) {
                                const shift = window.shiftsData.find(s => s.id === shiftId);
                                if (shift) {
                                    shiftName = shift.nama_shift;
                                    shiftTime = `${shift.jam_masuk_max} - ${shift.jam_pulang_min}`;
                                    shiftType = shift.tipe || '';
                                    typeBadge = shiftType ? `<span class="badge badge-${shiftType === 'WFO' ? 'success' : 'info'} badge-sm">${shiftType}</span>` : '';
                                }
                            }

                            if (lokasiId && window.lokasiData) {
                                const lokasi = window.lokasiData.find(l => l.id === lokasiId);
                                if (lokasi) {
                                    lokasiName = lokasi.name;
                                }
                            }

                            // Update card body
                            const cardBody = $(`.day-card[data-day-index="${dayIndex}"] .day-card-body`);
                            if (shiftId) {
                                cardBody.html(`
                                    <div class="shift-info">
                                        <span class="shift-name">${shiftName} ${typeBadge}</span>
                                        <span class="shift-detail">
                                            <i class="fas fa-clock text-muted"></i> ${shiftTime}
                                        </span>
                                    </div>
                                    <div class="shift-info">
                                        <span class="shift-detail">
                                            <i class="fas fa-map-marker-alt text-danger"></i> ${lokasiName}
                                        </span>
                                    </div>
                                `);
                            } else {
                                cardBody.html('<p class="no-shift-text mb-0">Belum ada shift</p>');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Jadwal berhasil diperbarui',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Reload table data
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan';
                            if (xhr.responseJSON?.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMsg
                            });
                        },
                        complete: function() {
                            $('#saveShiftBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                        }
                    });
                }
            });

            // Clean up when modals are closed
            $('#editJadwalModal').on('hidden.bs.modal', function() {
                $('#jadwal-days-container').html('');
                window.currentJadwalData = null;
                window.currentJadwalId = null;
            });

            $('#editShiftModal').on('hidden.bs.modal', function() {
                $('#shift-select').select2('destroy');
                $('#lokasi-select').select2('destroy');
            });

            // Delete Button
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('presensi/jadwal/jadwal-kerja-delete') }}/' + id,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.success,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error || 'Terjadi kesalahan'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

