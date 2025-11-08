@extends('layouts.master')
@section('title', 'Role')
@section('PageTitle', 'Create Role')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.role.index') }}">Role</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Crete a New Role</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.role.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Role/Jabatan</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Input Nama Role/Jabatan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="departemen">Departemen</label>
                                <select class="form-control select2" name="departemen" id="departemen">
                                    <option></option>
                                    <option value="na">Non-Departemen</option>
                                    @foreach ($departemen as $item)
                                        <option value="{{ $item->id_departemen }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Select URLs</label>
                            <div class="row">
                                @foreach ($routes as $routeName => $route)
                                    @if (
                                        !str_starts_with($routeName, 'admin.') &&
                                            !str_starts_with($routeName, 'v1.') &&
                                            !str_starts_with($routeName, 'livewire.'))
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-group clearfix p-0 m-0">
                                                <div class="icheck-primary d-inline form-check">
                                                    <input type="checkbox" name="urls[]"
                                                    value="{{ $routeName }}"
                                                    class="form-check-input route-checkbox my-1"
                                                    id="route_{{ $loop->index }}" checked readonly
                                                    onclick="return false;">
                                                    <label for="route_{{ $loop->index }}">
                                                        {{ $routeName }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <hr/>
                            {{-- Main Fitur Aplikasi --}}
                            <h4 class="mb-3">Main Fitur</h4>
                            @php
                                $adminRoutes = [];
                                $groupLabels = [
                                    'dashboard' => 'Dashboard',
                                    'pes' => 'Project Execution Sheet',
                                    'service' => 'Service Menu',
                                    'departemen' => 'Manage Departemen',
                                    'role' => 'Manage Role/Jabatan',
                                    'permission' => 'Manage Role Permission',
                                    'auditTrail' => 'Audit Trail',
                                    'contact' => 'Contact',
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
                                        <div class="form-group clearfix p-0 m-0">
                                            <div class="icheck-primary d-inline form-check">
                                                <input type="checkbox" class="check-group"
                                                id="check_group_{{ $group }}"
                                                data-group="{{ $group }}">
                                                <label for="check_group_{{ $group }}">
                                                    {{ $getLabel($group) }}
                                                    <span class="badge ms-2 badge-status badge-{{ $group }}">Not Actived</span>
                                                </label>
                                            </div>
                                        </div>
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
                            <h4 class="pt-3 mb-3">Admin Fitur</h4>
                            @php
                                $adminRoutes = [];
                                $groupLabels = [
                                    'settings' => 'System Settings',
                                ];
                                $hiddenGroups = [];

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
                                        <div class="form-group clearfix p-0 m-0">
                                            <div class="icheck-primary d-inline form-check">
                                                <input type="checkbox" class="check-group"
                                                id="check_group_{{ $group }}"
                                                data-group="{{ $group }}">
                                                <label for="check_group_{{ $group }}">
                                                    {{ $getLabel($group) }}
                                                    <span class="badge ms-2 badge-status badge-{{ $group }}">Not Actived</span>
                                                </label>
                                            </div>
                                        </div>
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
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.role.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Departemen',
        })
    });
</script>
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
