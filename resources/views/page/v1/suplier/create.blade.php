@extends('layouts.master')
@section('title', 'Tambah Suplier')
@section('PageTitle', 'Tambah Suplier')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.suplier.index') }}">Suplier</a></li>
    <li class="breadcrumb-item active">Tambah Suplier</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Suplier</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.suplier.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis">Jenis Suplier <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="jenis" id="jenis" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Jenis Suplier --</option>
                                        @foreach($jenisSuplier as $item)
                                        <option value="{{ $item->id_jenis_suplier }}" {{ old('jenis') == $item->id_jenis_suplier ? 'selected' : '' }}>{{ $item->nama_jenis_suplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" id="nama" placeholder="Masukkan Nama Suplier">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat">Alamat Suplier <span style="color: #ff0000;">*</span></label>
                                    <textarea class="form-control" name="alamat" value="{{ old('alamat') }}" id="alamat" placeholder="Masukkan Alamat Suplier" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bayar">Cara Pembayaran <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="bayar" id="bayar" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Cara Pembayaran --</option>
                                        <option value="Kredit" {{ old('bayar') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                                        <option value="Tunai" {{ old('bayar') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="syarat">Syarat Pembayaran <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="syarat" id="syarat" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Syarat Pembayaran --</option>
                                        <option value="Sebelum Barang Dikirim" {{ old('syarat') == 'Sebelum Barang Dikirim' ? 'selected' : '' }}>Sebelum Barang Dikirim</option>
                                        <option value="Setelah Barang Diterima" {{ old('syarat') == 'Setelah Barang Diterima' ? 'selected' : '' }}>Setelah Barang Diterima</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nama_kontak">Nama Kontak <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="nama_kontak" value="{{ old('nama_kontak') }}" id="nama_kontak" placeholder="Masukkan Nama Kontak">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan') }}" id="jabatan" placeholder="Masukkan Jabatan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="telp">Telp Suplier</label>
                                    <input type="text" class="form-control" name="telp" value="{{ old('telp') }}" id="telp" placeholder="Masukkan Telp Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hp">HP Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="hp" value="{{ old('hp') }}" id="hp" placeholder="Masukkan HP Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="email" value="{{ old('email') }}" id="email" placeholder="Masukkan Email Suplier">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control" name="website" value="{{ old('website') }}" id="website" placeholder="Masukkan Website">
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="norek">Norek Suplier <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="norek" value="{{ old('norek') }}" id="norek" placeholder="Masukkan Nomor Rekening Suplier" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank">Nama Bank <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="bank" value="{{ old('bank') }}" id="bank" placeholder="Masukkan Bank Suplier">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_pemilik_rek">Nama Pemilik Rekening <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="nama_pemilik_rek" value="{{ old('nama_pemilik_rek') }}" id="nama_pemilik_rek" placeholder="Masukkan Nama Pemilik Rekening">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cabang_bank">Cabang Bank <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="cabang_bank" value="{{ old('cabang_bank') }}" id="cabang_bank" placeholder="Masukkan Cabang Bank">
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_cp"><input type="checkbox" value="cp" name="cp" id="cp" {{ old('cp') ? 'checked' : '' }}> Company Profile</label>
                                    <input type="file" class="form-control" name="file_cp" value="{{ old('file_cp') }}" id="file_cp" @if(!old('cp')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_npwp"><input type="checkbox" value="npwp" name="npwp" id="npwp" {{ old('npwp') ? 'checked' : '' }}> NPWP</label>
                                    <input type="file" class="form-control" name="file_npwp" value="{{ old('file_npwp') }}" id="file_npwp" @if(!old('npwp')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_siup"><input type="checkbox" value="siup" name="siup" id="siup" {{ old('siup') ? 'checked' : '' }}> SIUP/Izin Usaha</label>
                                    <input type="file" class="form-control" name="file_siup" value="{{ old('file_siup') }}" id="file_siup" @if(!old('siup')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_tdp"><input type="checkbox" value="tdp" name="tdp" id="tdp" {{ old('tdp') ? 'checked' : '' }}> TDP/NIB</label>
                                    <input type="file" class="form-control" name="file_tdp" value="{{ old('file_tdp') }}" id="file_tdp" @if(!old('tdp')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_akta"><input type="checkbox" value="akta" name="akta" id="akta" {{ old('akta') ? 'checked' : '' }}> Akta Perusahaan</label>
                                    <input type="file" class="form-control" name="file_akta" value="{{ old('file_akta') }}" id="file_akta" @if(!old('akta')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_domisili"><input type="checkbox" value="domisili" name="domisili" id="domisili" {{ old('domisili') ? 'checked' : '' }}> Surat Domisili</label>
                                    <input type="file" class="form-control" name="file_domisili" value="{{ old('file_domisili') }}" id="file_domisili" @if(!old('domisili')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_sertifikat"><input type="checkbox" value="sertifikat" name="sertifikat" id="sertifikat" {{ old('sertifikat') ? 'checked' : '' }}> Sertifikat ISO/K3/Sejenisnya</label>
                                    <input type="file" class="form-control" name="file_sertifikat" value="{{ old('file_sertifikat') }}" id="file_sertifikat" @if(!old('sertifikat')) disabled @endif>
                                    <small><em><b>File ekstensi PDF max 5 Mb</b></em></small>
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
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                <button type="reset" class="btn btn-warning btn-sm">Batal</button>
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

        // Toggle Company Profile file input based on checkbox
        $('#cp').on('change', function() {
            $('#file_cp').prop('disabled', !this.checked);
        });

        $('#npwp').on('change', function() {
            $('#file_npwp').prop('disabled', !this.checked);
        });

        $('#akta').on('change', function() {
            $('#file_akta').prop('disabled', !this.checked);
        });

        $('#siup').on('change', function() {
            $('#file_siup').prop('disabled', !this.checked);
        });

        $('#tdp').on('change', function() {
            $('#file_tdp').prop('disabled', !this.checked);
        });

        $('#domisili').on('change', function() {
            $('#file_domisili').prop('disabled', !this.checked);
        });

        $('#sertifikat').on('change', function() {
            $('#file_sertifikat').prop('disabled', !this.checked);
        });

        // Ensure initial state on page load (handles old input)
        $('#file_cp').prop('disabled', !$('#cp').is(':checked'));
        $('#file_npwp').prop('disabled', !$('#npwp').is(':checked'));
        $('#file_akta').prop('disabled', !$('#akta').is(':checked'));
        $('#file_siup').prop('disabled', !$('#siup').is(':checked'));
        $('#file_tdp').prop('disabled', !$('#tdp').is(':checked'));
        $('#file_domisili').prop('disabled', !$('#domisili').is(':checked'));
        $('#file_sertifikat').prop('disabled', !$('#sertifikat').is(':checked'));
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
