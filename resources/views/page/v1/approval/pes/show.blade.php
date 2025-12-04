@extends('layouts.master')
@section('title', 'View Project Execution Sheet')
@section('PageTitle', 'Request Approval PES')
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
                    <h3 class="card-title" style="color: black;">Informasi Project</h3>
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
                                <label for="schedule">Schedule Start</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->schedule_start ? \Carbon\Carbon::parse($data->project_sheet_detail->schedule_start)->format('d M Y') : 'NA' }}" placeholder="Input Schedule" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="schedule">Schedule End</label>
                                <input type="text" class="form-control" value="{{ $data->project_sheet_detail->schedule_end ? \Carbon\Carbon::parse($data->project_sheet_detail->schedule_end)->format('d M Y') : 'NA' }}" placeholder="Input Schedule" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="payment_term">Term of Payment</label>
                                <textarea class="form-control" name="payment_term" id="payment_term" rows="3" placeholder="Input Project Detail" readonly>{{ $data->project_sheet_detail->payment_term ?? 'NA' }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
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
                    <h3 class="card-title" style="color: black;">Status Approval</h3>
                    <div class="float-right d-none d-sm-inline">
                        <i class="fas fa-minus"></i>
                    </div>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Marketing --}}
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header"><h5 class="card-title">Approval Tim Marketing</h5></div>
                            <div class="card-body">
                                @if (!$approvalData->disetujui_mkt && !$approvalData->ditolak_mkt)
                                    <div class="col-12 mb-2">
                                        <button onclick="openAuthModal('approve','mkt')" class="btn btn-sm bg-gradient-success w-100">Approve</button>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <button onclick="openAuthModal('reject','mkt')" class="btn btn-sm bg-gradient-danger w-100">Reject</button>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="approval_note_mkt">Note/Catatan</label>
                                            <textarea class="form-control" name="approval_note_mkt" id="approval_note_mkt" rows="3" placeholder="Masukkan Catatan"></textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12 mb-2">
                                        <button class="btn w-100 bg-gradient-{{ $approvalData->disetujui_mkt ? 'success' : ($approvalData->ditolak_mkt ? 'danger' : 'secondary') }}">
                                            {{ $approvalData->disetujui_mkt ? 'Approved' : ($approvalData->ditolak_mkt ? 'Rejected' : 'Processed') }}
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        Response by: {{ optional($approvalData->responseMkt)->fullName ?? 'Unknown' }} <br>
                                        Response at: {{ $approvalData->response_mkt_at ?? '-' }} <br>
                                        Note: {{ $approvalData->note_mkt ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- T&O --}}
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header"><h5 class="card-title">Approval Tim T&O</h5></div>
                            <div class="card-body">
                                @php
                                    $mktApproved = $approvalData->disetujui_mkt ?? false;
                                    $toPending = $approvalData && !$approvalData->disetujui_to && !$approvalData->ditolak_to;
                                @endphp

                                @if (!$mktApproved)
                                    <div class="col-12 mb-2">
                                        <button class="btn bg-gradient-warning w-100" disabled>Belum Di-Approve oleh Marketing</button>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="approval_note_to">Note/Catatan</label>
                                            <textarea class="form-control" name="approval_note_to" id="approval_note_to" rows="3" placeholder="Masukkan Catatan" disabled></textarea>
                                        </div>
                                    </div>
                                @else
                                    @if ($toPending)
                                        <div class="col-12 mb-2">
                                            <button onclick="openAuthModal('approve','to')" class="btn btn-sm bg-gradient-success w-100">Approve</button>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <button onclick="openAuthModal('reject','to')" class="btn btn-sm bg-gradient-danger w-100">Reject</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="approval_note_to">Note/Catatan</label>
                                                <textarea class="form-control" name="approval_note_to" id="approval_note_to" rows="3" placeholder="Masukkan Catatan"></textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 mb-2">
                                            <button class="btn w-100 bg-gradient-{{ $approvalData->disetujui_to ? 'success' : ($approvalData->ditolak_to ? 'danger' : 'secondary') }}">
                                                {{ $approvalData->disetujui_to ? 'Approved' : ($approvalData->ditolak_to ? 'Rejected' : 'Processed') }}
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            Response by: {{ optional($approvalData->responseTo)->fullName ?? 'Unknown' }} <br>
                                            Response at: {{ $approvalData->response_to_at ?? '-' }} <br>
                                            Note: {{ $approvalData->note_to ?? '-' }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
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
@section('modals')
<!-- Re-auth Modal -->
<div class="modal fade" id="reauthModal" tabindex="-1" aria-labelledby="reauthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="reauthForm" onsubmit="return submitReauth(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="reauthModalLabel">Konfirmasi Password</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="closeAuthModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Masukkan password untuk melanjutkan aksi ini.</p>
                    <div class="form-group">
                        <input type="password" id="reauth_password" class="form-control" placeholder="Password" required autocomplete="current-password" />
                    </div>
                    <input type="hidden" id="reauth_action" />
                    <input type="hidden" id="reauth_role" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeAuthModal()">Batal</button>
                    <button type="submit" id="reauth_submit_btn" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
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
<script>
    let currentAction = null;
    let currentRole = null;

    function openAuthModal(action, role) {
        // store for submit handler
        currentAction = action;
        currentRole = role;

        document.getElementById('reauth_password').value = '';
        document.getElementById('reauth_action').value = action;
        document.getElementById('reauth_role').value = role;

        // show modal (Bootstrap 5)
        const modalEl = document.getElementById('reauthModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function closeAuthModal() {
        const modalEl = document.getElementById('reauthModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    async function submitReauth(e) {
        e.preventDefault();
        const password = document.getElementById('reauth_password').value;
        const action = document.getElementById('reauth_action').value;
        const role = document.getElementById('reauth_role').value;

        // choose note field based on role
        const noteElemId = role === 'mkt' ? 'approval_note_mkt' : 'approval_note_to';
        const note = document.getElementById(noteElemId) ? document.getElementById(noteElemId).value : '';

        const projectId = '{{ $data->id_project ?? '' }}';

        // disable submit to avoid double click
        const submitBtn = document.getElementById('reauth_submit_btn');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Memproses...';

        try {
            const res = await fetch('{{ route('v1.approval.pes.ApproveOrReject') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    project_id: projectId,
                    action: action,
                    role: role,
                    approval_note: note,
                    password: password
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // success
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                // show error message (from server)
                Swal.fire('Error', data.message || 'Autentikasi/approval gagal', 'error');
                submitBtn.disabled = false;
                submitBtn.innerText = 'Konfirmasi';
            }
        } catch (err) {
            Swal.fire('Error', err.message || 'Network error', 'error');
            submitBtn.disabled = false;
            submitBtn.innerText = 'Konfirmasi';
        }
        return false;
    }

    function handleApproval(action, role) {
        // role = 'mkt' or 'to'
        const noteElemId = role === 'mkt' ? 'approval_note_mkt' : 'approval_note_to';
        const note = document.getElementById(noteElemId) ? document.getElementById(noteElemId).value : '';
        const projectId = '{{ $data->id_project ?? '' }}';

        fetch('{{ route('v1.approval.pes.ApproveOrReject') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                project_id: projectId,
                action: action,
                role: role,
                approval_note: note
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                Swal.fire('Error', data.message || 'Unknown error', 'error');
            }
        })
        .catch(err => Swal.fire('Error', err.message || 'Network error', 'error'));
    }

</script>
    
@endsection
