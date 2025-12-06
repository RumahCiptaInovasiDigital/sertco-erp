@extends('layouts.master')
@section('title', 'Purchase Order')
@section('PageTitle', 'New Purchase Order')

@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.poso-request.po.index') }}">Purchase Order</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Purchase Order</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('v1.poso-request.po.store') }}" method="post">
                    @csrf
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="suplier">Suplier <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2" name="suplier" id="suplier" style="width: 100%;">
                                        <option value="" disabled selected>-- Pilih Suplier --</option>
                                        @foreach ($suplier as $v)
                                            <option value="{{ $v->id_suplier }}" {{ old('suplier') == $v->id_suplier ? 'selected' : '' }}>{{ $v->nama_suplier }}</option>
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
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi PO <span style="color: #ff0000;">*</span></label>
                                    <textarea class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" id="deskripsi" placeholder="Masukkan Deskripsi" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm mb-3" id="addRow">
                                    + Tambah Barang
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div id="barangRows" class="col-md-12"></div>
                        </div>

                        {{-- Template baris barang --}}
                        <template id="rowTemplate">
                            <div class="row barang-row mb-3 p-2 border rounded">
                                <div class="col-12 col-md-3">
                                    <label>Daftar Barang <span style="color: #ff0000;">*</span></label>
                                    <select class="form-control select2 barangSelect" name="idBarang[]" required>
                                        <option></option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->id_barang }}">{{ $item->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label>Stok Saat Ini</label>
                                    <input type="text" class="form-control stok" name="stok[]" readonly>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label>Request PO <span style="color: #ff0000;">*</span></label>
                                    <input type="text" class="form-control req" name="req[]" onKeyPress="return goodchars(event,'1234567890',this)">
                                </div>

                                <div class="col-12 col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm removeRow float-right"><i class="fas fa-trash"></i> Hapus</button>
                                </div>

                            </div>
                        </template>

                        <div class="row">
                            <div class="col-md-12">
                                <small><b><span style="color: #ff0000;">(*)</span> <em>Wajib Diisi</em></b></small>
                            </div>
                        </div>

                        <hr class="my-3">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                <a href="{{ route('v1.poso-request.po.index') }}"><button type="button" class="btn btn-warning btn-sm">Batal</button></a>
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
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function() {

    // Tambah baris alat
    $('#addRow').click(function () {
        let template = $('#rowTemplate').html();
        $('#barangRows').append(template);

        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Barang'
        });
    });

    // Hapus baris
    $(document).on('click', '.removeRow', function() {
        $(this).closest('.barang-row').remove();
    });

    // Auto fill data barang
    $(document).on('change', '.barangSelect', function () {
        let barangId = $(this).val();
        let row = $(this).closest('.barang-row');

        if (barangId) {
            $.ajax({
                url: '/v1/poso-request/po/item-search/' + barangId,
                type: 'GET',
                success: function (data) {
                    row.find('.stok').val(data.qty_barang);
                }
            });
        } else {
            row.find('.stok').val('');
        }
    });

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
