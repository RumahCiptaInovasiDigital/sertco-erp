@extends('layouts.master')
@section('title', 'Sertifikat Personil')
@section('PageTitle', 'Input Sertifikat Personil')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.jenis-sertifikat.index') }}">Matrix Personil</a></li>
    <li class="breadcrumb-item active">Input</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Sertifikat Personil</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                {{-- <form action="{{ route('v1.jenis-sertifikat.store') }}" method="post" enctype="multipart/form-data">
                    @csrf --}}
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="nik_karyawan">Nama Karyawan <span style="color: #ff0000;">*</span></label>
                                <select class="form-control select2 karyawanSelect" name="nik_karyawan" id="nik_karyawan" required>
                                        <option></option>
                                        @foreach ($karyawan as $data)
                                            <option value="{{ $data->id }}">{{ $data->fullName }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6"></div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="email">email</label>
                                <input type="text" class="form-control email" name="email" id="email" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="phoneNumber">Nomor Hp</label>
                                <input type="text" class="form-control nomor" name="phoneNumber" id="phoneNumber" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="namaJabatan">Jabatan</label>
                                <input type="text" class="form-control jabatan" name="namaJabatan" id="namaJabatan" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="namaDepartemen">Departemen</label>
                                <input type="text" class="form-control departemen" name="namaDepartemen" id="namaDepartemen" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div id="daftar-sertifikat">
                            {{-- populate semua data jenis sertifikat disini --}}
                        </div>
                        <div class="col-md-12">
                            <small><b><span style="color: #ff0000;">(*)</span> <em>Wajib Diisi</em></b></small>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12">
                            {{-- <button type="submit" class="btn btn-success">Simpan</button> --}}
                            <a href="{{ route('v1.matrix-personil.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                {{-- </form> --}}
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
<script>
    $(function () {
        //Initialize Select2 Elements
        $('#nik_karyawan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Karyawan',
        })
    });

    // Auto fill data karyawan
    $(document).on('change', '.karyawanSelect', function () {
        let nik_karyawan = $(this).val();
        // let row = $(this).closest('.alat-row');

        if (nik_karyawan) {
            $.ajax({
                url: '/v1/input-sertifikat/karyawan/' + nik_karyawan,
                type: 'GET',
                success: function (data) {
                    $('.email').val(data.karyawan.email);
                    $('.nomor').val(data.karyawan.phoneNumber);
                    $('.jabatan').val(data.karyawan.namaJabatan);
                    $('.departemen').val(data.karyawan.namaDepartemen);
                    $('#daftar-sertifikat').html(data.view);
                    $('.field-nik').val(data.karyawan.nik);
                }
            });
        } else {
            $('.email').val('');
            $('.nomor').val('');
            $('.jabatan').val('');
            $('.departemen').val('');
            $('#nik').val('');
        }
    });

    function SimpanData(id) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('due_date', $('#due_date_' + id).val());
        formData.append('nik', $('#nik_' + id).val());
        formData.append('_token', "{{ csrf_token() }}");

        // ambil file dari input
        let fileInput = document.getElementById('file_serti_' + id);
        if (fileInput.files.length > 0) {
            formData.append('file_serti', fileInput.files[0]);
        }

        $.ajax({
            url: "{{ route('v1.input-sertifikat.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                Swal.fire("Success!", response.message, "success");

                // 🔥 Disable input file
                $('#file_serti_' + id).prop('disabled', true);

                // 🔥 Disable due date
                $('#due_date_' + id).prop('disabled', true);

                // 🔥 Hapus tombol submit & ganti icon check
                $('#action_area_' + id).html(`
                    <i class="fas fa-check-square text-success"></i>
                `);
            },
            error: function (xhr) {
                Swal.fire("Error!", "Pastikan File Sertifikat dan Due Date diisi dengan benar", "error");
            },
        });
    }



</script>
@endsection
