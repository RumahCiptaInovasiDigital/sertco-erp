@extends('layouts.master')
@section('title', 'Manajemen Informasi')
@section('PageTitle', 'Manajemen Informasi')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Informasi</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" id="add-btn">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                                <button type="button" class="btn btn-tool" id="btnRefresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" style="width: 100%" id="information-table">
                                    <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Status</th>

                                        <th>Tanggal</th>
                                        <th>Dibuat Oleh</th>
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

    <!-- Modal Form -->
    <div class="modal fade" id="formModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="informationForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="information_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Form Informasi</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipe <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="general">Umum</option>
                                        <option value="reminder">Reminder</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Warna</label>
                            <input type="color" class="form-control" name="color" value="#007bff">
                        </div>
                        <div class="form-group">
                            <label>Lampiran</label>
                            <input type="file" class="form-control-file" name="attachment">
                            <small class="form-text text-muted">Maksimal 10MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 (Jika menggunakan template AdminLTE/Bootstrap4)
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            const table = $('#information-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("informasi.data") }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'title', name: 'title'},
                    {data: 'type_badge', name: 'type_badge'}, // Pastikan controller mengirim raw HTML atau column ini
                    {data: 'status_badge', name: 'status'},   // Pastikan controller mengirim raw HTML atau column ini
                    {data: 'date_range', name: 'date_range'},
                    {data: 'user_name', name: 'user.fullname'}, // Sesuaikan dengan relasi
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, 'asc']],
                scrollCollapse: true,
                responsive: true
            });

            $('#btnRefresh').on('click', function() {
                table.ajax.reload();
            });

            // --- 1. PERBAIKAN TOMBOL TAMBAH ---
            $('#add-btn').click(function() {
                $('#informationForm')[0].reset(); // Kosongkan form
                $('#information_id').val('');     // Kosongkan ID (penanda mode tambah)

                // Reset Select2 agar kembali ke pilihan default
                $('select[name="type"]').val('').trigger('change');
                $('select[name="status"]').val('').trigger('change');

                $('.modal-title').text('Tambah Informasi');
                $('#formModal').modal('show');
            });

            // --- 2. PERBAIKAN SUBMIT FORM ---
            $('#informationForm').submit(function(e) {
                e.preventDefault();
                const id = $('#information_id').val();

                // Tentukan URL berdasarkan ada tidaknya ID
                const url = id ? '{{ url("informasi/update") }}/' + id : '{{ route("informasi.store") }}';

                const formData = new FormData(this);

                // Laravel Resource controller butuh _method: PUT untuk update via FormData
                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: 'POST', // Tetap POST karena ada file upload & _method spoofing
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#formModal').modal('hide');
                        Swal.fire('Sukses!', res.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = 'Terjadi kesalahan';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Ambil error pertama jika validasi gagal
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        Swal.fire('Error!', msg, 'error');
                    }
                });
            });

            // --- 3. PERBAIKAN EDIT ---
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get('{{ url("informasi/show") }}/' + id, function(data) {
                    $('#information_id').val(data.id);
                    $('[name="title"]').val(data.title);
                    $('[name="description"]').val(data.description);

                    // Update Select2 dengan trigger change
                    $('[name="type"]').val(data.type).trigger('change');
                    $('[name="status"]').val(data.status).trigger('change');

                    // Format Tanggal untuk input datetime-local (YYYY-MM-DDTHH:mm)
                    // Data JSON: "1979-03-26T00:00:00.000000Z" -> Ambil 16 karakter pertama
                    if(data.start_date) {
                        let start = data.start_date.substring(0, 16);
                        $('[name="start_date"]').val(start);
                    }

                    if(data.end_date) {
                        let end = data.end_date.substring(0, 16);
                        $('[name="end_date"]').val(end);
                    }

                    $('[name="color"]').val(data.color || '#007bff');

                    $('.modal-title').text('Edit Informasi');
                    $('#formModal').modal('show');
                }).fail(function() {
                    Swal.fire('Error', 'Gagal mengambil data', 'error');
                });
            });

            // Delete (Sudah Oke)
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("informasi/destroy") }}/' + id,
                            type: 'DELETE',
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

            // --- 4. PERBAIKAN DETAIL ---
            $(document).on('click', '.detail-btn', function() {
                const id = $(this).data('id');
                $.get('{{ url("informasi/show") }}/' + id, function(data) {

                    // Cek path lampiran. Jika path dari faker/temp windows, mungkin tidak bisa dibuka.
                    // Asumsi path valid tersimpan di storage/app/public
                    let attachment = '-';
                    if (data.attachment_path) {
                        // Bersihkan path jika ada backslash (kasus windows path di database testing)
                        let cleanPath = data.attachment_path.replace(/\\/g, "/");
                        // URL Download
                        let urlDownload = `{{ url('storage') }}/${cleanPath}`;

                        attachment = `<a href="${urlDownload}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i> Download File
                                       </a>`;
                    }

                    // Format Tanggal agar enak dibaca (Indonesia)
                    let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    let startDate = new Date(data.start_date).toLocaleDateString('id-ID', options);
                    let endDate = new Date(data.end_date).toLocaleDateString('id-ID', options);

                    // Ambil nama user (perbaikan field fullname)
                    let creator = data.user ? data.user.fullname : 'System/Deleted User';

                    Swal.fire({
                        title: `Detail: ${data.title}`,
                        html: `
                            <div class="text-left" style="font-size: 0.9rem;">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="30%"><strong>Tipe</strong></td>
                                        <td>: ${data.type}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>: <span class="badge badge-${data.status === 'active' ? 'success' : 'secondary'}">${data.status}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>: ${startDate} <br> s/d <br> ${endDate}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat Oleh</strong></td>
                                        <td>: ${creator}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lampiran</strong></td>
                                        <td>: ${attachment}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Deskripsi:</strong><br>
                                            <div class="p-2 bg-light border rounded mt-1">${data.description}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        `,
                        width: '600px',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                });
            });
        });
    </script>
@endsection
