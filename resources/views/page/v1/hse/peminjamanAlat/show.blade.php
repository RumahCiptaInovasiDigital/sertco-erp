@extends('layouts.master')
@section('title', 'Data Peminjaman Alat')
@section('PageTitle', 'Data Peminjaman Alat')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Peminjaman Alat</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-11">
                            <h3 class="card-title" sty>Daftar Peminjaman Peralatan</h3>
                        </div>
                        <div class="col-12 col-md-1">
                            <a href="{{ route('v1.data-peminjaman.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <table border="0" cellspacing="0" cellpadding="2" style="width: 21%;">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->karyawan->fullName }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->nikUser }}</td>
                        </tr>
                        <tr>
                            <td>Departemen</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->karyawan->namaDepartemen }}</td>
                        </tr>
                        <tr>
                            <td>Client</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->namaClient }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="dt_tools" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>Nomor Seri</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Konsdisi Alat (Sebelum Berangkat)</th>
                            <th>Konsdisi Alat (Setelah Dari Site)</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataDetail as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->dataAlat->name}}</td>
                            <td>{{ $item->merkAlat }}</td>
                            <td>{{ $item->tipeAlat }}</td>
                            <td>{{ $item->snAlat }}</td>
                            <td>{{ $dataPeminjaman->tanggal_pinjam }}</td>
                            <td>{{ $dataPeminjaman->tanggal_kembali }}</td>
                            <td>{{ $item->kondisiSebelum }}</td>
                            <td>{{ $item->kondisiSesudah ?? '-' }}</td>
                            {{-- <td> - </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

{{-- <script>
    const _URL = "{{ route('v1.data-peminjaman.getData') }}";

$(document).ready(function () {
$('.page-loading').fadeIn();
setTimeout(function () {
$('.page-loading').fadeOut();
}, 1000); // Adjust the timeout duration as needed

let DT = $("#dt_tools").DataTable({
"paging": true,
"lengthChange": true,
"searching": true,
"ordering": true,
"info": true,
"autoWidth": false,
"responsive": true,
processing: true,
serverSide: true,
ajax: {
url: _URL,
},
columns: [
{ data: "DT_RowIndex" },
{ data: "nikUser" },
{ data: "tanggal_pinjam" },
{ data: "tanggal_kembali" },
{ data: "total_alat" },
{ data: "approved" },
{
data: "action",
orderable: false,
searchable: false,
},
],
columnDefs: [
{
targets: 0,
render: function (data, type, row, meta) {
return meta.row + meta.settings._iDisplayStart + 1; // Calculate the row index
},
},
],
});
});
</script> --}}
{{-- <script>
    function deleteData(id) {
        Swal.fire({
            text: "Are you sure you want to delete this Role?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('v1.data-peralatan.destroy') }}",
type: "POST",
data: {
id: id,
_token: "{{ csrf_token() }}",
},
success: function (response) {
$("#dt_tools").DataTable().ajax.reload(null, false);
Swal.fire("Deleted!", response.message, "success");
},
error: function (xhr) {
Swal.fire("Error!", xhr.responseJSON.message, "error");
},
});
} else if (result.dismiss === "cancel") {
Swal.fire("Cancelled", "Your data is safe :)", "error");
}
});
}
</script> --}}
@endsection
