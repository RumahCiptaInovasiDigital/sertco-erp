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

@php
use App\Models\MasterIso;

$isoList = MasterIso::orderBy('name')->get();

@endphp
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.master-iso*', $item->url))
        <li class="nav-item {{ request()->is('v1/master-iso*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('v1/master-iso*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-archive"></i>
                <p>
                    List ISO
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @foreach ($isoList as $data )                
                <li class="nav-item">
                    <a href="{{ route('v1.master-iso.show', $data->id) }}" class="nav-link {{ request()->is('v1/master-iso/show/'.$data->id) ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $data->name }}</p>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        @break
    @endif
@endforeach