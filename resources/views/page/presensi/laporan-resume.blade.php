@extends('layouts.master')
@section('title', 'Laporan Resume Presensi')
@section('PageTitle', 'Laporan Resume Presensi')
@section('head')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('content')
    <style>
        /* Info Box Compact */
        .info-box {
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            border-radius: 0.25rem;
            transition: transform 0.2s;
            min-height: 70px;
            margin-bottom: 10px;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.15);
        }

        .info-box .info-box-icon {
            width: 70px;
            font-size: 1.8rem;
        }

        .info-box-text {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .info-box-number {
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* Table Enhancement */
        #resumeTable {
            font-size: 0.85rem;
        }

        #resumeTable thead th {
            font-weight: 600;
            font-size: 0.8rem;
            vertical-align: middle;
            padding: 8px 4px;
            line-height: 1.2;
        }

        #resumeTable tbody td {
            vertical-align: middle;
            padding: 6px 4px;
        }

        /* Progress Bar Compact */
        .progress {
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
            height: 18px;
            margin-bottom: 2px;
        }

        .progress-bar {
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 18px;
        }

        /* Badge Compact */
        .badge {
            padding: 0.3em 0.55em;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* Button Group Enhancement */
        .btn-group .btn {
            margin: 0;
        }

        /* Card Compact */
        .card {
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            margin-bottom: 15px;
        }

        .card-outline {
            border-top: 3px solid #007bff;
        }

        .card-body {
            padding: 1rem;
        }

        /* Filter Section Compact */
        .filter-card .card-body {
            padding: 0.75rem 1rem;
        }

        /* Button Styling */
        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .btn i {
            font-size: 0.875rem;
        }
    </style>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary filter-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="mb-1" style="font-size: 0.85rem; font-weight: 600;">
                                            <i class="far fa-calendar-alt mr-1"></i>Pilih Periode
                                        </label>
                                        <select class="form-control select2" id="filterPeriode" name="periode">
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

                                <div class="col-md-8 text-md-right mt-2 mt-md-0">
                                    <button type="button" id="btnSync" class="btn btn-info">
                                        <i class="fas fa-sync-alt mr-1"></i> Sinkronkan
                                    </button>
                                    <button type="button" id="btnExport" class="btn btn-success">
                                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Karyawan</span>
                            <span class="info-box-number" id="sum_karyawan">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tepat Waktu</span>
                            <span class="info-box-number" id="sum_good">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Terlambat</span>
                            <span class="info-box-number" id="sum_late">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tidak Hadir</span>
                            <span class="info-box-number" id="sum_absent">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar-times"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Cuti</span>
                            <span class="info-box-number" id="sum_leave">0</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-briefcase-medical"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sakit</span>
                            <span class="info-box-number" id="sum_sick">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-2 pb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" id="resumeTable" style="width: 100%;">
                                    <thead class="bg-primary text-white">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th style="width: 100px;">NIK</th>
                                        <th style="min-width: 180px;">Nama Karyawan</th>
                                        <th style="width: 60px;" class="text-center">Total<br>Hari</th>
                                        <th style="width: 60px;" class="text-center">Hadir</th>
                                        <th style="width: 60px;" class="text-center">Tepat<br>Waktu</th>
                                        <th style="width: 60px;" class="text-center">Telat</th>
                                        <th style="width: 60px;" class="text-center">Alpha</th>
                                        <th style="width: 60px;" class="text-center">Cuti</th>
                                        <th style="width: 60px;" class="text-center">Sakit</th>
                                        <th style="width: 60px;" class="text-center">Tugas<br>Luar</th>
                                        <th style="width: 60px;" class="text-center">Lembur</th>
                                        <th style="width: 120px;" class="text-center">Disiplin</th>
                                        <th style="width: 100px;" class="text-center">Aksi</th>
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
@endsection

@section('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Init Select2
            $('#filterPeriode').select2({ theme: 'bootstrap4' });

            // 1. Inisialisasi DataTable
            let table = $('#resumeTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,    // MENONAKTIFKAN SORTING (Header tidak bisa diklik untuk ubah urutan)
                scrollX: false,     // MENONAKTIFKAN SCROLL SAMPING (Tabel statis sesuai lebar layar)
                autoWidth: false,   // Membiarkan Bootstrap mengatur lebar
                // ------------------------
                ajax: {
                    url: "{{ route('presensi.resume-presensi.index') }}",
                    data: function(d) {
                        d.periode = $('#filterPeriode').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    {
                        data: 'nik',
                        name: 'karyawan.nik',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: 'nama_karyawan',
                        name: 'karyawan.fullName',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: 'total_hari',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-secondary">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_hadir',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-info">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_good',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>${data}</span>`;
                        }
                    },
                    {
                        data: 'total_late',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>${data}</span>`;
                        }
                    },
                    {
                        data: 'total_absent',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i>${data}</span>`;
                        }
                    },
                    {
                        data: 'total_leave',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-primary">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_sick',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-secondary">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_onduty',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-info">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_overtime',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-dark">${data}</span>`;
                        }
                    },
                    {
                        data: 'persentase',
                        className: 'text-center',
                        render: function(data) {
                            let persen = parseFloat(data).toFixed(1);
                            let color = 'danger';
                            let icon = 'fa-times-circle';

                            if(persen >= 90) {
                                color = 'success';
                                icon = 'fa-check-circle';
                            } else if(persen >= 75) {
                                color = 'primary';
                                icon = 'fa-info-circle';
                            } else if(persen >= 60) {
                                color = 'warning';
                                icon = 'fa-exclamation-circle';
                            }

                            return `
                                <div class="progress">
                                    <div class="progress-bar bg-${color}" role="progressbar" style="width: ${persen}%">
                                        ${persen}%
                                    </div>
                                </div>
                                <small class="text-muted"><i class="fas ${icon} text-${color}"></i></small>
                            `;
                        }
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function(settings) {
                    // Update Summary Cards Otomatis saat tabel direload
                    let json = settings.json;
                    if(json && json.summary) {
                        $('#sum_karyawan').text(json.summary.total_karyawan || 0);
                        $('#sum_good').text(json.summary.total_good || 0);
                        $('#sum_late').text(json.summary.total_late || 0);
                        $('#sum_absent').text(json.summary.total_absent || 0);
                        $('#sum_leave').text(json.summary.total_leave || 0);
                        $('#sum_sick').text(json.summary.total_sick || 0);
                    }
                }
            });

            // --- PERUBAHAN UTAMA DISINI ---
            // 2. Auto Reload saat Select diganti
            $('#filterPeriode').on('change', function() {
                table.ajax.reload(); // Reload tabel (dan summary via drawCallback)
            });
            // ------------------------------

            // 3. Event Tombol Export
            $('#btnExport').click(function() {
                let periode = $('#filterPeriode').val();
                let url = "{{ route('presensi.resume-presensi.export') }}?periode=" + periode;
                window.location.href = url;
            });

            // 4. Event Tombol Sinkronkan
            $('#btnSync').click(function(e) {
                e.preventDefault();
                let periode = $('#filterPeriode').val();

                Swal.fire({
                    title: 'Sinkronisasi Data?',
                    text: "Hitung ulang rekap presensi periode " + periode + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Sinkronkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menghitung ulang data...',
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: "{{ route('presensi.resume-presensi.sync') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                periode: periode
                            },
                            success: function(response) {
                                Swal.fire('Sukses', response.message, 'success');
                                table.ajax.reload(); // Reload tabel otomatis setelah sync
                            },
                            error: function(xhr) {
                                let errorMsg = 'Gagal sinkronisasi';
                                if(xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Error', errorMsg, 'error');
                            }
                        });
                    }
                });
            });

            // 5. Event Tombol Print (per karyawan)
            $(document).on('click', '.btn-print', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const periode = $('#filterPeriode').val();

                Swal.fire({
                    title: 'Cetak Laporan',
                    html: `Cetak laporan resume presensi untuk:<br><strong>${nama}</strong><br>Periode: <strong>${periode}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-print"></i> Cetak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#007bff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buka window baru untuk print
                        window.open(`{{ url('presensi/presensi/resume/print') }}/${id}?periode=${periode}`, '_blank');
                    }
                });
            });
        });
    </script>
@endsection
