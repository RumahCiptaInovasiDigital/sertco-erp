@extends('layouts.master')
@section('title', 'Service Type')
@section('PageTitle', 'Edit Service Type')
@section('head')
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.service.type.index') }}">Service Type</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit a Service Type</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.service.type.update', $data->id_service_type) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Tipe</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}" placeholder="Input Nama Kategori">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.service.type.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
