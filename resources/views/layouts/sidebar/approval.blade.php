<li class="nav-header">Approval</li>
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.pes.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.approval.pes.index') }}" class="nav-link {{ request()->is('v1/approval/pes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>
                    PES Approval (T&O)
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

{{-- @foreach ($relation ?? [] as $item)
    @if (Str::is('v1.logistik*', $item->url)) --}}
        {{-- <li class="nav-item menu-open">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>
                    Approval Peminjaman
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Alat</p>
                    </a>
                </li>
            </ul>
        </li> --}}
        {{-- @break
    @endif
@endforeach --}}