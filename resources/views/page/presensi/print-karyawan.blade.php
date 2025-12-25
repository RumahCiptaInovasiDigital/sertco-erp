<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resume Presensi - {{ $resume->karyawan->fullName ?? 'N/A' }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        .header h2 {
            margin: 5px 0;
            color: #007bff;
        }

        .header h3 {
            margin: 5px 0;
            color: #666;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 5px;
            vertical-align: top;
        }

        .info-box td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-card {
            flex: 1;
            min-width: 150px;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 5px;
            text-align: center;
        }

        .summary-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .summary-card.yellow { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
        .summary-card.red { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .summary-card.blue { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .summary-card h3 {
            margin: 0;
            font-size: 24pt;
        }

        .summary-card p {
            margin: 5px 0 0 0;
            font-size: 9pt;
            opacity: 0.9;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }

        table.data-table th {
            background-color: #007bff;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #0056b3;
            font-weight: bold;
        }

        table.data-table td {
            padding: 6px;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h2>LAPORAN RESUME PRESENSI KARYAWAN</h2>
        <h3>Periode: {{ \Carbon\Carbon::parse($periode . '-01')->format('F Y') }}</h3>
    </div>

    <!-- Info Karyawan -->
    <div class="info-box">
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $resume->karyawan->fullName ?? 'N/A' }}</td>
                <td>NIK</td>
                <td>: {{ $resume->karyawan->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $resume->karyawan->namaJabatan ?? '-' }}</td>
                <td>Departemen</td>
                <td>: {{ $resume->karyawan->namaDepartemen ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card blue">
            <h3>{{ $resume->total_hari }}</h3>
            <p>Total Hari</p>
        </div>
        <div class="summary-card green">
            <h3>{{ $resume->total_tepat_waktu }}</h3>
            <p>Tepat Waktu</p>
        </div>
        <div class="summary-card yellow">
            <h3>{{ $resume->total_terlambat }}</h3>
            <p>Terlambat</p>
        </div>
        <div class="summary-card red">
            <h3>{{ $resume->total_alpha }}</h3>
            <p>Alpha</p>
        </div>
    </div>

    <!-- Detail Presensi -->
    <h3 style="margin-top: 30px; color: #007bff;">Detail Presensi Harian</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 60px;">Hari</th>
                <th style="width: 70px;">Jam Masuk</th>
                <th style="width: 70px;">Jam Pulang</th>
                <th style="width: 60px;">Total Jam</th>
                <th style="width: 50px;">Tipe</th>
                <th>Lokasi</th>
                <th style="width: 100px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensis as $index => $presensi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->locale('id')->isoFormat('ddd') }}</td>
                    <td>
                        @if($presensi->jam_masuk)
                            {{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($presensi->jam_pulang)
                            {{ \Carbon\Carbon::parse($presensi->jam_pulang)->format('H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($presensi->total_jam_kerja)
                            {{ number_format($presensi->total_jam_kerja, 1) }}j
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $presensi->type_presensi == 'WFO' ? 'success' : 'danger' }}">
                            {{ $presensi->type_presensi ?? 'WFO' }}
                        </span>
                    </td>
                    <td style="font-size: 8pt; text-align: left;">
                        @if($presensi->originOfficeMasuk && $presensi->originOfficePulang)
                            @if($presensi->originOfficeMasuk->id == $presensi->originOfficePulang->id)
                                {{ $presensi->originOfficeMasuk->name }}
                            @else
                                In: {{ $presensi->originOfficeMasuk->name }}<br>
                                Out: {{ $presensi->originOfficePulang->name }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $statusConfig = [
                                'good' => ['label' => 'Tepat Waktu', 'class' => 'success'],
                                'late' => ['label' => 'Terlambat', 'class' => 'warning'],
                                'uncompleted' => ['label' => 'Belum Lengkap', 'class' => 'warning'],
                                'leave' => ['label' => 'Cuti', 'class' => 'info'],
                                'sick' => ['label' => 'Sakit', 'class' => 'info'],
                                'absent' => ['label' => 'Alpha', 'class' => 'danger'],
                                'overtime' => ['label' => 'Lembur', 'class' => 'info'],
                                'onduty' => ['label' => 'Tugas Luar', 'class' => 'info'],
                            ];
                            $config = $statusConfig[$presensi->status] ?? ['label' => $presensi->status ?? 'N/A', 'class' => 'secondary'];
                        @endphp
                        <span class="badge badge-{{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #999;">
                        Tidak ada data presensi untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}<br>
            Laporan ini digenerate otomatis oleh sistem Sertco Quality
        </p>
    </div>
</body>
</html>

