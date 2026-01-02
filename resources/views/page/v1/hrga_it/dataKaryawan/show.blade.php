@extends('layouts.master')
@section('title', 'Detail Data Karyawan')
@section('PageTitle', 'Detail Data Karyawan')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-karyawan.index') }}">Data karyawan</a></li>
    <li class="breadcrumb-item active">Show</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>Detail Data Karyawan
                </h3>
            </div>

            <div class="card-body">
                {{-- Header Profile --}}
                <div class="row mb-4">
                    <div class="col-md-3 d-flex justify-content-center">
                        <div 
                            class="border rounded d-flex align-items-center justify-content-center bg-light"
                            style="width: 180px; height: 180px;"
                        >
                            @if($karyawan->foto)
                                <img 
                                    src="{{ asset('storage/foto/'.$karyawan->foto) }}"
                                    alt="Foto Karyawan"
                                    class="img-fluid rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                >
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-user fa-3x mb-2"></i>
                                    <div class="small">Foto belum tersedia</div>
                                </div>
                            @endif
                        </div>
                    </div>                    

                    <div class="col-md-9">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="30%">Jabatan</th>
                                <td>: {{ $karyawan->namaJabatan }}</td>
                            </tr>
                            <tr>
                                <th>Departemen</th>
                                <td>: {{ $karyawan->namaDepartemen ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Grade</th>
                                <td>: {{ $karyawan->grade ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status Karyawan</th>
                                <td>
                                    : <span class="badge badge-info">{{ $karyawan->statusTK ?? '-' }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Informasi Pribadi</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th width="25%">Tempat, Tanggal Lahir</th>
                                <td>
                                    {{ $karyawan->tempatLahir ?? '-' }},
                                    {{ $karyawan->tanggalLahir ? \Carbon\Carbon::parse($karyawan->tanggalLahir)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Pendidikan</th>
                                <td>{{ $karyawan->pendidikan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td>{{ $karyawan->agama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $karyawan->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $karyawan->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>{{ $karyawan->phoneNumber ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Legal & Finance --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Legal & Keuangan</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th width="25%">No. KTP</th>
                                <td>{{ $karyawan->noKTP ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. SIM</th>
                                <td>{{ $karyawan->noSIM ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>NPWP</th>
                                <td>{{ $karyawan->noNPWP ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status PTKP</th>
                                <td>{{ $karyawan->statusPTKP ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Rekening</th>
                                <td>{{ $karyawan->noRekening ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Employment --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Informasi Kepegawaian</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th width="25%">Tanggal Join</th>
                                <td>{{ \Carbon\Carbon::parse($karyawan->joinDate)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Kontrak Mulai</th>
                                <td>{{ \Carbon\Carbon::parse($karyawan->empDateStart)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Kontrak Selesai</th>
                                <td>
                                    {{ $karyawan->empDateEnd ? \Carbon\Carbon::parse($karyawan->empDateEnd)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Resign</th>
                                <td>
                                    {{ $karyawan->resignDate ? \Carbon\Carbon::parse($karyawan->resignDate)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Emergency --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Kontak Darurat</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th width="25%">Nama</th>
                                <td>{{ $karyawan->emergencyName ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Hubungan</th>
                                <td>{{ $karyawan->emergencyRelation ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Kontak</th>
                                <td>{{ $karyawan->emergencyContact ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="{{ route('v1.data-karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit Data Karyawan
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
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
    const _URL = "{{ route('v1.data-karyawan.getData') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_employee").DataTable({
                "paging": true,
                "lengthChange": false,
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
                    { data: "nik" },
                    { data: "fullName" },
                    { data: "email" },
                    { data: "phoneNumber" },
                    { data: "namaJabatan" },
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

            // $('#search_dt').on('keyup', function () {
            //     DT.search(this.value).draw();
            // });
        });
</script>
<script>
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
                    url: "{{ route('v1.data-karyawan.destroy') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        $("#dt_employee").DataTable().ajax.reload(null, false);
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

</script>
@endsection
