@extends('layouts.master')
@section('title', 'Detail Barang')
@section('PageTitle', 'Detail Barang')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.barang.kategori.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">Detail Barang</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Barang</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Barang</label><br />
                                {{ $barang->nama_barang }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_perolehan">Tanggal Perolehan</label><br />
                                {{ $barang->tanggal_perolehan }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="deskripsi_barang">Deskripsi Barang</label><br />
                                {{ $barang->deskripsi_barang }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_barang">Kategori Barang</label><br />
                                {{ $barang->hasKategori->nama_kategori }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_barang">Jumlah Barang</label><br />
                                {{ $barang->qty_barang }} {{ $barang->hasSatuan->satuan }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_barang">Status Barang</label><br />
                                <?php
                                    if ($barang->status_barang == "1") {
                                        echo '<span class="badge badge-success">Baik</span>';
                                    } elseif ($barang->status_barang == "2") {
                                        echo '<span class="badge badge-secondary">Rusak Ringan</span>';
                                    } elseif ($barang->status_barang == "3") {
                                        echo '<span class="badge badge-danger">Rusak Berat</span>';
                                    } elseif ($barang->status_barang == "4") {
                                        echo '<span class="badge badge-primary">Sedang Digunakan</span>';
                                    } elseif ($barang->status_barang == "5") {
                                        echo '<span class="badge badge-info">Dipinjam</span>';
                                    } elseif ($barang->status_barang == "6") {
                                        echo '<span class="badge badge-warning">Sedang Diperbaiki</span>';
                                    } else {
                                        echo '<span class="badge badge-dark">Hilang</span>';
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_kepemilikan">Status Kepemilikan</label><br />
                                <?php
                                    if ($barang->status_kepemilikan == "1") {
                                        echo '<span class="badge badge-primary">Sertco Quality</span>';
                                    } else {
                                        echo '<span class="badge badge-info">Karyawan</span>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="karyawan">Digunakan Oleh</label><br />
                                {{ $barang->nik == null ? '-' : $barang->nik }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_maintenance">Last Maintenance</label><br />
                                {{ $barang->last_maintenance == null ? '-' : $barang->last_maintenance }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <img src="{{ asset('assets/qr-code-barang/qr-barang-'.$barang->kode_barang.'.png') }}" alt="QR Code Barang" width="200px">
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('v1.barang.master.index') }}"><button type="button" class="btn btn-info btn-sm">Kembali</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    });
</script>
<script type="text/javascript">
function getkey(e)
{
if (window.event)
return window.event.keyCode;
else if (e)
return e.which;
else
return null;
}
function goodchars(e, goods, field)
{
var key, keychar;
key = getkey(e);
if (key == null) return true;

keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();
goods = goods.toLowerCase();

// check goodkeys
if (goods.indexOf(keychar) != -1)
return true;
// control keys
if ( key==null || key==0 || key==8 || key==9 || key==27 )
return true;

if (key == 13) {
var i;
for (i = 0; i < field.form.elements.length; i++)
if (field == field.form.elements[i])
break;
i = (i + 1) % field.form.elements.length;
field.form.elements[i].focus();
return false;
};
// else return false
return false;
}
</script>
@endsection
