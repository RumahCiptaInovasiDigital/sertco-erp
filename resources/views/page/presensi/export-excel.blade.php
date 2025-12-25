<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resume Presensi {{ $periode }}</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
        }

        td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
        }

        td.text-left {
            text-align: left;
        }

        .green { background-color: #C6EFCE; }
        .blue { background-color: #C9DAF8; }
        .orange { background-color: #FCE5CD; }
        .red { background-color: #F4CCCC; }

        .summary {
            background-color: #E8E8E8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Resume Presensi Karyawan</h2>
    <h3 style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($periode . '-01')->format('F Y') }}</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Total Hari</th>
                <th>Total Hadir</th>
                <th>Tepat Waktu</th>
                <th>Terlambat</th>
                <th>Alpha</th>
                <th>Cuti</th>
                <th>Sakit</th>
                <th>Tugas Luar</th>
                <th>Lembur</th>
                <th>Disiplin (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalHari = 0;
                $totalHadir = 0;
                $totalGood = 0;
                $totalLate = 0;
                $totalAbsent = 0;
                $totalLeave = 0;
                $totalSick = 0;
                $totalOnduty = 0;
                $totalOvertime = 0;
                $avgDisiplin = 0;
            @endphp

            @foreach($data as $resume)
                @php
                    $hadir = $resume->total_good + $resume->total_late + $resume->total_overtime + $resume->total_onduty;
                    $persentase = 0;
                    if ($resume->total_hari > 0) {
                        $persentase = ($hadir / $resume->total_hari) * 100;
                    }

                    // Color coding
                    $colorClass = '';
                    if ($persentase >= 90) {
                        $colorClass = 'green';
                    } elseif ($persentase >= 75) {
                        $colorClass = 'blue';
                    } elseif ($persentase >= 60) {
                        $colorClass = 'orange';
                    } else {
                        $colorClass = 'red';
                    }

                    // Sum for summary
                    $totalHari += $resume->total_hari;
                    $totalHadir += $hadir;
                    $totalGood += $resume->total_good;
                    $totalLate += $resume->total_late;
                    $totalAbsent += $resume->total_absent;
                    $totalLeave += $resume->total_leave;
                    $totalSick += $resume->total_sick;
                    $totalOnduty += $resume->total_onduty;
                    $totalOvertime += $resume->total_overtime;
                    $avgDisiplin += $persentase;
                @endphp

                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $resume->karyawan->nik ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->fullName ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->departemen->name ?? '-' }}</td>
                    <td>{{ $resume->total_hari }}</td>
                    <td>{{ $hadir }}</td>
                    <td class="green">{{ $resume->total_good }}</td>
                    <td class="orange">{{ $resume->total_late }}</td>
                    <td class="red">{{ $resume->total_absent }}</td>
                    <td class="blue">{{ $resume->total_leave }}</td>
                    <td>{{ $resume->total_sick }}</td>
                    <td>{{ $resume->total_onduty }}</td>
                    <td>{{ $resume->total_overtime }}</td>
                    <td class="{{ $colorClass }}">{{ number_format($persentase, 1) }}%</td>
                </tr>
            @endforeach

            <!-- Summary Row -->
            <tr class="summary">
                <td colspan="5">TOTAL</td>
                <td>{{ $totalHari }}</td>
                <td>{{ $totalHadir }}</td>
                <td class="green">{{ $totalGood }}</td>
                <td class="orange">{{ $totalLate }}</td>
                <td class="red">{{ $totalAbsent }}</td>
                <td class="blue">{{ $totalLeave }}</td>
                <td>{{ $totalSick }}</td>
                <td>{{ $totalOnduty }}</td>
                <td>{{ $totalOvertime }}</td>
                <td>{{ number_format($avgDisiplin / $data->count(), 1) }}%</td>
            </tr>
        </tbody>
    </table>

    <br>
    <p style="font-size: 11px;">
        <strong>Keterangan:</strong><br>
        - Total Hadir: Tepat Waktu + Terlambat + Tugas Luar + Lembur<br>
        - Disiplin: Persentase kehadiran terhadap total hari<br>
        - Warna Hijau: Disiplin ≥ 90%<br>
        - Warna Biru: Disiplin ≥ 75%<br>
        - Warna Orange: Disiplin ≥ 60%<br>
        - Warna Merah: Disiplin < 60%<br>
    </p>
</body>
</html>
                    $totalTerlambat += $resume->total_terlambat;
                    $totalIzin += $resume->total_izin;
                    $totalSakit += $resume->total_sakit;
                    $totalAlpha += $resume->total_alpha;
                @endphp

                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $resume->karyawan->nik ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->fullName ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->namaJabatan ?? '-' }}</td>
                    <td class="text-left">{{ $resume->karyawan->namaDepartemen ?? '-' }}</td>
                    <td>{{ $resume->total_hari }}</td>
                    <td>{{ $resume->total_presensi }}</td>
                    <td>{{ $resume->total_tepat_waktu }}</td>
                    <td>{{ $resume->total_terlambat }}</td>
                    <td>{{ $resume->total_izin }}</td>
                    <td>{{ $resume->total_sakit }}</td>
                    <td>{{ $resume->total_alpha }}</td>
                    <td class="{{ $colorClass }}">{{ number_format($persentase, 2) }}%</td>
                </tr>
            @endforeach

            @php
                if ($data->count() > 0) {
                    $avgDisiplin = ($totalTepatWaktu / $totalHari) * 100;
                }
            @endphp

            <!-- Summary Row -->
            <tr class="summary">
                <td colspan="5">TOTAL</td>
                <td>{{ $totalHari }}</td>
                <td>{{ $totalHadir }}</td>
                <td>{{ $totalTepatWaktu }}</td>
                <td>{{ $totalTerlambat }}</td>
                <td>{{ $totalIzin }}</td>
                <td>{{ $totalSakit }}</td>
                <td>{{ $totalAlpha }}</td>
                <td>{{ number_format($avgDisiplin, 2) }}%</td>
            </tr>
        </tbody>
    </table>

    <br>
    <p style="font-size: 10px; color: #666;">
        <strong>Keterangan Warna:</strong><br>
        <span style="background-color: #C6EFCE; padding: 2px 5px;">Hijau</span>: Disiplin ≥ 90% (Sangat Baik)<br>
        <span style="background-color: #C9DAF8; padding: 2px 5px;">Biru</span>: Disiplin 75-89% (Baik)<br>
        <span style="background-color: #FCE5CD; padding: 2px 5px;">Orange</span>: Disiplin 60-74% (Cukup)<br>
        <span style="background-color: #F4CCCC; padding: 2px 5px;">Merah</span>: Disiplin < 60% (Perlu Perbaikan)
    </p>

    <p style="font-size: 10px; color: #666;">
        Digenerate pada: {{ now()->format('d/m/Y H:i:s') }}
    </p>
</body>
</html>

