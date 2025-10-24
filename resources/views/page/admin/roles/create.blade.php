@extends('layout.master')
@section('title')
    Create Permission Manage
@endsection
@section('main-content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Add Roles Manage
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="#" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Admin</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Add Roles Manage</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-12 mb-0 py-0">
                                        <h5 class="my-0">Add Role Manage</h5>
                                    </div>
                                    <div class="col-12 my-0 py-0">
                                        <span class="fw-light fs-8">Manager Your Role</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content">
                                <form action="{{ route('admin.roles.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label for="jobLvl" class="form-label">Job Level</label>
                                            <div class="form-check">
                                                <input type="checkbox" id="checkAll" class="form-check-input">
                                                <label class="form-check-label" for="checkAll">Check All</label>
                                            </div>
                                        </div>
                                        <input type="text" name="jobLvl" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select URLs</label>
                                        <div class="row">
                                            @foreach ($routes as $routeName => $route)
                                                @if (
                                                    !str_starts_with($routeName, 'admin.') &&
                                                        !str_starts_with($routeName, 'v1.') &&
                                                        !str_starts_with($routeName, 'livewire.'))
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="urls[]"
                                                                value="{{ $routeName }}"
                                                                class="form-check-input route-checkbox my-1"
                                                                id="route_{{ $loop->index }}" checked readonly
                                                                onclick="return false;">
                                                            <label class="form-check-label my-1 text-gray-700"
                                                                for="route_{{ $loop->index }}">{{ $routeName }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        {{-- Main Fitur Aplikasi --}}
                                        <label class="form-label fs-4 fw-bold mt-10">Main Fitur</label>
                                        @php
                                            $adminRoutes = [];
                                            $groupLabels = [
                                                'dashboard' => 'Home',
                                                'form' => 'Formulir Pengukuran',
                                                'monitoring' => 'Monitoring Pengukuran Suhu',
                                            ];
                                            $hiddenGroups = [];

                                            foreach ($routes as $routeName => $route) {
                                                if (str_starts_with($routeName, 'v1.')) {
                                                    $parts = explode('.', $routeName);
                                                    $group = $parts[1] ?? 'others';
                                                    $adminRoutes[$group][] = $routeName;
                                                }
                                            }

                                            $getLabel = fn($group) => $groupLabels[$group] ??
                                                str_replace('_', ' ', $group);
                                        @endphp
                                        @foreach ($adminRoutes as $group => $routeNames)
                                            @if (auth()->user()->jobLvl !== 'Administrator' && in_array($group, $hiddenGroups))
                                                @continue
                                            @endif

                                            <div>
                                                <div class="d-flex justify-content-start align-items-center mb-2">
                                                    <div class="form-check ms-5 mt-5">
                                                        <input type="checkbox" class="form-check-input check-group"
                                                            id="check_group_{{ $group }}"
                                                            data-group="{{ $group }}">
                                                    </div>
                                                    <p class="mb-0 fw-semibold text-capitalize fs-5 mt-5">
                                                        {{ $getLabel($group) }}
                                                        <span class="badge ms-2 badge-status badge-{{ $group }}">Not
                                                            Actived</span>
                                                    </p>
                                                </div>

                                                <div class="row">
                                                    @foreach ($routeNames as $routeName)
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="form-check d-none">
                                                                <input type="checkbox" name="urls[]"
                                                                    value="{{ $routeName }}"
                                                                    class="form-check-input route-checkbox my-1 group-checkbox"
                                                                    data-group="{{ $group }}"
                                                                    id="route_{{ md5($routeName) }}">
                                                                <label class="form-check-label my-1 text-gray-700"
                                                                    for="route_{{ md5($routeName) }}">{{ $routeName }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach

                                        {{-- Admin Fitur --}}
                                        <label class="form-label fs-4 fw-bold mt-10">Admin Fitur</label>
                                        @php
                                            $adminRoutes = [];
                                            $groupLabels = [
                                                'permission' => 'Manage HRIS Permission Job Level',
                                                'roles' => 'Manage Roles',
                                                'user' => 'Manage User',
                                                'dept' => 'Manage Department',
                                                'subdept' => 'Manage SubDepartment',
                                                'ruang' => 'Manage Ruang / Alat',
                                                'jenis' => 'Manage Jenis Ruangan & DP',
                                                'syarat' => 'Manage Syarat Ruangan & DP',
                                                'waktu' => 'Manage Waktu Pengukuran',
                                                'library' => 'Manage CR / SOP',
                                            ];
                                            $hiddenGroups = ['permission', 'settings'];

                                            foreach ($routes as $routeName => $route) {
                                                if (str_starts_with($routeName, 'admin.')) {
                                                    $parts = explode('.', $routeName);
                                                    $group = $parts[1] ?? 'others';
                                                    $adminRoutes[$group][] = $routeName;
                                                }
                                            }

                                            $getLabel = fn($group) => $groupLabels[$group] ??
                                                str_replace('_', ' ', $group);
                                        @endphp
                                        @foreach ($adminRoutes as $group => $routeNames)
                                            @if (auth()->user()->jobLvl !== 'Administrator' && in_array($group, $hiddenGroups))
                                                @continue
                                            @endif

                                            <div>
                                                <div class="d-flex justify-content-start align-items-center mb-2">
                                                    <div class="form-check ms-5 mt-5">
                                                        <input type="checkbox" class="form-check-input check-group"
                                                            id="check_group_{{ $group }}"
                                                            data-group="{{ $group }}">
                                                    </div>
                                                    <p class="mb-0 fw-semibold text-capitalize fs-5 mt-3">
                                                        {{ $getLabel($group) }}
                                                        <span class="badge ms-2 badge-status badge-{{ $group }}">Not
                                                            Actived</span>
                                                    </p>
                                                </div>

                                                <div class="row">
                                                    @foreach ($routeNames as $routeName)
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="form-check d-none">
                                                                <input type="checkbox" name="urls[]"
                                                                    value="{{ $routeName }}"
                                                                    class="form-check-input route-checkbox my-1 group-checkbox"
                                                                    data-group="{{ $group }}"
                                                                    id="route_{{ md5($routeName) }}">
                                                                <label class="form-check-label my-1 text-gray-700"
                                                                    for="route_{{ md5($routeName) }}">{{ $routeName }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach

                                    </div>
                                    <button type="submit" class="btn btn-primary mt-10">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#checkAll').change(function() {
                $('.route-checkbox').prop('checked', this.checked);
            });

            $('.check-group').each(function() {
                const group = $(this).data('group');
                const groupCheckboxes = $(`.group-checkbox[data-group="${group}"]`);
                const allChecked = groupCheckboxes.length && groupCheckboxes.filter(':checked').length ===
                    groupCheckboxes.length;

                // Set checkbox group berdasarkan semua checkbox dalam grup
                $(this).prop('checked', allChecked);

                // Update badge setelah checkbox di-set
                const badge = $('.badge-' + group.replace(/\s+/g, '-'));
                if (allChecked) {
                    badge.removeClass('bg-secondary').addClass('bg-success').text('Actived');
                } else {
                    badge.removeClass('bg-success').addClass('bg-secondary').text('Not Actived');
                }
            });

            $('.check-group').on('change', function() {
                const group = $(this).data('group');
                const checked = $(this).is(':checked');
                const badge = $('.badge-' + group.replace(/\s+/g, '-'));
                $(`.group-checkbox[data-group="${group}"]`).prop('checked', checked);

                if (checked) {
                    badge.removeClass('bg-secondary').addClass('bg-success').text('Actived');
                } else {
                    badge.removeClass('bg-success').addClass('bg-secondary').text('Not Actived');
                }
            });

            $('.group-checkbox').on('change', function() {
                const group = $(this).data('group');
                const groupCheckboxes = $(`.group-checkbox[data-group="${group}"]`);
                const allChecked = groupCheckboxes.length && groupCheckboxes.filter(':checked').length ===
                    groupCheckboxes.length;
                const badge = $('.badge-' + group.replace(/\s+/g, '-'));

                $(`.check-group[data-group="${group}"]`).prop('checked', allChecked);

                if (allChecked) {
                    badge.removeClass('bg-secondary').addClass('bg-success').text('Actived');
                } else {
                    badge.removeClass('bg-success').addClass('bg-secondary').text('Not Actived');
                }
            });
        });
    </script>
@endsection
