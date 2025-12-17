@php
    $relation = optional(optional(auth()->user()->hasRole)->role)->permission;
@endphp
<!-- Brand Logo -->
<a href="{{ route('v1.dashboard') }}" class="brand-link">
    {{-- <div class="row text-center justify-content-center">
        <img src="{{ asset('dist/img/sq-logo.jpg') }}" alt="AdminLTE Logo" class="brand-image image-circle elevation-3"
        style="opacity: .8;">
    </div> --}}
    <div class="row text-center justify-content-center">
        <span class="brand-text font-weight-bold mr-1"><b style="color: plum;">SERTCO</b></span>
        <span class="brand-text font-weight-light mr-1">Integrated</span>
        <span class="brand-text font-weight-light">System</span>
    </div>
</a>

<!-- Sidebar -->
<div class="sidebar">
    @if (auth()->user()->hasRole)
        <div class="user-panel">
            <button class="btn btn-block btn-info py-1 info"><h9><b>{{ auth()->user()->hasRole->role->name ?? 'N/A' }}</b></h9></button>
        </div>
    @else
        <button class="btn btn-block btn-danger py-1"><h9><b>Belum Ada Role</b></h9></button>
    @endif
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->fullname }}</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">NAVIGATION</li>
            <li class="nav-item">
                <a href="{{ route('v1.dashboard') }}" class="nav-link {{ request()->is('v1') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            @include('layouts.sidebar.admin')

            @include('layouts.sidebar.marketing')
            @include('layouts.sidebar.approval')

            @include('layouts.sidebar.hrga')
            @include('layouts.sidebar.presensi')

            @include('layouts.sidebar.logistik')
            @include('layouts.sidebar.hse')

            <li class="nav-header">MASTER DATA</li>
            {{-- Service --}}
            @foreach ($relation ?? [] as $item)
                @if (Str::is('v1.service.index', $item->url))
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

            {{-- Data Peralatan --}}
            @foreach ($relation ?? [] as $item)
                @if (Str::is('v1.data-peralatan.index', $item->url))
                    <li class="nav-item">
                        <a href="{{ route('v1.data-peralatan.index') }}" class="nav-link {{ request()->is('v1/data-peralatan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Data Peralatan
                            </p>
                        </a>
                    </li>
                    @break
                @endif
            @endforeach

            {{-- Jenis Sertifikat --}}
            @foreach ($relation ?? [] as $item)
                @if (Str::is('v1.jenis-sertifikat.index', $item->url))
                    <li class="nav-item">
                        <a href="{{ route('v1.jenis-sertifikat.index') }}" class="nav-link {{ request()->is('v1/jenis-sertifikat*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Jenis Sertifikat
                            </p>
                        </a>
                    </li>
                    @break
                @endif
            @endforeach

            {{-- Master Data ISO --}}
            @foreach ($relation ?? [] as $item)
                @if (Str::is('v1.master-iso.index', $item->url))
                    <li class="nav-item">
                        <a href="{{ route('v1.master-iso.index') }}" class="nav-link {{ request()->is('v1/master-iso*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                ISO
                            </p>
                        </a>
                    </li>
                    @break
                @endif
            @endforeach

            {{-- Barang --}}
            @foreach ($relation ?? [] as $item)
                @if (Str::is('v1.barang.master.index', $item->url))
                    <li class="nav-item {{ request()->is('v1/barang*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('v1/barang*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Barang
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('v1.barang.master.index') }}" class="nav-link {{ request()->is('v1/barang/master*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Barang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('v1.barang.kategori.index') }}" class="nav-link {{ request()->is('v1/barang/kategori*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kategori Barang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('v1.barang.satuan.index') }}" class="nav-link {{ request()->is('v1/barang/satuan*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Satuan Barang</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @break
                @endif
            @endforeach

            <li class="nav-header">OTHERS</li>
            <li class="nav-item">
                <a href="{{ route('v1.contact.index') }}" class="nav-link {{ request()->is('v1/contact*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-phone-alt"></i>
                    <p>
                        Contact Us
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('v1.auditTrail.index') }}" class="nav-link {{ request()->is('v1/audit*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-stream"></i>
                    <p>
                        Audit Trail
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
