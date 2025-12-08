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
    <div class="col-12 col-md-9">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-11">
                            <h3 class="card-title">Informasi Peminjaman Peralatan</h3>
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
                            <td>{{ $dataPeminjaman->karyawan->fullName ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->nikUser ??'-' }}</td>
                        </tr>
                        <tr>
                            <td>Departemen</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->karyawan->namaDepartemen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Client</td>
                            <td>:</td>
                            <td>{{ $dataPeminjaman->namaClient }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
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
                            <td>{{ $dataPeminjaman->tanggal_kembali ?? '-' }}</td>
                            <td>{{ $item->kondisiSebelum }}</td>
                            <td>{{ $item->kondisiSesudah ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Approval Section</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- @if ($approvalData->is_approved === 0 && $approvalData->is_rejected === 0) --}}
                    <div class="col-12 mb-2">
                        <button class="btn btn-sm bg-gradient-success w-100" onclick="handleApproval('approve')">Approve</button>
                    </div>
                    <div class="col-12 mb-2">
                        <button class="btn btn-sm bg-gradient-danger w-100" onclick="handleApproval('reject')">Reject</button>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="contract_description">Note/Catatan</label>
                            <textarea class="form-control" name="catatan_approved" id="catatan_approved" rows="3" placeholder="Masukkan Catatan"></textarea>
                        </div>
                    </div>
                    {{-- @else --}}
                    {{-- <div class="col-12 mb-2">
                        <button 
                            class="btn bg-gradient-{{ 
                                $approvalData->is_approved === 1 
                                    ? 'success' 
                                    : ($approvalData->is_rejected === 1 
                                        ? 'danger' 
                                        : 'secondary') 
                            }} w-100">
                            {{ 
                                $approvalData->is_approved === 1 
                                    ? 'Approved' 
                                    : ($approvalData->is_rejected === 1 
                                        ? 'Rejected' 
                                        : 'Reject') 
                            }}
                        </button>
                    </div> --}}
                    {{-- <div class="col-12">
                        Response by: {{ $approvalData->responseKaryawan->fullName ?? $approvalData->responseUserSession->fullname }} <br>
                        Response at: {{ $approvalData->response_at }}
                    </div> --}}
                    {{-- @endif --}}
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

<script>
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
            // serverSide: true,
        });
    });

    function handleApproval(action) {
        const catatan = document.getElementById('catatan_approved').value;
        const approvedId = '{{ $dataApproved->id ?? '' }}';
    
        fetch('{{ route('v1.approval-alat.ApproveOrReject') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: approvedId,
                action: action,
                catatan_approved: catatan
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
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => Swal.fire('Error', err.message, 'error'));
    }

</script>
@endsection
