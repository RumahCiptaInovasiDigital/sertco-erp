@extends('layouts.master')
@section('title', 'Pengajuan Izin - Cuti')
@section('PageTitle', 'Pengajuan Izin - Cuti')
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
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <select class="form-control select2" id="filterPeriode" name="periode"
                                                    style="width: 80%;">
                                                @foreach(range(date('Y'), date('Y') + 1) as $year)
                                                    @foreach(range(1, 12) as $month)
                                                        @php
                                                            $val = sprintf('%s-%s', $year, str_pad($month, 2, '0', STR_PAD_LEFT));
                                                            $selected = (date('Y-m') == $val) ? 'selected' : '';
                                                        @endphp
                                                        <option value="{{ $val }}" {{ $selected }}>
                                                            {{ date("F", mktime(0, 0, 0, $month, 10)) . ' ' . $year }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7 col-lg-8 text-md-right mt-3 mt-md-0">
                                    <button type="button" id="btnRefresh" class="btn btn-info">
                                        <i class="fas fa-sync mr-1"></i> Refresh
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
                                    <thead class="thead-light">
                                    <tr>
                                        <th style="width: 10px">No</th>

                                        <th>Nama Karyawan</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">Tanggal Mulai</th>
                                        <th class="text-center">Tanggal Selesai</th>
                                        <th class="text-center">Dokumen</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>

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

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengajuan Izin/Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Nama Karyawan</th>
                            <td id="det_nama"></td>
                        </tr>
                        <tr>
                            <th>Jenis Izin</th>
                            <td id="det_jenis"></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td><span id="det_tgl_mulai"></span> s/d <span id="det_tgl_selesai"></span></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td id="det_keterangan"></td>
                        </tr>
                        <tr>
                            <th>File Pendukung</th>
                            <td id="det_file"></td>
                        </tr>
                        <tr>
                            <th>Status Saat Ini</th>
                            <td id="det_status"></td>
                        </tr>
                        <tr>
                            <th>Catatan Approver</th>
                            <td id="det_catatan"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifikasiTitle">Verifikasi Izin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formVerifikasi">
                    @csrf
                    <input type="hidden" id="verifikasiId" name="id">
                    <input type="hidden" id="verifikasiType"> <div class="modal-body">
                        <p id="verifikasiText">Apakah Anda yakin?</p>

                        <div class="form-group">
                            <label>Catatan (Opsional untuk Approve, Wajib untuk Reject)</label>
                            <textarea class="form-control" name="catatan_approver" id="catatan_approver" rows="3" placeholder="Masukkan catatan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" id="btnSubmitVerifikasi">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // 1. Inisialisasi Select2 (Agar tampilan dropdown bagus sesuai class yang ada)
            $('#filterPeriode').select2({
                theme: 'bootstrap4'
            });

            // 2. Inisialisasi DataTable
            const table = $('#resumeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url("presensi/presensi/izin-cuti/data") }}',
                    type: 'GET',
                    // --- PERBAIKAN DISINI: Kirim data periode ke backend ---
                    data: function (d) {
                        d.periode = $('#filterPeriode').val();
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        // Pastikan library toastr sudah diload, jika belum pakai alert biasa
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Gagal memuat data izin');
                        } else {
                            alert('Gagal memuat data');
                        }
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nama', name: 'karyawan.fullName'},
                    {data: 'jenis_izin', name: 'jenis_izin'},
                    {data: 'tanggal_mulai', name: 'tanggal_mulai'},
                    {data: 'tanggal_selesai', name: 'tanggal_selesai'},
                    {data: 'file_pendukung', name: 'file_pendukung', orderable: false, searchable: false},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'status', name: 'status'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
                ],
                order: [[3, 'desc']], // Order by tanggal mulai
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
            });

            // --- 3. Event Listener: Filter Periode Berubah ---
            $('#filterPeriode').on('change', function () {
                // Reload tabel, parameter 'd.periode' otomatis terkirim nilai baru
                table.ajax.reload();
            });

            // --- 4. Event Listener: Tombol Refresh ---
            $('#btnRefresh').on('click', function () {
                // Reload tabel tanpa mereset paging
                table.ajax.reload(null, false);

                // Opsional: Animasi icon putar
                const icon = $(this).find('i');
                icon.addClass('fa-spin');
                setTimeout(function () {
                    icon.removeClass('fa-spin');
                }, 1000);
            });

            // Event handler actions (Approve, Reject, Detail) biarkan seperti semula...
            // --- 1. FUNGSI DETAIL ---
            $(document).on('click', '.detail-btn', function() {
                const id = $(this).data('id');
                const url = '{{ route("presensi.presensi-izin.detail", ":id") }}'.replace(':id', id);

                $('#det_nama').text('Loading...');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        // Isi data ke modal
                        $('#det_nama').text(res.karyawan ? res.karyawan.full_name : '-'); // Pastikan nama kolom karyawan benar
                        $('#det_jenis').text(res.jenis_izin);

                        // --- PERBAIKAN FORMAT TANGGAL DISINI ---
                        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

                        // Konversi string ISO ke Date Object lalu format ke Indonesia
                        let tglMulai = new Date(res.tanggal_mulai).toLocaleDateString('id-ID', options);
                        let tglSelesai = new Date(res.tanggal_selesai).toLocaleDateString('id-ID', options);

                        $('#det_tgl_mulai').text(tglMulai);
                        $('#det_tgl_selesai').text(tglSelesai);
                        // ----------------------------------------

                        $('#det_keterangan').text(res.keterangan || '-');
                        $('#det_status').text(res.status);
                        $('#det_catatan').text(res.catatan_approver || '-');

                        // Handle File (Sama seperti sebelumnya)
                        if(res.file_pendukung) {
                            let link = `{{ asset('storage') }}/${res.file_pendukung}`;
                            $('#det_file').html(`<a href="${link}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Lihat Dokumen</a>`);
                        } else {
                            $('#det_file').text('Tidak ada file');
                        }

                        $('#modalDetail').modal('show');
                    },
                    error: function() {
                        alert('Gagal mengambil data detail');
                    }
                });
            });

            // --- 2. PERSIAPAN MODAL APPROVE ---
            $(document).on('click', '.approve-btn', function() {
                const id = $(this).data('id');
                $('#verifikasiId').val(id);
                $('#verifikasiType').val('approve'); // Set tipe

                // Atur Tampilan Modal jadi Hijau/Positif
                $('#verifikasiTitle').text('Setujui Pengajuan');
                $('#verifikasiText').text('Apakah Anda yakin ingin MENYETUJUI pengajuan izin ini?');
                $('#btnSubmitVerifikasi').removeClass('btn-danger').addClass('btn-success').text('Ya, Setujui');
                $('#catatan_approver').val(''); // Reset catatan

                $('#modalVerifikasi').modal('show');
            });

            // --- 3. PERSIAPAN MODAL REJECT ---
            $(document).on('click', '.reject-btn', function() {
                const id = $(this).data('id');
                $('#verifikasiId').val(id);
                $('#verifikasiType').val('reject'); // Set tipe

                // Atur Tampilan Modal jadi Merah/Negatif
                $('#verifikasiTitle').text('Tolak Pengajuan');
                $('#verifikasiText').text('Apakah Anda yakin ingin MENOLAK pengajuan izin ini?');
                $('#btnSubmitVerifikasi').removeClass('btn-success').addClass('btn-danger').text('Ya, Tolak');
                $('#catatan_approver').val('');

                $('#modalVerifikasi').modal('show');
            });

            // --- 4. EKSEKUSI APPROVE / REJECT ---
            $('#formVerifikasi').on('submit', function(e) {
                e.preventDefault();

                const id = $('#verifikasiId').val();
                const type = $('#verifikasiType').val();
                const catatan = $('#catatan_approver').val();

                // Validasi sederhana untuk Reject
                if(type === 'reject' && !catatan) {
                    alert('Untuk penolakan, catatan wajib diisi!');
                    return;
                }

                // Tentukan URL berdasarkan tipe
                let url = '';
                if(type === 'approve') {
                    url = '{{ route("presensi.presensi-izin.approve", ":id") }}'.replace(':id', id);
                } else {
                    url = '{{ route("presensi.presensi-izin.reject", ":id") }}'.replace(':id', id);
                }

                // Tombol loading
                let btn = $('#btnSubmitVerifikasi');
                let btnOldText = btn.text();
                btn.text('Proses...').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        catatan_approver: catatan
                    },
                    success: function(res) {
                        $('#modalVerifikasi').modal('hide');
                        table.ajax.reload(); // Reload tabel

                        // Gunakan SweetAlert jika ada, atau alert biasa
                        if(typeof Swal !== 'undefined') {
                            Swal.fire('Sukses', res.message, 'success');
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan sistem.');
                        console.log(xhr);
                    },
                    complete: function() {
                        btn.text(btnOldText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection

