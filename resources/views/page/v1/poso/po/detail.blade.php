@extends('layouts.master')
@section('title', 'Purchase Order Detail')
@section('PageTitle', 'Purchase Order Detail')

@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.poso-request.po.index') }}">Purchase Order</a></li>
    <li class="breadcrumb-item active">Detail</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Purchase Order Detail</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Suplier</span>
                                        <span class="info-box-number text-center text-muted mb-0">2300</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Tanggal Dibutuhkan</span>
                                        <span class="info-box-number text-center text-muted mb-0">2000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">No. Project</span>
                                        <span class="info-box-number text-center text-muted mb-0">20</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>PO Request By</h4>
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="{{ asset('dist/img/avatar5.png')}}">
                                        <span class="username">
                                        <a href="#">Jonathan Burke Jr.</a>
                                        </span>
                                        <span class="description">Shared publicly - 7:45 PM today</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore.
                                    </p>

                                    <p>
                                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <div class="card card-primary card-outline direct-chat direct-chat-primary shadow-none">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-paw"></i> Log PO Request</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- Conversations are loaded here -->
                                <div class="direct-chat-messages">
                                    <!-- Message. Default to the left -->
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">Alexander Pierce</span>
                                            <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{ asset('dist/img/avatar5.png')}}">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            Status : Pending. <br />
                                            Purchase Order Request dikirim, menunggu approval pimpinan departemen.
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->

                                    <!-- Message to the right -->
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">Sarah Bullock</span>
                                            <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{ asset('dist/img/avatar5.png')}}">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            Status : Draft. <br />
                                            Draft Purchase Order dibuat.
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                                </div>
                                <!--/.direct-chat-messages-->
                            </div>
                            <!--/.direct-chat -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
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
