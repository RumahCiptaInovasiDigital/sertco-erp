@extends('layouts.master')
@section('title', 'Detail Resume Presensi')
@section('PageTitle', 'Detail Resume Presensi')

@section('content')
    <style>
        /* Info Card */
        .info-card {
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            margin-bottom: 15px;
        }

        .info-card .card-body {
            padding: 1rem;
        }

        /* Stat Cards Compact */
        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,.15);
        }

        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            margin-bottom: 10px;
        }

        .small-box > .inner {
            padding: 10px;
        }

        .small-box h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }

        .small-box p {
            font-size: 0.85rem;
            margin: 5px 0 0 0;
        }

        .small-box .icon {
            font-size: 60px;
        }

        /* Info Box Compact */
        .info-box {
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            border-radius: 0.25rem;
            min-height: 70px;
            margin-bottom: 10px;
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

        /* Badge Compact */
        .badge-xl {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .badge {
            padding: 0.3em 0.55em;
            font-weight: 500;
        }

        /* Table Compact */
        #detailTable {
            font-size: 0.85rem;
        }

        #detailTable thead th {
            font-weight: 600;
            font-size: 0.8rem;
            vertical-align: middle;
            padding: 8px 4px;
            line-height: 1.2;
        }

        #detailTable tbody td {
            vertical-align: middle;
            padding: 6px 4px;
        }

        /* Card Compact */
        .card {
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
            margin-bottom: 15px;
        }

        .card-body {
            padding: 1rem;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .btn, .card-tools, .main-sidebar, .main-header, .content-header {
                display: none !important;
            }

            .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: 1px solid #dee2e6;
                box-shadow: none;
                page-break-inside: avoid;
            }

            .card-header {
                background-color: #007bff !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table {
                font-size: 10pt;
            }

            th {
                background-color: #007bff !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                font-size: 11pt;
            }

            .small-box, .info-box {
                page-break-inside: avoid;
            }
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <!-- Action Buttons -->
            <div class="row mb-3 no-print">
                <div class="col-12">
                    <a href="{{ route('presensi.resume-presensi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i>Cetak Laporan
                    </button>
                </div>
            </div>

            <!-- Info Karyawan -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card info-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-3">
                                        <i class="fas fa-user-circle text-primary mr-2"></i>
                                        <strong>{{ $resume->karyawan->fullName ?? 'N/A' }}</strong>
                                    </h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-2">
                                                <i class="fas fa-id-card text-muted mr-2"></i>
                                                <strong>NIK:</strong> {{ $resume->karyawan->nik ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-2">
                                                <i class="fas fa-briefcase text-muted mr-2"></i>
                                                <strong>Jabatan:</strong> {{ $resume->karyawan->jabatan->nama_jabatan ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-2">
                                                <i class="fas fa-building text-muted mr-2"></i>
                                                <strong>Departemen:</strong> {{ $resume->karyawan->departemen->name ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <h5 class="text-muted mb-1">Periode</h5>
                                    <h3 class="text-primary">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        {{ \Carbon\Carbon::parse($periode . '-01')->format('F Y') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_hari }}</h3>
                            <p>Total Hari</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_good + $resume->total_late + $resume->total_overtime + $resume->total_onduty }}</h3>
                            <p>Total Hadir</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_good }}</h3>
                            <p>Tepat Waktu</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_late }}</h3>
                            <p>Terlambat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_absent }}</h3>
                            <p>Alpha</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary stat-card">
                        <div class="inner">
                            <h3>{{ $resume->total_leave + $resume->total_sick }}</h3>
                            <p>Cuti/Sakit</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards Row 2 -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-suitcase"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tugas Luar</span>
                            <span class="info-box-number">{{ $resume->total_onduty }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-dark">
                        <span class="info-box-icon"><i class="fas fa-business-time"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Lembur</span>
                            <span class="info-box-number">{{ $resume->total_overtime }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum Lengkap</span>
                            <span class="info-box-number">{{ $resume->total_uncompleted }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tingkat Kehadiran</span>
                            <span class="info-box-number">
                                @php
                                    $totalHadir = $resume->total_good + $resume->total_late + $resume->total_overtime + $resume->total_onduty;
                                    $persentase = $resume->total_hari > 0 ? ($totalHadir / $resume->total_hari) * 100 : 0;
                                @endphp
                                {{ number_format($persentase, 1) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Presensi Harian -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-list-alt mr-2"></i>Detail Presensi Harian
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" id="detailTable" style="width: 100%;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 40px;" class="text-center">No</th>
                                            <th style="width: 120px;">Tanggal</th>
                                            <th style="width: 80px;" class="text-center">Hari</th>
                                            <th style="width: 100px;" class="text-center">Jam Masuk</th>
                                            <th style="width: 100px;" class="text-center">Jam Pulang</th>
                                            <th style="width: 100px;" class="text-center">Total Jam</th>
                                            <th style="width: 100px;" class="text-center">Tipe</th>
                                            <th style="width: 150px;">Lokasi</th>
                                            <th style="width: 120px;" class="text-center">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($presensis as $index => $presensi)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-secondary">
                                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('dddd') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($presensi->jam_masuk)
                                                        <span class="badge badge-{{ $presensi->status == 'late' ? 'warning' : 'info' }} badge-xl">
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($presensi->jam_pulang)
                                                        <span class="badge badge-info badge-xl">
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($presensi->total_jam_kerja)
                                                        <span class="badge badge-primary">
                                                            {{ number_format($presensi->total_jam_kerja, 1) }} jam
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $presensi->type_presensi == 'WFO' ? 'success' : 'danger' }}">
                                                        {{ $presensi->type_presensi ?? 'WFO' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>
                                                        @if($presensi->originOfficeMasuk || $presensi->originOfficePulang)
                                                            @if($presensi->originOfficeMasuk && $presensi->originOfficePulang)
                                                                @if($presensi->originOfficeMasuk->id == $presensi->originOfficePulang->id)
                                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                                    {{ $presensi->originOfficeMasuk->name }}
                                                                @else
                                                                    <i class="fas fa-sign-in-alt text-success"></i>
                                                                    {{ $presensi->originOfficeMasuk->name }}<br>
                                                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                                                    {{ $presensi->originOfficePulang->name }}
                                                                @endif
                                                            @elseif($presensi->originOfficeMasuk)
                                                                <i class="fas fa-sign-in-alt text-success"></i>
                                                                {{ $presensi->originOfficeMasuk->name }}
                                                            @elseif($presensi->originOfficePulang)
                                                                <i class="fas fa-sign-out-alt text-danger"></i>
                                                                {{ $presensi->originOfficePulang->name }}
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusConfig = [
                                                            'good' => ['label' => 'Tepat Waktu', 'class' => 'success', 'icon' => 'check-circle'],
                                                            'late' => ['label' => 'Terlambat', 'class' => 'warning', 'icon' => 'clock'],
                                                            'uncompleted' => ['label' => 'Belum Lengkap', 'class' => 'warning', 'icon' => 'exclamation-circle'],
                                                            'leave' => ['label' => 'Cuti', 'class' => 'info', 'icon' => 'calendar-check'],
                                                            'sick' => ['label' => 'Sakit', 'class' => 'info', 'icon' => 'file-medical'],
                                                            'absent' => ['label' => 'Alpha', 'class' => 'danger', 'icon' => 'times-circle'],
                                                            'overtime' => ['label' => 'Lembur', 'class' => 'primary', 'icon' => 'business-time'],
                                                            'onduty' => ['label' => 'Tugas Luar', 'class' => 'primary', 'icon' => 'suitcase'],
                                                        ];
                                                        $config = $statusConfig[$presensi->status] ?? ['label' => $presensi->status ?? 'N/A', 'class' => 'secondary', 'icon' => 'question'];
                                                    @endphp
                                                    <span class="badge badge-{{ $config['class'] }}">
                                                        <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                                        {{ $config['label'] }}
                                                    </span>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                    Tidak ada data presensi untuk periode ini
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
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
    <script>
        $(document).ready(function() {
            $('#detailTable').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                pageLength: 31,
                order: [[1, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
@endsection
