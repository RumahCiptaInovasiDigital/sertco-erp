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
@section('content')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt mr-1"></i> Data Jadwal & Presensi <br>
                <small class="text-muted font-weight-bold" id="periode-label" style="font-size: 80%;">Loading...</small>
            </h3>
            <div class="card-tools">

                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-info btn-sm btn-reload ">
                        <i class="fas fa-sync-alt"></i> Reloads
                    </button>

                </div>

                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-filter text-primary"></i> Pilih Periode
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" id="current-week-btn">Minggu Ini</a>
                        <a class="dropdown-item" href="#" id="next-week-btn">Minggu Depan (+7 Hari)</a>
                        <a class="dropdown-item" href="#" id="prev-week-btn">Minggu Lalu (-7 Hari)</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="custom-period-btn">Pilih Tanggal Custom</a>
                    </div>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-calendar-check"></i> Generate Jadwal
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" id="gen-current-week-btn">Minggu Ini</a>
                        <a class="dropdown-item" href="#" id="gen-next-week-btn">Minggu Depan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="gen-custom-period-btn">Periode Custom</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body bg-light border-bottom" id="custom-period-container" style="display: none;">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="small text-muted mb-1">Tanggal Awal:</label>
                        <input type="date" class="form-control form-control-sm" id="tanggal-awal">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="small text-muted mb-1">Tanggal Akhir (Otomatis +6 Hari):</label>
                        <input type="date" class="form-control form-control-sm" id="tanggal-akhir" readonly>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm btn-block" id="apply-custom-period-btn">
                        <i class="fas fa-check"></i> Terapkan Filter
                    </button>
                </div>
            </div>
            <small class="text-danger mt-2 d-block font-italic">*Filter dikunci per 7 hari agar tampilan tabel tetap
                rapi.</small>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="jadwal-karyawan-table" class="table table-bordered table-striped w-100">
                    <thead>
                    <tr>
                        <th style="width: 10px" class="align-middle">No</th>
                        <th class="align-middle">Nama Lengkap</th>
                        <th class="align-middle">Jabatan</th>

                        <th class="text-center align-middle header-hari">Senin</th>
                        <th class="text-center align-middle header-hari">Selasa</th>
                        <th class="text-center align-middle header-hari">Rabu</th>
                        <th class="text-center align-middle header-hari">Kamis</th>
                        <th class="text-center align-middle header-hari">Jum'at</th>
                        <th class="text-center align-middle header-hari">Sabtu</th>
                        <th class="text-center align-middle header-hari text-danger">Minggu</th>

                        {{--                        <th width="10%" class="text-center align-middle">Aksi</th>--}}
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditShift" tabindex="-1" role="dialog" aria-labelledby="modalEditShiftLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditShiftLabel">
                        <i class="fas fa-calendar-edit mr-2"></i>Ubah Jadwal & Lokasi Kerja
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUpdateShift">
                    @csrf
                    <input type="hidden" name="id" id="edit_jadwal_id">

                    <div class="modal-body">
                        <!-- Info Karyawan -->
                        <div class="card mb-3">
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Nama Karyawan</label>
                                        <input type="text" class="form-control form-control-sm" id="edit_nama_karyawan" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Tanggal</label>
                                        <input type="text" class="form-control form-control-sm" id="edit_tanggal" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal Shift -->
                        <div class="form-group">
                            <label for="edit_shift_id">
                                <i class="fas fa-clock text-primary mr-1"></i>Pilih Shift Baru
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="shift_kerja_id" id="edit_shift_id" style="width: 100%;" required>
                            </select>
                            <small class="form-text text-muted">
                                Pilih shift kerja yang sesuai (jam masuk & pulang otomatis terisi)
                            </small>
                        </div>

                        <hr>

                        <!-- Lokasi Kerja -->
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>Lokasi Kerja
                        </h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lokasi_masuk" class="small">
                                        Lokasi Masuk
                                    </label>
                                    <select class="form-control select2" name="origin_branchoffice_masuk_id" id="edit_lokasi_masuk" style="width: 100%;">
                                        <option value="">-- Pilih Lokasi --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lokasi_pulang" class="small">
                                        Lokasi Pulang
                                    </label>
                                    <select class="form-control select2" name="origin_branchoffice_pulang_id" id="edit_lokasi_pulang" style="width: 100%;">
                                        <option value="">-- Pilih Lokasi --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle mr-1"></i>
                            <small>
                                <strong>Info:</strong> Lokasi ini menentukan titik origin presensi karyawan. Jika kosong, karyawan bisa presensi dari mana saja.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Batal
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSimpanShift">
                            <i class="fas fa-save mr-1"></i>Simpan Perubahan
                        </button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#edit_shift_id').select2({
                dropdownParent: $('#modalEditShift'),
                theme: 'bootstrap4',
                width: '100%'
            });

            $('#edit_lokasi_masuk').select2({
                dropdownParent: $('#modalEditShift'),
                theme: 'bootstrap4',
                width: '100%',
                placeholder: '-- Pilih Lokasi Masuk --',
                allowClear: true
            });

            $('#edit_lokasi_pulang').select2({
                dropdownParent: $('#modalEditShift'),
                theme: 'bootstrap4',
                width: '100%',
                placeholder: '-- Pilih Lokasi Pulang --',
                allowClear: true
            });

            moment.locale('id');
            $('.btn-reload').click(function (e) {
                e.preventDefault();

                // Ambil elemen tombol dan icon
                let btn = $(this);
                let icon = btn.find('i');

                // 1. Tambahkan animasi putar (spin)
                icon.addClass('fa-spin');

                // 2. Reload DataTable
                // Parameter (callback, resetPaging)
                // null = tidak ada callback khusus
                // false = JANGAN reset paging (tetap di halaman yang sama, misal halaman 2)
                $('#jadwal-karyawan-table').DataTable().ajax.reload(function () {
                    // 3. Hentikan animasi saat selesai loading
                    icon.removeClass('fa-spin');
                }, false);
            });
            // =======================================================
            // 1. STATE MANAGEMENT
            // =======================================================
            let filterStartDate = moment().startOf('isoWeek').format('YYYY-MM-DD');

            // =======================================================
            // 2. HELPER FUNCTIONS (TABEL & FILTER)
            // =======================================================
            function updateDateState(newStartDate) {
                filterStartDate = newStartDate;
                let startM = moment(filterStartDate);
                let endM = startM.clone().add(6, 'days');

                $('#periode-label').html(`
                    <span class="badge badge-light border">
                        ${startM.format('DD MMMM YYYY')} s/d ${endM.format('DD MMMM YYYY')}
                    </span>
                `);

                $('#tanggal-awal').val(filterStartDate);
                $('#tanggal-akhir').val(endM.format('YYYY-MM-DD'));
            }

            function getJadwalByOffset(presensiArray, offsetDays) {
                if (!presensiArray || presensiArray.length === 0) return '-';
                let targetDate = moment(filterStartDate).add(offsetDays, 'days').format('YYYY-MM-DD');

                let found = presensiArray.find(item => {
                    return item.tanggal && item.tanggal.substring(0, 10) === targetDate;
                });

                if (found) {
                    let masuk = found.jam_harus_masuk_akhir ? found.jam_harus_masuk_akhir.substring(0, 5) : '--:--';
                    let pulang = found.jam_harus_pulang_awal ? found.jam_harus_pulang_awal.substring(0, 5) : '--:--';

                    // Logika warna WFO/WFA
                    let colorClass = (found.type_presensi === 'WFO') ? 'text-success' : 'text-danger';

                    // Lokasi info
                    let lokasiMasuk = found.origin_office_masuk ? found.origin_office_masuk.name : '-';
                    let lokasiPulang = found.origin_office_pulang ? found.origin_office_pulang.name : '-';
                    let lokasiSama = lokasiMasuk === lokasiPulang;

                    let jadwalId = found.id || '';

                    return `
        <div class="text-center">
            <button type="button"
                    class="btn btn-outline-primary btn-sm rounded-pill btn-ubah-shift"
                    data-id="${jadwalId}"
                    title="Klik untuk ubah shift & lokasi"
                    style="font-size: 11px; min-width: 100px; padding: 3px 10px;">
                <i class="far fa-clock mr-1"></i>${masuk} - ${pulang}
            </button>

            <div class="mt-1" style="font-size: 11px; line-height: 1.2">
                <span class="badge badge-${found.type_presensi === 'WFO' ? 'success' : 'danger'} badge-sm">
                    ${found.type_presensi || 'WFO'}
                </span>
            </div>

            <div class="mt-1 text-muted" style="font-size: 10px; line-height: 1.1">
                ${lokasiSama ?
                    `<i class="fas fa-map-marker-alt"></i> ${lokasiMasuk}` :
                    `<i class="fas fa-sign-in-alt text-success"></i> ${lokasiMasuk}<br><i class="fas fa-sign-out-alt text-danger"></i> ${lokasiPulang}`
                }
            </div>
        </div>`;
                }
                return '<div class="text-center text-muted">-</div>';
            }


            $(document).on('click', '.btn-ubah-shift', function() {
                var idJadwal = $(this).data('id');
                var btn = $(this);

                // Tampilkan loading text (opsional)
                var originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                btn.prop('disabled', true);

                // Panggil Route Backend
                var url = "{{ route('presensi.jadwal.jadwal-karyawan.shift', ':id') }}";
                url = url.replace(':id', idJadwal);

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        // 1. Isi Data Hidden ID & Info Karyawan
                        $('#edit_jadwal_id').val(response.presensi.id);
                        $('#edit_nama_karyawan').val(response.presensi.karyawan.fullName);

                        // Format Tanggal
                        var formattedDate = moment(response.presensi.tanggal).format('DD MMMM YYYY');
                        $('#edit_tanggal').val(formattedDate);

                        // 2. Generate Options untuk Select Shift
                        var shiftOptions = '<option value="">-- Pilih Shift --</option>';
                        var currentShiftId = response.presensi.shift_kerja_id;

                        $.each(response.shifts, function(key, shift) {
                            var isSelected = (shift.id == currentShiftId) ? 'selected' : '';
                            var jam = shift.jam_masuk_max.substring(0, 5) + ' - ' + shift.jam_pulang_min.substring(0, 5);
                            var tipe = shift.tipe;

                            shiftOptions += `<option value="${shift.id}" ${isSelected}>
                                        ${shift.nama_shift} (${jam}) | ${tipe}
                                   </option>`;
                        });

                        $('#edit_shift_id').html(shiftOptions).trigger('change');

                        // 3. Generate Options untuk Select Lokasi
                        var lokasiOptions = '<option value="">-- Pilih Lokasi (Opsional) --</option>';
                        var currentLokasiMasuk = response.presensi.origin_branchoffice_masuk_id;
                        var currentLokasiPulang = response.presensi.origin_branchoffice_pulang_id;

                        $.each(response.branchOffices, function(key, office) {
                            var isSelectedMasuk = (office.id == currentLokasiMasuk) ? 'selected' : '';
                            var isSelectedPulang = (office.id == currentLokasiPulang) ? 'selected' : '';

                            lokasiOptions += `<option value="${office.id}">${office.name} - ${office.city}</option>`;
                        });

                        // Populate lokasi masuk
                        var lokasiMasukOptions = '<option value="">-- Pilih Lokasi (Opsional) --</option>';
                        $.each(response.branchOffices, function(key, office) {
                            var isSelected = (office.id == currentLokasiMasuk) ? 'selected' : '';
                            lokasiMasukOptions += `<option value="${office.id}" ${isSelected}>${office.name} - ${office.city}</option>`;
                        });
                        $('#edit_lokasi_masuk').html(lokasiMasukOptions).trigger('change');

                        // Populate lokasi pulang
                        var lokasiPulangOptions = '<option value="">-- Pilih Lokasi (Opsional) --</option>';
                        $.each(response.branchOffices, function(key, office) {
                            var isSelected = (office.id == currentLokasiPulang) ? 'selected' : '';
                            lokasiPulangOptions += `<option value="${office.id}" ${isSelected}>${office.name} - ${office.city}</option>`;
                        });
                        $('#edit_lokasi_pulang').html(lokasiPulangOptions).trigger('change');

                        // 4. Buka Modal
                        $('#modalEditShift').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal mengambil data jadwal.', 'error');
                        console.log(xhr.responseText);
                    },
                    complete: function() {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }
                });
            });

            // Event Klik Tombol Simpan di Modal
            $('#btnSimpanShift').click(function(e) {
                e.preventDefault();

                // 1. Ambil Data dari Input
                var idJadwal = $('#edit_jadwal_id').val();
                var shiftId = $('#edit_shift_id').val();
                var lokasiMasuk = $('#edit_lokasi_masuk').val();
                var lokasiPulang = $('#edit_lokasi_pulang').val();
                var btn = $(this);

                // 2. Validasi Sederhana
                if (!shiftId) {
                    Swal.fire('Peringatan', 'Harap pilih shift baru terlebih dahulu!', 'warning');
                    return;
                }

                // 3. Ubah Tombol jadi Loading
                var originalText = btn.html();
                btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                // 4. Siapkan URL
                var url = "{{ route('presensi.jadwal.jadwal-karyawan.shift', ':id') }}";
                url = url.replace(':id', idJadwal);

                // 5. Kirim AJAX POST
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: idJadwal,
                        shift_kerja_id: shiftId,
                        origin_branchoffice_masuk_id: lokasiMasuk,
                        origin_branchoffice_pulang_id: lokasiPulang
                    },
                    success: function(response) {
                        Swal.fire('Sukses', response.message, 'success').then(() => {
                            $('#modalEditShift').modal('hide');
                        });
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire('Error', 'Gagal mengubah jadwal. Silakan coba lagi.', 'error');
                    },
                    complete: function() {
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // =======================================================
            // 3. DATATABLES CONFIGURATION
            // =======================================================
            var table = $("#jadwal-karyawan-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('presensi.jadwal.jadwal-karyawan.get') }}",
                    data: function (d) {
                        d.tanggal_awal = filterStartDate;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'fullName', name: 'fullName'},
                    {data: 'namaJabatan', name: 'namaJabatan'},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 0)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 1)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 2)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 3)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 4)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 5)},
                    {data: 'presensi', orderable: false, render: (d) => getJadwalByOffset(d, 6)},
                    // { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ],
                drawCallback: function (settings) {
                    var api = this.api();
                    var json = api.ajax.json();
                    if (json && json.headerDates) {
                        let currentStart = moment(filterStartDate);
                        json.headerDates.forEach((dateString, index) => {
                            let dayName = currentStart.clone().add(index, 'days').format('dddd');
                            let headerCell = $(api.column(index + 3).header());
                            headerCell.html(`${dayName} <br> <small class="text-secondary font-weight-normal">(${dateString})</small>`);

                            if (dayName.toLowerCase() === 'minggu') {
                                headerCell.addClass('text-danger');
                            } else {
                                headerCell.removeClass('text-danger');
                            }
                        });
                    }
                },
                autoWidth: false,
                responsive: true
            });

            updateDateState(filterStartDate);


            // =======================================================
            // 4. EVENT LISTENERS: FILTER (PILIH PERIODE)
            // =======================================================
            $('#custom-period-btn').click(function (e) {
                e.preventDefault();
                $('#custom-period-container').slideToggle();
            });

            $('#tanggal-awal').on('change', function () {
                let val = $(this).val();
                if (val) {
                    let end = moment(val).add(6, 'days').format('YYYY-MM-DD');
                    $('#tanggal-akhir').val(end);
                }
            });

            $('#apply-custom-period-btn').click(function () {
                let val = $('#tanggal-awal').val();
                if (!val) return Swal.fire('Error', 'Silakan pilih tanggal awal!', 'error');
                $('#custom-period-container').slideUp();
                updateDateState(val);
                table.draw();
            });

            $('#current-week-btn').click(function (e) {
                e.preventDefault();
                updateDateState(moment().startOf('isoWeek').format('YYYY-MM-DD'));
                table.draw();
            });

            $('#next-week-btn').click(function (e) {
                e.preventDefault();
                updateDateState(moment(filterStartDate).add(7, 'days').format('YYYY-MM-DD'));
                table.draw();
            });

            $('#prev-week-btn').click(function (e) {
                e.preventDefault();
                updateDateState(moment(filterStartDate).subtract(7, 'days').format('YYYY-MM-DD'));
                table.draw();
            });


            // =======================================================
            // 5. EVENT LISTENERS: GENERATE JADWAL
            // =======================================================

            // Fungsi Inti AJAX Generate
            function postGenerateJadwal(tanggalAwal, labelPeriode) {
                Swal.fire({
                    title: 'Generate Jadwal?',
                    text: `Jadwal periode [${labelPeriode}] akan dibuat. Data existing tidak akan diduplikasi.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Generate!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: "{{ route('presensi.jadwal.jadwal-karyawan.generate') }}",
                            type: "POST",
                            data: {tanggal_awal: tanggalAwal},
                            success: function (response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                table.ajax.reload(); // Reload tabel otomatis
                            },
                            error: function (xhr) {
                                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                                Swal.fire('Gagal!', msg, 'error');
                            }
                        });
                    }
                });
            }

            // Tombol: Generate Minggu Ini (ID baru: gen-current-week-btn)
            $('#gen-current-week-btn').click(function (e) {
                e.preventDefault();
                let start = moment().startOf('isoWeek').format('YYYY-MM-DD');
                postGenerateJadwal(start, "Minggu Ini");
            });

            // Tombol: Generate Minggu Depan (ID baru: gen-next-week-btn)
            $('#gen-next-week-btn').click(function (e) {
                e.preventDefault();
                let start = moment().startOf('isoWeek').add(7, 'days').format('YYYY-MM-DD');
                postGenerateJadwal(start, "Minggu Depan");
            });

            // Tombol: Generate Custom (ID baru: gen-custom-period-btn)
            $('#gen-custom-period-btn').click(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Pilih Tanggal Mulai Generate',
                    html: '<p>Jadwal akan digenerate selama <b>7 hari</b>.</p>',
                    input: 'date',
                    inputValue: moment().format('YYYY-MM-DD'),
                    showCancelButton: true,
                    confirmButtonText: 'Lanjut',
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('Tanggal harus diisi!')
                        }
                        return value
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let start = result.value;
                        let label = moment(start).format('DD-MM-YYYY');
                        postGenerateJadwal(start, label);
                    }
                });
            });
        });
    </script>
@endsection
