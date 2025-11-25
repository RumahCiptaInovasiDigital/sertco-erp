@extends('layouts.master')
@section('title', 'Show PES')
@section('PageTitle', 'Project Execution Sheet')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('styles')
    <style>
        .timeline {
            margin: 0;
        }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.pes.index') }}">Project Execution Sheet</a></li>
    <li class="breadcrumb-item active">Show</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        {{-- Project Information --}}
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-10">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#information" data-toggle="tab">Project Information</a></li>
                            <li class="nav-item"><a class="nav-link" href="#service" data-toggle="tab">Service Type</a></li>
                            <li class="nav-item"><a class="nav-link" href="#pricedoc" data-toggle="tab">Price Document</a></li>
                            <li class="nav-item"><a class="nav-link" href="#unpricedoc" data-toggle="tab">Unprice Document</a></li>
                            <li class="nav-item"><a class="nav-link" href="#inspektor" data-toggle="tab">Inspector</a></li>
                            <li class="nav-item"><a class="nav-link" href="#coi" data-toggle="tab">COI</a></li>
                            <li class="nav-item"><a class="nav-link" href="#report" data-toggle="tab">Reporting</a></li>
                            <li class="nav-item"><a class="nav-link" href="#comment" data-toggle="tab"><i class="fas fa-comment mr-1"></i>Comment</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="information">
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
                                    <input type="text" value="{{ $data->preparedBy->fullName ?? null }}" class="form-control" id="prepared" placeholder="Input FullName" readonly>
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
                    <div class="tab-pane" id="service">
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
                                    $currentService = optional($data->service->firstWhere('id_kategori_service', $kategori->id_kategori_service));
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
                                                {{-- <input type="text"
                                                    id="other_value_{{ $kategori->sort_num }}"
                                                    class="form-control tipe-input text-wrap"
                                                    data-kategori="{{ $kategori->sort_num }}"
                                                    data-tipe="{{ (int) optional($serviceType->last())->sort_num + 1 }}"
                                                    name="other_value[{{ $kategori->sort_num }}]"
                                                    placeholder=""
                                                    style="text-align: center;"
                                                    value="{{ $currentService->other_value }} kajshfkljahsd lfkjasflkjahfkjhsfhsakudhfshdfhdf ljkasf jsdlkajh"
                                                    readonly 
                                                    > --}}
                                                    
                                                    <textarea 
                                                    id="other_value_{{ $kategori->sort_num }}"
                                                    class="form-control tipe-input wrap-text overflow-auto" 
                                                    data-kategori="{{ $kategori->sort_num }}"
                                                    data-tipe="{{ (int) optional($serviceType->last())->sort_num + 1 }}"
                                                    name="other_value[{{ $kategori->sort_num }}]"
                                                    rows="auto"
                                                    style="text-align: center;"
                                                    readonly >{{ $currentService->other_value }}</textarea>
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
                    <div class="tab-pane" id="pricedoc">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <object 
                                            data="{{ asset('assets/project/'. $data->project_no. '/pricedoc/' . $data->project_sheet_detail->pricedoc) }}" 
                                            type="application/pdf" 
                                            width="100%" 
                                            height="700px">
                                            <p>Browser tidak bisa menampilkan PDF, <a href="{{ asset('assets/project/'. $data->project_no. '/pricedoc/' . $data->project_sheet_detail->pricedoc) }}">klik di sini untuk buka</a>.</p>
                                        </object>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="unpricedoc">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <object 
                                            data="{{ asset('assets/project/'. $data->project_no. '/unpricedoc/' . $data->project_sheet_detail->unpricedoc) }}" 
                                            type="application/pdf" 
                                            width="100%" 
                                            height="700px">
                                            <p>Browser tidak bisa menampilkan PDF, <a href="{{ asset('assets/project/'. $data->project_no. '/unpricedoc/' . $data->project_sheet_detail->unpricedoc) }}">klik di sini untuk buka</a>.</p>
                                        </object>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="inspektor">
                    </div>
                    <div class="tab-pane" id="coi">
                    </div>
                    <div class="tab-pane" id="report">
                    </div>
                    <div class="tab-pane" id="comment">
                        <div id="comment-container"></div>

                        <div id="comment-create" class="mt-3">
                            <textarea id="main-comment" class="form-control mb-2" rows="3" placeholder="Write a comment..."></textarea>

                            <div class="d-flex align-items-center">
                                <input type="file" id="main-image" accept="image/*">
                                <button id="send-comment" class="btn btn-primary ml-2">Send</button>
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
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    });
</script>
<script>
    $(function() {
        // ensure CSRF token present for ajax post
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    
        const projectNo = "{{ $data->project_no }}";
        const loadUrl = "comment/" + projectNo;
    
        // initial load via jQuery .load() — server returns HTML directly
        function loadComments() {
            $("#comment-container").load(loadUrl);
        }
    
        loadComments();
    
        // Send main comment (with optional image)
        $('#send-comment').on('click', function () {
            const comment = $('#main-comment').val().trim();
            const imageFile = $('#main-image').prop('files')[0];
    
            if (!comment && !imageFile) {
                alert('Isi komentar atau pilih gambar.');
                return;
            }
    
            const form = new FormData();
            form.append('project_no', projectNo);
            form.append('comment', comment);
            if (imageFile) form.append('image', imageFile);
    
            $.ajax({
                url: 'comment',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#main-comment').val('');
                    $('#main-image').val('');
                    loadComments();
                },
                error: function(xhr) {
                    alert('Gagal mengirim komentar.');
                    console.error(xhr.responseText);
                }
            });
        });
    
        // Delegate: toggle reply form
        $(document).on('click', '.reply-toggle', function () {
            const id = $(this).data('id');
            $('#reply-form-' + id).toggleClass('d-none');
        });
    
        // Delegate: send reply (with optional image)
        $(document).on('click', '.send-reply', function () {
            const id = $(this).data('id');
            const text = $('#reply-form-' + id + ' .reply-text').val().trim();
            const fileEl = $('#reply-form-' + id + ' .reply-image')[0];
            const file = fileEl && fileEl.files[0];
    
            if (!text && !file) {
                alert('Isi reply atau pilih gambar.');
                return;
            }
    
            const form = new FormData();
            form.append('project_no', projectNo);
            form.append('comment', text);
            form.append('parent_id', id);
            if (file) form.append('image', file);
    
            $.ajax({
                url: 'comment',
                method: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function() {
                    loadComments();
                },
                error: function(xhr) {
                    alert('Gagal mengirim reply.');
                    console.error(xhr.responseText);
                }
            });
        });
    
        // Delegate: like toggle
        $(document).on('click', '.like-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            $.post('comment/' + id + '/like', {}, function (res) {
                // Option A: reload everything (simple, consistent)
                loadComments();
    
                // Option B: update only this like count/button (more efficient)
                // $(e.currentTarget).find('.like-count').text('(' + res.count + ')');
            });
        });
    
        // Optional: preview image before upload for main comment
        $('#main-image').on('change', function () {
            // you can implement preview if you want (left out to keep concise)
        });

        $('.like-btn').on('click', function () {
            const id = $(this).data('id');
            // AJAX like...
        });

        $('.reply-toggle').on('click', function () {
            const id = $(this).data('id');
            // toggle reply form...
        });

    });
</script>

@endsection
