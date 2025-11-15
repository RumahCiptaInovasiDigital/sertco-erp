@extends('layouts.master')
@section('title', 'Edit Notification')
@section('PageTitle', 'Edit Notification')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.notification.index') }}">Notification</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Notification</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('admin.notification.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input class="form-control" name="title" id="title" placeholder="Input Judul" value="{{ $data->title }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Message</label>
                                <textarea class="form-control" name="pesan" id="pesan" rows="4" placeholder="Input Message">{{ $data->pesan }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="departemen">Jenis Notifikasi</label>
                                        <select class="form-control select2" name="jenis_notifikasi" id="jenis_notifikasi">
                                            <option value=""></option>
                                            <option value="sekali" {{ $data->jenis_notifikasi == 'sekali' ? 'selected' : ''}}>Hanya Sekali</option>
                                            <option value="daily" {{ $data->jenis_notifikasi == 'daily' ? 'selected' : ''}}>Setiap Hari</option>
                                            <option value="weekly" {{ $data->jenis_notifikasi == 'weekly' ? 'selected' : ''}}>Setiap Minggu</option>
                                            <option value="monthly" {{ $data->jenis_notifikasi == 'monthly' ? 'selected' : ''}}>Setiap Bulan</option>
                                            <option value="yearly" {{ $data->jenis_notifikasi == 'yearly' ? 'selected' : ''}}>Setiap Tahun</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12" id="opsi_tambahan"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
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
                        <div class="col-12">
                            <hr class="my-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Karyawan Penerima Notifikasi</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="dt_data" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Nama Karaywan</th>
                                                <th width="10%">Waktu Notifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                            <button type="submit" class="btn btn-success">Update Data</button>
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
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    const _URL = "{{ route('admin.notification.getEmployee', $data->id) }}";

    $(document).ready(function () {
        $('.page-loading').fadeIn();
        setTimeout(function () {
            $('.page-loading').fadeOut();
        }, 1000); // Adjust the timeout duration as needed

        let DT = $("#dt_data").DataTable({
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
                { data: "penerima" },
                { data: "diterima" },
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

        $('#karyawan').prop('disabled', true);
        //Initialize Select2 Elements
        $('#karyawan').select2({
            theme: 'bootstrap4',
            minimumInputLength: 2,
            placeholder: 'Cari dan pilih karyawan...',
            ajax: {
                url: "{{ route('admin.notification.searchEmployee') }}", // route baru
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
                        <input type="time" class="form-control" name="jam_notifikasi" value={{ $data->jam_notifikasi }}>
                    </div>
                `;
            } else if (value === 'weekly') {
                html = `
                    <div class="form-group">
                        <label>Pilih Hari</label>
                        <select class="form-control" name="hari_notifikasi">
                            <option value="Senin" {{ $data->hari_notifikasi == 'Senin' ? 'Selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ $data->hari_notifikasi == 'Selasa' ? 'Selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ $data->hari_notifikasi == 'Rabu' ? 'Selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ $data->hari_notifikasi == 'Kamis' ? 'Selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ $data->hari_notifikasi == 'Jumat' ? 'Selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ $data->hari_notifikasi == 'Sabtu' ? 'Selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ $data->hari_notifikasi == 'Minggu' ? 'Selected' : '' }}>Minggu</option>
                        </select>
                    </div>
                `;
            } else if (value === 'monthly') {
                html = `
                    <div class="form-group">
                        <label>Tanggal Notifikasi</label>
                        <input type="number" class="form-control" name="tanggal_notifikasi" min="1" max="31" placeholder="Misal: 15" value={{ $data->tanggal_notifikasi }}>
                    </div>
                `;
            } else if (value === 'yearly') {
                html = `
                    <div class="form-group">
                        <label>Bulan & Tanggal Notifikasi</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="bulan_notifikasi">
                                    <option value="1" {{ $data->bulan_notifikasi == '1' ? 'Selected' : '' }}>Januari</option>
                                    <option value="2" {{ $data->bulan_notifikasi == '2' ? 'Selected' : '' }}>Februari</option>
                                    <option value="3" {{ $data->bulan_notifikasi == '3' ? 'Selected' : '' }}>Maret</option>
                                    <option value="4" {{ $data->bulan_notifikasi == '4' ? 'Selected' : '' }}>April</option>
                                    <option value="5" {{ $data->bulan_notifikasi == '5' ? 'Selected' : '' }}>Mei</option>
                                    <option value="6" {{ $data->bulan_notifikasi == '6' ? 'Selected' : '' }}>Juni</option>
                                    <option value="7" {{ $data->bulan_notifikasi == '7' ? 'Selected' : '' }}>Juli</option>
                                    <option value="8" {{ $data->bulan_notifikasi == '8' ? 'Selected' : '' }}>Agustus</option>
                                    <option value="9" {{ $data->bulan_notifikasi == '9' ? 'Selected' : '' }}>September</option>
                                    <option value="10" {{ $data->bulan_notifikasi == '10' ? 'Selected' : '' }}>Oktober</option>
                                    <option value="11" {{ $data->bulan_notifikasi == '11' ? 'Selected' : '' }}>November</option>
                                    <option value="12" {{ $data->bulan_notifikasi == '12' ? 'Selected' : '' }}>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="tanggal_notifikasi" min="1" max="31" placeholder="Tanggal" value={{ $data->tanggal_notifikasi }}>
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
