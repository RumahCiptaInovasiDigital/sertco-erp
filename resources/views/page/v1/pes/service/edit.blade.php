@extends('layouts.master')
@section('title', 'Edit Service Data')
@section('PageTitle', 'Project Execution Sheet')
@section('head')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.pes.index') }}">Project Execution Sheet</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Project Execution Sheet</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Project Information</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Prepared by</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->karyawan->fullname ?? null }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Issued Date</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->issued_date }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Project No.</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->project_no }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->project_sheet_detail->client ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Owner</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->project_sheet_detail->owner ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Contract/SO/LOI/WA No.</label>
                                            <input type="text" class="form-control" value="{{ $projectSheet->project_sheet_detail->contract_no ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person</label>
                                            <input type="text" class="form-control" placeholder="Input Contact Person" value="{{ $projectSheet->project_sheet_detail->contact_person ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="ph_no">Ph.</label>
                                                    <input type="text" class="form-control" placeholder="Input Ph." value="{{ $projectSheet->project_sheet_detail->ph_no ?? 'NA' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="fax_no">Fax</label>
                                                    <input type="text" class="form-control" placeholder="Input Fax No." value="{{ $projectSheet->project_sheet_detail->fax_no ?? 'NA' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="hp_no">Hp.</label>
                                                    <input type="text" class="form-control" placeholder="Input Hp." value="{{ $projectSheet->project_sheet_detail->hp_no ?? 'NA' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="contract_description">Contract Description</label>
                                            <textarea class="form-control" rows="3" placeholder="Input Contract Description" readonly>{{ $projectSheet->project_sheet_detail->contract_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="contract_period">Contract Period</label>
                                            <input type="text" class="form-control" placeholder="Input Contract Period" value="{{ $projectSheet->project_sheet_detail->contract_period ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="payment_term">Term of Payment</label>
                                            <input type="text" class="form-control" placeholder="Input Term of Payment" value="{{ $projectSheet->project_sheet_detail->payment_term ?? 'NA' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="schedule">Schedule</label>
                                            <input type="text" class="form-control" placeholder="Input Schedule" value="{{ optional($projectSheet->project_sheet_detail)->schedule ? \Carbon\Carbon::parse(optional($projectSheet->project_sheet_detail)->schedule)->format('d M Y') : 'NA' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="project_detail">Project Detail</label>
                                            <textarea class="form-control" rows="3" placeholder="Input Project Detail" readonly>{{ $projectSheet->project_detail }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('v1.pes.service.update', strtolower($projectSheet->project_no)) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input value="{{ $projectSheet->project_no }}" name="project_no" class="d-none">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Service Type</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 5%;">NO</th>
                                        <th style="text-align: center; width: 15%;">Item</th>
                                        <th style="text-align: center; width: 10%;">Qty</th>
                                        @foreach ($serviceType as $tipe)
                                            <th style="text-align: center;">{{ $tipe->sort_num }}</th>
                                        @endforeach
                                        <th style="text-align: center;">{{ (int) optional($serviceType->last())->sort_num + 1 }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceKategori as $kategori)
                                    @php
                                        // Ambil data service yang sesuai dengan kategori saat ini
                                        $currentService = optional($projectSheet->service->firstWhere('id_kategori_service', $kategori->id_kategori_service));
                                    @endphp
                                        <input value="{{ $kategori->id_kategori_service }}" name="id_kategori[{{ $kategori->sort_num }}]" class="d-none">
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle;">{{ $kategori->sort_num }}</td>
                                            <td style="text-align: center; vertical-align: middle;"><strong>{{ $kategori->name }}</strong></td>
                                            <td style="text-align: center;">
                                                <input type="text" 
                                                class="form-control" 
                                                name="kategori_qty[{{ $kategori->sort_num }}]"
                                                value="{{ $currentService->qty ?? '' }}"
                                                placeholder="0" 
                                                style="text-align: center;"/>
                                            </td>
                                            @foreach ($serviceType as $tipe)
                                            <td style="text-align: center; vertical-align: middle;">
                                                <div class="form-group clearfix p-0 m-0">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="radio" id="k{{ $kategori->sort_num }}r{{ $tipe->sort_num }}"
                                                            class="kategori-radio"
                                                            name="service_type[{{ $kategori->sort_num }}]"
                                                            value="{{ $tipe->id_service_type }}"
                                                            @if($currentService->id_service_type == $tipe->id_service_type) 
                                                                checked 
                                                            @endif>
                                                        <label for="k{{ $kategori->sort_num }}r{{ $tipe->sort_num }}"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            @endforeach
                                            <!-- Other Option -->
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <div class="form-group clearfix p-0 m-0">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio"
                                                                        id="k{{ $kategori->sort_num }}r{{ (int) optional($serviceType->last())->sort_num + 1 }}"
                                                                        class="kategori-radio"
                                                                        name="service_type[{{ $kategori->sort_num }}]"
                                                                        value="0"
                                                                        @if($currentService->id_service_type === null && $currentService->other === 1) 
                                                                            checked 
                                                                        @endif>
                                                                    <label for="k{{ $kategori->sort_num }}r{{ (int) optional($serviceType->last())->sort_num + 1 }}"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        id="other_value_{{ $kategori->sort_num }}"
                                                        class="form-control tipe-input"
                                                        data-kategori="{{ $kategori->sort_num }}"
                                                        data-tipe="{{ (int) optional($serviceType->last())->sort_num + 1 }}"
                                                        name="other_value[{{ $kategori->sort_num }}]"
                                                        placeholder="describe"
                                                        style="text-align: center;"
                                                        value="{{ $currentService->other_value }}"
                                                        @if(!$currentService->other || $currentService->other === 0) 
                                                            readonly
                                                        @endif>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @php
                                // Tambahkan item "Other" ke collection agar masuk ke chunk terakhir
                                $serviceList = $serviceType->concat([
                                    (object)[
                                        'sort_num' => (int) optional($serviceType->last())->sort_num + 1,
                                        'name' => 'Other (describe)'
                                    ]
                                ]);
                            @endphp
                            <div class="row m-3">
                                <div class="col-12">
                                    <div class="callout callout-warning">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <strong>Service Type:</strong>
                                                <div class="row mt-2">
                                                    @foreach ($serviceList->chunk(ceil($serviceList->count() / 2)) as $chunk)
                                                        <div class="col-md-6">
                                                            @foreach ($chunk as $tipe)
                                                                <div>{{ $tipe->sort_num . '. ' . $tipe->name }}</div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <p>
                                                <strong>- All Term and Condition of the services subject to be referred to the Contract/SO/LOI/PO/WO/WA</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>                      
                            <hr class="my-2"/>
                            <div class="row m-3">
                                <div class="col-12 mb-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_draft" name="is_draft" checked>
                                        <label class="custom-control-label" for="is_draft">Save to Draft</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">Selanjutnya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('.kategori-radio').each(function() {
            $(this).on('change', function() {
                var nameParts = this.name.match(/service_type\[(\d+)\]/);
                if (nameParts && nameParts.length > 1) {
                    var sortNum = nameParts[1];
                    var otherInput = $('#other_value_' + sortNum);
                    
                    if (otherInput.length) {
                        if ($(this).val() === '0') {
                            otherInput.prop('readonly', false);
                            otherInput.focus();
                        } else {
                            otherInput.prop('readonly', true);
                            otherInput.val('');
                        }
                    }
                }
            });
        });
    });
</script>
@endsection
