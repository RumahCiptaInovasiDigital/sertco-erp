@extends('layouts.master')
@section('title', 'Create Notification')
@section('PageTitle', 'Create Notification')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.notification.index') }}">Notification</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create a New Notification</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('admin.notification.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input class="form-control" name="title" id="title" placeholder="Input Judul">
                            </div>
                            <div class="form-group">
                                <label for="name">Message</label>
                                <textarea class="form-control" name="pesan" id="pesan" rows="4" placeholder="Input Message"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="departemen">Pilih Karyawan</label>
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="allKaryawan" class="jenis_karyawan" name="jenis_karyawan" value="all" checked>
                                                <label for="allKaryawan">Semua</label>
                                            </div>
                                            <div class="icheck-primary d-inline pl-md-4">
                                                <input type="radio" id="selectKaryawan" class="jenis_karyawan" value="selected" name="jenis_karyawan">
                                                <label for="selectKaryawan">Ditentukan</label>
                                            </div>
                                        </div>
                                        <select class="form-control selectEmployee" name="karyawan[]" id="karyawan" multiple="multiple" style="width: 100%;"></select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="departemen">Jenis Notifikasi</label>
                                        <select class="form-control select2" name="jenis_notifikasi" id="jenis_notifikasi">
                                            <option value="sekali">Hanya Sekali</option>
                                            <option value="daily">Setiap Hari</option>
                                            <option value="weekly">Setiap Minggu</option>
                                            <option value="monthly">Setiap Bulan</option>
                                            <option value="yearly">Setiap Tahun</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6" id="opsi_tambahan"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('admin.notification.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#karyawan').prop('disabled', true);

        //Initialize Select2 Elements
        $('#karyawan').select2({
            theme: 'bootstrap4',
            minimumInputLength: 2,
            placeholder: 'Cari dan pilih karyawan...',
            ajax: {
                url: "{{ route('admin.notification.getEmployee') }}", // route baru
                dataType: 'json',
                delay: 200,
                data: function (params) {
                    return {
                        search: params.term // kirim keyword pencarian
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id, // UUID atau NIK
                                text: item.fullName + ' - ' + item.namaJabatan
                            };
                        })
                    };
                },
                cache: true
            }
        });


        $('#jenis_notifikasi').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Jenis Notifikasi',
        })

        $('.jenis_karyawan').on('change', function() {
            if ($('#selectKaryawan').is(':checked')) {
                $('#karyawan').prop('disabled', false);
            } else {
                $('#karyawan').prop('disabled', true);
                $('#karyawan').val(null).trigger('change');
            }
        });

        $('#jenis_notifikasi').on('change', function() {
            const value = $(this).val();
            let html = '';

            if (value === 'daily') {
                html = `
                    <div class="form-group">
                        <label>Jam Notifikasi</label>
                        <input type="time" class="form-control" name="jam_notifikasi">
                    </div>
                `;
            } else if (value === 'weekly') {
                html = `
                    <div class="form-group">
                        <label>Pilih Hari</label>
                        <select class="form-control" name="hari_notifikasi">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                `;
            } else if (value === 'monthly') {
                html = `
                    <div class="form-group">
                        <label>Tanggal Notifikasi</label>
                        <input type="number" class="form-control" name="tanggal_notifikasi" min="1" max="31" placeholder="Misal: 15">
                    </div>
                `;
            } else if (value === 'yearly') {
                html = `
                    <div class="form-group">
                        <label>Bulan & Tanggal Notifikasi</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="bulan_notifikasi">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="tanggal_notifikasi" min="1" max="31" placeholder="Tanggal">
                            </div>
                        </div>
                    </div>
                `;
            }

            $('#opsi_tambahan').html(html);
        });

        // trigger saat load pertama
        $('#jenis_notifikasi').trigger('change');
    });
</script>
@endsection
