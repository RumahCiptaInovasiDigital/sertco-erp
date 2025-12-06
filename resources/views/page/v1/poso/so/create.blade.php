@extends('layouts.master')
@section('title', 'New SO Request')
@section('PageTitle', 'New SO Request')
@section('head')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.poso-request.so.index') }}">SO Request</a></li>
    <li class="breadcrumb-item active">New SO Request</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New SO Request</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.poso-request.so.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vendor">Vendor <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="vendor" id="vendor" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Vendor --</option>
                                        @foreach ($vendor as $v)
                                            <option value="{{ $v->id_vendor }}" {{ old('vendor') == $v->id_vendor ? 'selected' : '' }}>{{ $v->nama_vendor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_dibutuhkan">Tanggal Dibutuhkan <span style="color: #ff0000;">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan') }}" id="tanggal_dibutuhkan" placeholder="Masukkan Tanggal Dibutuhkan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenis_pekerjaan">Jenis Pekerjaan <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" name="jenis_pekerjaan" value="{{ old('jenis_pekerjaan') }}" id="jenis_pekerjaan" placeholder="Masukkan Jenis Pekerjaan">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi SO <span style="color: #ff0000;">*</span></label>
                                    <textarea class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" id="deskripsi" placeholder="Masukkan Deskripsi" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimasi_jasa">Estimasi Biaya Jasa <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control" style="text-align: right;" name="estimasi_jasa" value="{{ old('estimasi_jasa') }}" id="estimasi_jasa" placeholder="Masukkan Estimasi Biaya Jasa" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimasi_material">Estimasi Biaya Material</label>
                                    <input type="text" class="form-control" style="text-align: right;" name="estimasi_material" value="{{ old('estimasi_material') }}" id="estimasi_material" placeholder="Masukkan Estimasi Biaya Jasa" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_lampiran">File Lampiran (.pdf) <span style="color: #ff0000;">*</span></label>
                                    <input type="file" class="form-control" name="file_lampiran" id="file_lampiran">
                                    <em><b>File wajib PDF (.pdf) maksimal 5Mb</b></em>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project">Project</label>
                                    <select class="form-control select2" name="project" id="project" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Project --</option>
                                        @foreach ($project as $p)
                                            <option value="{{ $p->id_project }}" {{ old('project') == $p->id_project ? 'selected' : '' }}>{{ $p->project_no }}</option>
                                        @endforeach
                                    </select>
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

<script type="text/javascript">
    var estimasi_jasa = document.getElementById('estimasi_jasa');
        estimasi_jasa.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        estimasi_jasa.value = formatRupiah(this.value, 'Rp. ');
    });

    var estimasi_material = document.getElementById('estimasi_material');
        estimasi_material.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        estimasi_material.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection
