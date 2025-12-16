@extends('layouts.master')
@section('title', 'Manajemen Device')
@section('PageTitle', 'Manajemen Device')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Device Karyawan</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" id="btnRefresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" style="width: 100%" id="deviceTable">
                                    <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Device Name</th>

                                        <th>Device ID</th>
                                        <th>Status</th>
                                        <th>Terakhir Aktif</th>
                                        <th width="100">Aksi</th>
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
    <script>
        $(document).ready(function() {
            const table = $('#deviceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("device.data") }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nik', name: 'nik'},
                    {data: 'nama', name: 'nama'},
                    {data: 'device_name', name: 'device_name'},

                    {data: 'device_id', name: 'device_id'},
                    {data: 'status_badge', name: 'is_blocked'},
                    {data: 'activate_at', name: 'activate_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, 'asc']],
                // scrollX: true,
                scrollCollapse: true,

                responsive: true
            });

            $('#btnRefresh').on('click', function() {
                table.ajax.reload();
            });

            // Block Device
            $(document).on('click', '.block-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Blokir Device',
                    html: `
                            <p>Apakah Anda yakin ingin memblokir device ini?</p>
                            <div class="form-group text-left mt-3">
                                <label for="alasan-block">Alasan Pemblokiran <span class="text-danger">*</span></label>
                                <textarea id="alasan-block" class="form-control" rows="3" placeholder="Masukkan alasan pemblokiran..."></textarea>
                            </div>
                        `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Blokir!',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const alasan = document.getElementById('alasan-block').value;
                        if (!alasan || alasan.trim() === '') {
                            Swal.showValidationMessage('Alasan pemblokiran wajib diisi');
                            return false;
                        }
                        return alasan;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("device/manajemen/block") }}/' + id,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                alasan: result.value
                            },
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


            // Unblock Device
            $(document).on('click', '.unblock-btn', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengaktifkan kembali device ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Aktifkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("device/manajemen/unblock") }}/' + id,
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
