@extends('layouts.master')
@section('title', 'Approval Device')
@section('PageTitle', 'Approval Device Baru')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Permintaan Approval Device</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" id="btnRefresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" width="100%" id="approvalTable">
                                    <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Device Info</th>
                                        <th>Device ID</th>
                                        <th>Tanggal Permintaan</th>
                                        <th width="120">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>

        $(document).ready(function() {
            const table = $('#approvalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("presensi.device.approval.data") }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nik', name: 'nik'},
                    {data: 'nama', name: 'nama'},
                    {data: 'device_info', name: 'device_name'},
                    {data: 'device_id', name: 'device_id'},
                    {data: 'created_at_formatted', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[5, 'desc']]
            });

            $('#btnRefresh').on('click', function() {
                table.ajax.reload();
            });

            // Approve Device
            $(document).on('click', '.approve-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Setujui permintaan device ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("presensi/device/approval/approve") }}/' + id,
                            type: 'POST',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function(res) {
                                Swal.fire('Sukses!', res.message, 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });

            // Reject Device
            $(document).on('click', '.reject-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Tolak dan hapus permintaan device ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("presensi/device/approval/reject") }}/' + id,
                            type: 'POST',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function(res) {
                                Swal.fire('Sukses!', res.message, 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
