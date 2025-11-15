@extends('layouts.master')
@section('title', 'Review')
@section('PageTitle', 'Review Project')
@section('head')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.pes.index') }}">Approval PES</a></li>
    <li class="breadcrumb-item active">View</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-md-9">
        {{-- Project Information --}}
        <div class="card collapsing-card">
            <div class="card-header">
                <button type="button" class="btn btn-tool w-100" data-card-widget="collapse">
                    <h3 class="card-title" style="color: black;">Detail Project</h3>
                    <div class="float-right d-none d-sm-inline">
                        <i class="fas fa-minus"></i>
                    </div>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" class="form-control" value="{{ $data->karyawan->nik ?? null }}" id="nik" placeholder="Input NIK" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="prepared">Prepared By</label>
                                <input type="text" value="{{ $data->karyawan->fullName ?? null }}" class="form-control" id="prepared" placeholder="Input FullName" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="issued_date">Issued Date</label>
                                <input type="text" name="issued_date" value="{{ $data->issued_date }}" class="form-control" id="issued_date" placeholder="Input Issued Date" readonly>
                            </div>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="to">To</label>
                                <input type="text" value="{{ $data->to ?? 'NA' }}" class="form-control" id="to" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="attn">Attention</label>
                                <input type="text" value="{{ $data->attn ?? 'NA' }}" class="form-control" id="attn" readonly>
                            </div>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="row">
                        <div class="col-12">
                            <h5>Project Information</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="project_no">Project No.</label>
                                <input type="text" class="form-control" name="project_no" id="project_no" value="{{ $data->project_no ?? 'NA' }}" placeholder="Input Project No." readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="client">Client</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->client ?? 'NA' }}" placeholder="Input Client" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="owner">Owner</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->owner ?? 'NA' }}" placeholder="Input Owner" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="contract_no">Contract/SO/LOI/WA No.</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->contract_no ?? 'NA' }}" placeholder="Input Contract No." readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->contact_person ?? 'NA' }}" placeholder="Input Contact Person" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="ph_no">Ph.</label>
                                        <input type="text" class="form-control" value="{{ $data->project_sheet_detail->ph_no ?? 'NA' }}" placeholder="Input Ph." readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="fax_no">Fax</label>
                                        <input type="text" class="form-control" value="{{ $data->project_sheet_detail->fax_no ?? 'NA' }}" placeholder="Input Fax No." readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="hp_no">Hp.</label>
                                        <input type="text" class="form-control" value="{{ $data->project_sheet_detail->hp_no ?? 'NA' }}" placeholder="Input Hp." readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="contract_description">Contract Description</label>
                                <textarea class="form-control" name="contract_description" id="contract_description" rows="3" placeholder="Input Contract Description" readonly>{{ $data->project_sheet_detail->contract_description ?? 'NA' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="contract_period">Contract Period</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->contract_period ?? 'NA' }}" placeholder="Input Contract Period" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="payment_term">Term of Payment</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->payment_term ?? 'NA' }}" placeholder="Input Term of Payment" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="schedule">Schedule</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->schedule ?? 'NA' }}" placeholder="Input Schedule" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="project_detail">Project Detail</label>
                                <textarea class="form-control" name="project_detail" id="project_detail" rows="3" placeholder="Input Project Detail" readonly>{{ $data->project_detail ?? 'NA' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        {{-- Approval Section --}}
        <div class="card collapsing-card card-success card-outline">
            <div class="card-header">
                <button type="button" class="btn btn-tool w-100" data-card-widget="collapse">
                    <h3 class="card-title" style="color: black;">Komentar</h3>
                    <div class="float-right d-none d-sm-inline">
                        <i class="fas fa-minus"></i>
                    </div>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row m-1">
                    <div class="col-12">
                        @foreach($notes as $note)
                        <div class="note-item">
                            <span class="badge badge-info">{{ $note->user->fullname }} :</span>
                            <div class="input-group mb-2">
                                <textarea class="form-control" readonly>{{ $note->note }}</textarea>
                            </div>
                        </div>
                        @endforeach
                        <div id="notes-wrapper">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveNotes('{{ $data->project_no }}')">
                            Simpan Catatan
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="add-note">
                            <i class="fas fa-plus"></i> Tambah Note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        {{-- Service Type --}}
        <div class="card collapsed-card">
            <div class="card-header">
                <button type="button" class="btn btn-tool w-100" data-card-widget="collapse">
                    <h3 class="card-title" style="color: black;">Services</h3>
                    <div class="float-right d-none d-sm-inline">
                        <i class="fas fa-plus"></i>
                    </div>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
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
                                    <input type="text" class="form-control" 
                                    value="{{ $currentService->qty ?? '' }}" 
                                    placeholder="0" 
                                    style="text-align: center;"
                                    readonly/>
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
                                                @endif
                                                disabled
                                                >
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
                                                            @endif
                                                            disabled
                                                            >
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
                                            placeholder=""
                                            style="text-align: center;"
                                            value="{{ $currentService->other_value }}"
                                            readonly 
                                            >
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
                <div class="row my-3">
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
                            <div class="col-12 mt-2">
                                <p>
                                    <strong>- All Term and Condition of the services subject to be referred to the Contract/SO/LOI/PO/WO/WA</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('#add-note').click(function () {
            $('#notes-wrapper').append(`
                <div class="note-item">
                    <span class="badge badge-info">{{ auth()->user()->fullname }} :</span>
                    <div class="input-group mb-2">
                        <textarea name="notes[]" class="form-control" placeholder="Input Note"></textarea>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-note">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);
        });

        // Hapus note tertentu
        $(document).on('click', '.btn-remove-note', function () {
            $(this).closest('.note-item').remove();
        });

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
<script>
    function saveNotes(projectId) {
        const notes = [];
        $('textarea[name="notes[]"]').each(function () {
            const val = $(this).val().trim();
            if (val) notes.push(val);
        });

        if (notes.length === 0) {
            Swal.fire('Oops', 'Catatan tidak boleh kosong.', 'warning');
            return;
        }

        $.ajax({
            url: "{{ route('v1.review.pes.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                project_no: projectId,
                notes: notes
            },
            success: function (res) {
                if (res.success) {
                    Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            }
        });
    }

</script>
    
@endsection
