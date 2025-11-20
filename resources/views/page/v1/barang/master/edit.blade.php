@extends('layouts.master')
@section('title', 'Edit Barang')
@section('PageTitle', 'Edit Barang')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.barang.kategori.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">Edit Barang</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Barang</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.barang.master.update', $barang->id_barang) }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Barang <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $barang->nama_barang) }}" id="nama" placeholder="Masukkan Nama Barang">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_perolehan">Tanggal Perolehan <span style="color: #ff0000;">*</span></label>
                                    <input type="date" class="form-control" name="tgl_perolehan" value="{{ old('tgl_perolehan', $barang->tanggal_perolehan) }}" id="tgl_perolehan" placeholder="Masukkan Tanggal Perolehan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi_barang">Deskripsi Barang <span style="color: #ff0000;">*</span></label>
                                    <textarea class="form-control" name="deskripsi_barang" value="{{ old('deskripsi_barang', $barang->deskripsi_barang) }}" id="deskripsi_barang" placeholder="Masukkan Deskripsi Barang" rows="5">{{ $barang->deskripsi_barang }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_barang">Jumlah Barang <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="jumlah_barang" value="{{ old('jumlah_barang', $barang->qty_barang) }}" id="jumlah_barang" placeholder="Masukkan Jumlah Barang" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="satuan_barang">Satuan Barang <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="satuan_barang" id="satuan_barang">
                                        <option value="" disabled>-- pilih salah satu --</option>
                                        @foreach ($satuan as $item)
                                            <option value="{{ $item->id_satuan_barang }}" {{ $barang->id_satuan_barang ==  $item->id_satuan_barang ? "selected" : '' }}>{{ $item->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kategori_barang">Kategori Barang <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="kategori_barang" id="kategori_barang">
                                        <option value="" disabled>-- pilih salah satu --</option>
                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->id_kategori_barang }}" {{ $barang->id_kategori_barang ==  $item->id_kategori_barang ? "selected" : '' }}>{{ $item->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_barang">Status Barang <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="status_barang" id="status_barang">
                                        <option value="" disabled>-- pilih salah satu --</option>
                                        <option value="1" {{ $barang->status_barang ==  "1"  ? "selected" : '' }}>Baik</option>
                                        <option value="2" {{ $barang->status_barang ==  "2"  ? "selected" : '' }}>Rusak Ringan</option>
                                        <option value="3" {{ $barang->status_barang ==  "3"  ? "selected" : '' }}>Rusak Berat</option>
                                        <option value="4" {{ $barang->status_barang ==  "4"  ? "selected" : '' }}>Digunakan</option>
                                        <option value="5" {{ $barang->status_barang ==  "5"  ? "selected" : '' }}>Dipinjam</option>
                                        <option value="6" {{ $barang->status_barang ==  "6"  ? "selected" : '' }}>Diperbaiki</option>
                                        <option value="7" {{ $barang->status_barang ==  "7"  ? "selected" : '' }}>Hilang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_kepemilikan">Status Kepemilikan <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="status_kepemilikan" id="status_kepemilikan">
                                        <option value="" disabled>-- pilih salah satu --</option>
                                        <option value="1" {{ $barang->status_kepemilikan ==  "1"  ? "selected" : '' }}>Sertco Quality</option>
                                        <option value="2" {{ $barang->status_kepemilikan ==  "2"  ? "selected" : '' }}>Karyawan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="karyawan">Digunakan Oleh</label>
                                    <select class="form-control select2" name="karyawan" id="karyawan">
                                        <option value=""  {{ $barang->nik ==  null ?? "selected" }}>-- pilih salah satu --</option>
                                        @foreach ($data_karyawan as $item)
                                            <option value="{{ $item->nik }}" {{ $barang->nik ==  $item->nik ? "selected" : ''  }}>{{ $item->nik." - ".$item->fullName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_maintenance">Last Maintenance</label>
                                    <input type="date" class="form-control" name="last_maintenance" value="{{ old('last_maintenance', $barang->last_maintenance) }}" id="last_maintenance" placeholder="Masukkan Terakhir Maintenance">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <small><b><span style="color: #ff0000;">(*)</span> <em>Wajib Diisi</em></b></small>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                <a href="{{ route('v1.barang.master.index') }}"><button type="button" class="btn btn-warning btn-sm">Batal</button></a>
                            </div>
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
