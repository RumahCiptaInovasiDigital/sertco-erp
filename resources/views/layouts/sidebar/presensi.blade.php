@php

    $listmenu = [
        'presensi.dashboard' => 'Dashboard Presensi',
        // Routes that point to @index only
        // 'presensi.device.index' => 'Manajemen Perangkat',
        // 'presensi.device.approval' => 'Approval Perangkat',
        // 'presensi.informasi.index' => 'Informasi',
        // 'presensi.master.jadwal-karyawan' => 'Jadwal Karyawan',
        // 'presensi.master.jadwal-kerja' => 'Jadwal Kerja',
        // 'presensi.master.kalender-kerja' => 'Kalender Kerja',
        // 'presensi.master.kantor' => 'Kantor',
        // 'presensi.master.jenis-kerja' => 'Jenis Kerja',
        // 'presensi.master.shift-kerja' => 'Shift Kerja',
        // 'presensi.master.departemen' => 'Departemen',
        // 'presensi.presensi.monitoring' => 'Monitoring Presensi',
        // 'presensi.presensi-manual.index' => 'Presensi Manual',
        // 'presensi.presensi-izin.index' => 'Izin & Cuti',
        // 'presensi.resume-presensi.index' => 'Resume Presensi',
    ];
    $keymenu = array_keys($listmenu);
@endphp

<li class="nav-header">PRESENSI</li>
{{-- Employee Data --}}
@foreach ($relation ?? [] as $item)

    @if ( in_array($item->url, $keymenu ) )
        @php
            $path = route($item->url);
            $path = substr($path, strlen(url('/')) + 1);
        @endphp
        <li class="nav-item">
            <a href="{{ route($item->url) }}" class="nav-link {{ request()->is( $path . '*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>
                    {{ $listmenu[$item->url] }}
                </p>
            </a>
        </li>
    @endif
@endforeach
