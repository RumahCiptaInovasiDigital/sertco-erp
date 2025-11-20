@extends('layouts.master')
@section('title', 'Project Execution Sheet')
@section('PageTitle', 'Project Execution Sheet')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.pes.index') }}">Project Execution Sheet</a></li>
    <li class="breadcrumb-item active">Form</li>
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
                <form action="{{ route('v1.pes.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" class="form-control" name="nik" value="{{ auth()->user()->nik }}" id="nik" placeholder="Input NIK" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="prepared">Prepared By</label>
                                    <input type="hidden" name="prepared_by" value="{{ auth()->user()->id_user }}">
                                    <input type="text" value="{{ auth()->user()->fullname }}" class="form-control" id="prepared" placeholder="Input FullName" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="issued_date">Date</label>
                                    <input type="text" name="issued_date" value="{{ now()->format('d M Y') }}" class="form-control" id="issued_date" placeholder="Input Issued Date" readonly>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="to">To</label>
                                    <Select class="form-control select2" name="to" id="to">
                                        <option value="" disabled selected>select</option>
                                        @foreach ($departemen as $item)
                                        <option value="{{ $item->id_departemen }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="attn">Attention</label>
                                    <Select class="form-control select2" name="attn" id="attn">
                                        <option value="" disabled selected>select</option>
                                        @foreach ($role as $item)
                                        <option value="{{ $item->id_role }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <h5>Project Information</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="project_no">Project No.</label>
                                    <input type="text" class="form-control" name="project_no" id="project_no" value="{{ $project_no }}" placeholder="Input Project No." readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="client">Client</label>
                                    <input type="text" class="form-control" name="client" id="client" placeholder="Input Client">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="owner">Owner</label>
                                    <input type="text" class="form-control" name="owner" id="owner" placeholder="Input Owner">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="contract_no">Contract/SO/LOI/WA No.</label>
                                    <input type="text" class="form-control" name="contract_no" id="contract_no" placeholder="Input Contract No.">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="Input Contact Person">
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="ph_no">Ph.</label>
                                            <input type="text" class="form-control" name="ph_no" id="ph_no" placeholder="Input Ph.">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="email_client">Email</label>
                                            <input type="text" class="form-control" name="email_client" id="email_client" placeholder="Input email">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="hp_no">Hp.</label>
                                            <input type="text" class="form-control" name="hp_no" id="hp_no" placeholder="Input Hp.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="contract_description">Contract Description</label>
                                    <textarea class="form-control" name="contract_description" id="contract_description" rows="3" placeholder="Input Contract Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="contract_period">Contract Period</label>
                                            <input type="text" class="form-control" name="contract_period" id="contract_period" placeholder="Input Contract Period">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="row row-cols-2">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="schedule_start">Schedule Start</label>
                                                    <input type="date" class="form-control" name="schedule_start" id="schedule_start">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="schedule_start">End</label>
                                                    <input type="date" class="form-control" name="schedule_end">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="payment_term">Term of Payment</label>
                                            <textarea class="form-control" name="payment_term" id="payment_term" rows="3" placeholder="Input Term of payment"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="project_detail">Project Detail</label>
                                            <textarea class="form-control" name="project_detail" id="project_detail" rows="3" placeholder="Input Project Detail"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="row">
                                    <div class="col-12">
                                        <h5>Lampiran</h5>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="priceDoc">Upload Price Document</label>
                                            <input type="file" class="form-control" name="priceDoc" id="priceDoc" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="unpriceDoc">Upload UnPrice Document</label>
                                            <input type="file" class="form-control" name="unpriceDoc" id="unpriceDoc" required>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-warning" data-widget="control-sidebar" data-controlsidebar-slide="true">Notes / Comments!!</button>
                            </div>
                        </div> --}}
                        <hr class="my-3">
                        <div class="row">
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
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Comment Section --}}
{{-- <aside class="control-sidebar control-sidebar-light" style="width: 350px;">
    <div class="row m-1">
        <div class="col-12">
            <label><u>Project Notes / Comments</u></label>
            <div id="notes-wrapper">
            </div>
            <button type="button" class="btn btn-success btn-sm" id="add-note">
                <i class="fas fa-plus"></i> Tambah Note
            </button>
            <br/>
            <button type="button" class="btn btn-outline-danger btn-sm mt-1" data-widget="control-sidebar" data-controlsidebar-slide="true">
                tutup
            </button>
        </div>
    </div>
</aside> --}}

@endsection
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    // $(document).ready(function () {
    //     // Tambah note baru
    //     $('#add-note').click(function () {
    //         $('#notes-wrapper').append(`
    //             <div class="note-item">
    //                 <span class="badge badge-info">{{ auth()->user()->fullname }} :</span>
    //                 <div class="input-group mb-2">
    //                     <textarea name="notes[]" class="form-control" placeholder="Input Note"></textarea>
    //                     <button type="button" class="btn btn-danger btn-sm btn-remove-note">
    //                         <i class="fas fa-trash"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         `);
    //     });

    //     // Hapus note tertentu
    //     $(document).on('click', '.btn-remove-note', function () {
    //         $(this).closest('.note-item').remove();
    //     });
    // });
    
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    });
</script>
@endsection
