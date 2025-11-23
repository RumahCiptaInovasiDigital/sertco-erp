@extends('layouts.master')
@section('title', 'Peminjaman')
@section('PageTitle', 'Tambah Peminjaman Alat')

@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peminjaman.index') }}">DataPeminjaman</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Peminjaman Alat</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('v1.data-peminjaman.store') }}" method="post">
                    @csrf
                    <div class="row">

                        {{-- Nama --}}
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" value="{{ auth()->user()->fullname }}" class="form-control" readonly>
                            </div>
                        </div>

                        {{-- NIK --}}
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="nik">Nik</label>
                                <input type="text" class="form-control" name="nikUser" value="{{ auth()->user()->nik }}" readonly>
                            </div>
                        </div>

                        {{-- Departemen --}}
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="departemen">Departemen</label>
                                <input type="text" class="form-control" 
                                       value="{{ auth()->user()->karyawan->departemen->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="namaClient">Nama Klien</label>
                                <input type="text" class="form-control" name="namaClient" id="namaClient" placeholder="Nama Client">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="tanggal_pinjam">Tgl Pinjam</label>
                                <input type="date" class="form-control" name="tanggal_pinjam" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="tanggal_kembali">Tgl Pengembalian</label>
                                <input type="date" class="form-control" name="tanggal_kembali" required>
                            </div>
                        </div>

                        {{-- Tombol tambah baris --}}
                        <div class="col-12">
                            <button type="button" class="btn btn-primary mb-3" id="addRow">
                                + Tambah Alat
                            </button>
                        </div>

                        {{-- Container baris alat --}}
                        <div id="alatRows" class="col-12"></div>

                        {{-- Template baris alat --}}
                        <template id="rowTemplate">
                            <div class="row alat-row mb-3 p-2 border rounded">

                                <div class="col-12 col-md-3">
                                    <label>Daftar Alat</label>
                                    <select class="form-control select2 alatSelect" name="idAlat[]" required>
                                        <option></option>
                                        @foreach ($alat as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label>Merk</label>
                                    <input type="text" class="form-control merk" name="merkAlat[]" readonly>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label>Tipe</label>
                                    <input type="text" class="form-control tipe" name="tipeAlat[]" readonly>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label>Serial</label>
                                    <input type="text" class="form-control serial" name="snAlat[]" readonly>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label>Kondisi</label>
                                    <input type="text" class="form-control kondisi" name="kondisiSebelum[]" readonly>
                                </div>

                                <div class="col-12 col-md-1">
                                    <label>Hapus</label>
                                    <button type="button" class="btn btn-danger btn-block removeRow">X</button>
                                </div>

                            </div>
                        </template>

                        {{-- <div class="col-12"><hr></div> --}}

                        <div class="col-12">
                            <button class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.data-peminjaman.index') }}" class="btn btn-secondary">Batal</a>
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
        $('#alatRows').append(template);

        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Alat'
        });
    });

    // Hapus baris
    $(document).on('click', '.removeRow', function() {
        $(this).closest('.alat-row').remove();
    });

    // Auto fill data alat
    $(document).on('change', '.alatSelect', function () {
        let alatId = $(this).val();
        let row = $(this).closest('.alat-row');

        if (alatId) {
            $.ajax({
                url: '/v1/data-peminjaman/alat/' + alatId,
                type: 'GET',
                success: function (data) {
                    row.find('.merk').val(data.merk);
                    row.find('.tipe').val(data.tipe);
                    row.find('.serial').val(data.serial_number);
                    row.find('.kondisi').val(data.kondisi_alat);
                }
            });
        } else {
            row.find('.merk').val('');
            row.find('.tipe').val('');
            row.find('.serial').val('');
            row.find('.kondisi').val('');
        }
    });

});
</script>
@endsection
