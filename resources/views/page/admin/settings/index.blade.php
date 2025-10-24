@extends('layouts.master')
@section('title', 'Website Setting')
@section('PageTitle', 'Website Setting')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Website Settings</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Website Settings</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('admin.setting.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">Webiste Maintenance Mode</label>
                                <select class="form-control select2" name="maintenance_mode" id="maintenance_mode">
                                    <option value="1" {{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == 1 ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == 0 ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12" id="maintenance_reason_row" style="{{ isset($maintenanceMode->maintenance) && $maintenanceMode->maintenance == 1 ? '' : 'display:none;' }}">
                            <div class="form-group">
                                <label for="name">Maintenance Reason</label>
                                <textarea name="reason" class="form-control" placeholder="type any reason">{{ $maintenanceMode->reason ?? '' }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Idle Time</label>
                                <div class="input-group mb-3">
                                    <input type="number" min="0" class="form-control" name="idle_time" value="{{ $maintenanceMode->idle_time ?? '0' }}" id="idle_time" name="idle_time" oninput="if(this.value > 60) this.value = 60;">
                                    <div class="input-group-append">
                                      <span class="input-group-text">menit</span>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.dashboard') }}" class="btn btn-secondary">Cancel</a>
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
    $(document).ready(function () {
        const $maintenanceSelect = $('#maintenance_mode');
        const $reasonRow = $('#maintenance_reason_row');

        // Saat pertama kali load, tampilkan/hidden sesuai value awal
        if ($maintenanceSelect.val() === "1") {
            $reasonRow.show();
        } else {
            $reasonRow.hide();
        }

        // Event listener saat user ubah pilihan
        $maintenanceSelect.on('change', function () {
            if ($(this).val() === "1") {
                $reasonRow.show();
            } else {
                $reasonRow.hide();
            }
        });
    });
</script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Departemen',
        })
    });
</script>
@endsection
