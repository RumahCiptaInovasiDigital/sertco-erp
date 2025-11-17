@extends('layouts.master')
@section('title', 'Edit Suplier')
@section('PageTitle', 'Edit Suplier')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.suplier.index') }}">Suplier</a></li>
    <li class="breadcrumb-item active">Edit Suplier</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Suplier</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.suplier.update', $suplier->id_suplier) }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama">Nama Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $suplier->nama_suplier) }}" id="nama" placeholder="Masukkan Nama Suplier">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telp">Telp Suplier</label>
                                    <input type="text" class="form-control" name="telp" value="{{ old('telp', $suplier->telp_suplier) }}" id="telp" placeholder="Masukkan Telp Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="email" value="{{ old('email', $suplier->email_suplier) }}" id="email" placeholder="Masukkan Email Suplier">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat">Alamat Suplier <span style="color: #ff0000;">*</span></label>
                                    <textarea class="form-control" name="alamat" value="{{ old('alamat') }}" id="alamat" placeholder="Masukkan Alamat Suplier" rows="3">{{ $suplier->alamat_suplier }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="norek">Norek Suplier</label>
                                    <input type="text" class="form-control" name="norek" value="{{ old('norek', $suplier->norek_suplier) }}" id="norek" placeholder="Masukkan Nomor Rekening Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank">Nama Bank</label>
                                    <input type="text" class="form-control" name="bank" value="{{ old('bank', $suplier->bank_suplier) }}" id="bank" placeholder="Masukkan Bank Suplier">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak">Nama Kontak</label>
                                    <input type="text" class="form-control" name="kontak" value="{{ old('kontak', $suplier->nama_kontak) }}" id="kontak" placeholder="Masukkan Kontak Suplier">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hp_kontak">No. HP Kontak</label>
                                    <input type="text" class="form-control" name="hp_kontak" value="{{ old('hp_kontak', $suplier->nohp_kontak) }}" id="hp_kontak" placeholder="Masukkan No. HP Kontak Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
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
                                <a href="{{ route('v1.suplier.index') }}"><button type="button" class="btn btn-warning btn-sm">Batal</button></a>
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
