@extends('layouts.master')
@section('title', 'View ISO')
@section('PageTitle', 'View Jenis ISO')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.master-iso.index') }}">View Jenis ISO</a></li>
    <li class="breadcrumb-item active">View</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Jenis ISO</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="name">Nama ISO</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="petugas">Petugas Audit</label>
                            <input type="text" class="form-control" name="petugas" id="petugas" value="{{ $data->petugas }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="tgl_audit">Tanggal Audit</label>
                            <input type="date" class="form-control" name="tgl_audit" id="tgl_audit" value="{{ $data->tgl_audit }}" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Document ISO</label>
                                @php
                                    $file = $data->fileIso ?? null;
                                    $link = $data->linkIso ?? null;
                                    $folderName = Str::slug($data->name);

                                    function getDriveFileId($url)
                                    {
                                        if (!$url) return null;

                                        // format: https://drive.google.com/file/d/FILE_ID/view?usp=sharing
                                        if (preg_match('/\/d\/(.*?)\//', $url, $match)) {
                                            return $match[1];
                                        }

                                        // format: https://drive.google.com/open?id=FILE_ID
                                        parse_str(parse_url($url, PHP_URL_QUERY), $qs);

                                        return $qs['id'] ?? null;
                                    }

                                    $linkId = getDriveFileId($link);
                                @endphp
          
                                {{-- FILE MODE --}}
                                @if ($file)
                                    <object 
                                        data="{{ asset('assets/ISO/' . $folderName . '/' . $file) }}"
                                        type="application/pdf"
                                        width="100%"
                                        height="700px">
                                        <p>
                                            Browser tidak bisa menampilkan PDF. 
                                            <a href="{{ asset('assets/ISO/' . $folderName . '/' . $file) }}" 
                                            target="_blank">Klik di sini untuk buka</a>.
                                        </p>
                                    </object>
                                    <p class="mt-2">
                                        <a href="{{ asset('assets/ISO/' . $folderName . '/' . $file) }}" target="_blank" class="btn btn-primary btn-sm">
                                            Open File PDF
                                        </a>
                                    </p>

                                {{-- LINK MODE --}}
                                @elseif ($link)
                                    @if ($linkId)
                                        {{-- IFRAME PREVIEW --}}
                                        <iframe 
                                            src="https://drive.google.com/file/d/{{ $linkId }}/preview"
                                            width="100%"
                                            height="700px"
                                            allow="autoplay">
                                        </iframe>

                                        <div class="mt-3">
                                            <a href="{{ $link }}" 
                                            target="_blank" 
                                            class="btn btn-primary btn-sm">
                                                Open in Google Drive
                                            </a>
                                        </div>

                                    @else
                                        {{-- INVALID OR NON-GDRIVE LINK --}}
                                        <p>Link tidak bisa di-embed.</p>
                                        <a href="{{ $link }}" 
                                        target="_blank" 
                                        class="btn btn-primary btn-sm">
                                            Open Link
                                        </a>
                                    @endif

                                @else
                                    <p class="text-muted">No document available.</p>
                                @endif
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <small><b><span style="color: #ff0000;">(*)</span> <em>Wajib Diisi</em></b></small>
                    </div> --}}
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
@endsection
