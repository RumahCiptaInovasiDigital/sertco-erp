<li class="nav-header">HRGA IT</li>
{{-- Employee Data --}}
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.data-karyawan.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.data-karyawan.index') }}" class="nav-link {{ request()->is('v1/data-karyawan*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Data Karyawan
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.departemen.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.departemen.index') }}" class="nav-link {{ request()->is('v1/departemen*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tags"></i>
                <p>
                    Departemen
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.role.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.role.index') }}" class="nav-link {{ request()->is('v1/role*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-tag"></i>
                <p>
                    Role/Jabatan
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach