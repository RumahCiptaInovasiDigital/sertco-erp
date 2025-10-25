<li class="nav-header">HRGA IT</li>
{{-- Service --}}
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.service*', $item->url))
        <li class="nav-item {{ request()->is('v1/service*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('v1/service*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>
                    Service
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('v1.service.kategori.index') }}" class="nav-link {{ request()->is('v1/service/kategori*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Kategori</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('v1.service.type.index') }}" class="nav-link {{ request()->is('v1/service/type*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Service Type</p>
                    </a>
                </li>
            </ul>
        </li>
        @break
    @endif
@endforeach