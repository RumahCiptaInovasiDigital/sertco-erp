@extends('layouts.master')
@section('title', 'Data Karyawan')
@section('PageTitle', 'Data Karyawan')

@section('head')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Data Karyawan</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Karyawan</h3>
            <div class="card-tools">
                <a href="{{ route('master.karyawan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Karyawan
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="karyawan-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Grade</th>
                    <th>No. Telepon</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                {{-- Data will be populated by DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function () {
            $("#karyawan-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.karyawan.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'foto', name: 'foto', orderable: false, searchable: false },
                    { data: 'nik', name: 'nik' },
                    { data: 'fullName', name: 'fullName' },
                    { data: 'namaJabatan', name: 'namaJabatan' },
                    { data: 'grade', name: 'grade' },
                    { data: 'phoneNumber', name: 'phoneNumber' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
            });
        });
    </script>
@endsection
