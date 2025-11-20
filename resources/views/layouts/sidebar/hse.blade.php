<li class="nav-header">HSE</li>
{{-- Employee Data --}}
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.data-peralatan.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.data-peralatan.index') }}" class="nav-link {{ request()->is('v1/data-peralatan*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tools"></i>
                <p>
                    Data Peralatan
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.data-peminjaman.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.data-peminjaman.index') }}" class="nav-link {{ request()->is('v1/data-peminjaman*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clone"></i>
                <p>
                    Peminjaman Alat
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach