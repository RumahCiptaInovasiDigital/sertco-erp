<li class="nav-header">PURCHASING</li>
<!-- Suplier -->
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.suplier.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.suplier.index') }}" class="nav-link {{ request()->is('v1/suplier*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                    Data Suplier
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

<!-- Vendor -->
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.vendor.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.vendor.index') }}" class="nav-link {{ request()->is('v1/vendor*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                    Data Vendor
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

{{-- Permintaan PO Barang/Jasa --}}
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.poso-request.po.index', $item->url))
        <li class="nav-item {{ request()->is('v1/poso-request*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('v1/poso-request*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>
                    PO/SO Request
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('v1.poso-request.po.index') }}" class="nav-link {{ request()->is('v1/poso-request/po*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>PO Request</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('v1.poso-request.so.index') }}" class="nav-link {{ request()->is('v1/poso-request/so*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>SO Request</p>
                    </a>
                </li>
            </ul>
        </li>
        @break
    @endif
@endforeach

{{-- Logistik --}}
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.logistik.index', $item->url))
        <li class="nav-item {{ request()->is('v1/logistik*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('v1/logistik*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-truck"></i>
                <p>
                    Logistik
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('v1.logistik.masuk.index') }}" class="nav-link {{ request()->is('v1/logistik/masuk*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-circle-right nav-icon"></i>
                        <p>Logistik Masuk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('v1.logistik.keluar.index') }}" class="nav-link {{ request()->is('v1/logistik/keluar*') ? 'active' : '' }}">
                        <i class="fas fa-arrow-circle-left nav-icon"></i>
                        <p>Logistik Keluar</p>
                    </a>
                </li>
            </ul>
        </li>
        @break
    @endif
@endforeach
