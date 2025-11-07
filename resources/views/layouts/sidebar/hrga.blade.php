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