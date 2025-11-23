<li class="nav-header">HSE</li>
{{-- Employee Data --}}
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

@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.matrix-personil.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.matrix-personil.index') }}" class="nav-link {{ request()->is('v1/matrix-personil*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                    Matrix Personil
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach