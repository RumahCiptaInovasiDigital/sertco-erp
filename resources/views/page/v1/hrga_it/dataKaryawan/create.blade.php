@extends('layouts.master')
@section('title', 'Peralatan')
@section('PageTitle', 'Tambah Data Karyawan')
@section('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('v1.data-peralatan.index') }}">DataKaryawan</a></li>
    <li class="breadcrumb-item active">Create</li>
</ol>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Karyawan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('v1.data-karyawan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div id="accordion" class="w-100">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" type="button" data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            Data Personal
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="firstName">Nama Depan</label>
                                                    <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Nama Depan">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="lastName">Nama Belakang</label>
                                                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Nama Belakang">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="alamat">Alamat</label>
                                                    <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3" placeholder="Alamat Rumah"></textarea>
                                                    {{-- <input type="text" class="form-control" name="name" id="name" placeholder="Input Nama Peralatan"> --}}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="pendidikan">Pendidikan</label>
                                                    <input type="text" class="form-control" name="pendidikan" id="pendidikan" placeholder="Pendidikan Terakhir">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="tempatLahir">Tempat Lahir</label>
                                                    <input type="text" class="form-control" name="tempatLahir" id="tempatLahir" placeholder="Tempat Lahir">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggalLahir">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir" placeholder="Tanggal Lahir">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email pribadi">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="phoneNumber">Nomor Handphone</label>
                                                    <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Nomor Handphone Pribadi">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="agama">Agama</label>
                                                    <select class="form-control select2" name="agama" id="agama">
                                                        <option></option>
                                                        <option value="Islam">Islam</option>
                                                        <option value="Kristen">Kristen</option>
                                                        <option value="Hindu">Hindu</option>
                                                        <option value="Buddha">Buddha</option>
                                                        <option value="Konghucu">Konghucu</option>
                                                        <option value="lainnya">Lainnya</option>
                                                    </select>
                                                    <input type="text" class="form-control mt-2 d-none" name="agamaLain" id="agamaLain" placeholder="Masukkan Agama Lainnya">
                                                    {{-- <input type="text" class="form-control" name="agama" id="agama" placeholder="Agama"> --}}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="foto">Foto</label>
                                                    <input type="file" class="form-control" name="foto" id="foto" accept="image/*,.jpg,.jpeg,.png" placeholder="Input Nama Peralatan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" type="button"
                                            data-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            Dokumen Pendukung
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="noKTP">No KTP</label>
                                                    <input type="text" class="form-control" name="noKTP" id="noKTP"  placeholder="No KTP">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="fileKTP">Foto KTP</label>
                                                    <input type="file" class="form-control" name="fileKTP" id="fileKTP" accept="image/*,.jpg,.jpeg,.png" placeholder="File KTP">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="noSIM">No SIM</label>
                                                    <input type="text" class="form-control" name="noSIM" id="noSIM" placeholder="No SIM">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="fileSIM">Foto SIM</label>
                                                    <input type="file" class="form-control" name="fileSIM" id="fileSIM" accept="image/*,.jpg,.jpeg,.png" placeholder="File SIM">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="noNPWP">No NPWP</label>
                                                    <input type="text" class="form-control" name="noNPWP" id="noNPWP" placeholder="No NPWP">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="fileNPWP">Foto NPWP</label>
                                                    <input type="file" class="form-control" name="fileNPWP" id="fileNPWP" accept="image/*,.jpg,.jpeg,.png" placeholder="File NPWP">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="ijazah">File Ijazah</label>
                                                    <input type="file" class="form-control" name="ijazah" id="ijazah" accept=".pdf" placeholder="File NPWP">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="noRekening">No Rekening</label>
                                                    <input type="text" class="form-control" name="noRekening" id="noRekeneing" placeholder="No Rekening">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="statusTK">Status TK</label>
                                                    <select class="form-control select2" name="statusTK" id="statusTK">
                                                        <option></option>
                                                        <option value="PKWT">PKWT/Kontrak</option>
                                                        <option value="PKWTT">PKWTT/Tetap</option>
                                                        <option value="FreeLance">FreeLance</option>
                                                    </select>
                                                    {{-- <input type="text" class="form-control" name="noRekeneing" id="noRekeneing" placeholder="No Rekening"> --}}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="statusPTKP">Status PTKP</label>
                                                    <select class="form-control select2" name="statusPTKP" id="statusPTKP">
                                                        <option></option>
                                                        <option value="PTKP">PTKP</option>
                                                        <option value="Non PTKP">Non PTKP</option>
                                                    </select>
                                                    {{-- <input type="text" class="form-control" name="noRekeneing" id="noRekeneing" placeholder="No Rekening"> --}}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="nppBpjsTk">NPP BPJS TK</label>
                                                    <input type="text" class="form-control" name="nppBpjsTk" id="nppBpjsTk" placeholder="NPP BPJS TK">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="BpjsKes">BPJS Kesehatan</label>
                                                    <select class="form-control select2" name="BpjsKes" id="BpjsKes">
                                                        <option></option>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                    {{-- <input type="text" class="form-control" name="noRekeneing" id="noRekeneing" placeholder="No Rekening"> --}}
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="AXA">AXA</label>
                                                    <select class="form-control select2" name="AXA" id="AXA">
                                                        <option></option>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                    {{-- <input type="text" class="form-control" name="noRekeneing" id="noRekeneing" placeholder="No Rekening"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" type="button"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Kontak Darurat
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="emergencyContact">Nomor Kontak Darurat</label>
                                                    <input type="text" class="form-control" name="emergencyContact" id="emergencyContact" placeholder="Nomor Kontak Darurat">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="emergencyName">Nama Kontak Darurat</label>
                                                    <input type="text" class="form-control" name="emergencyName" id="emergencyName" placeholder="Nama Kontak Darurat">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="emergencyRelation">Hubungan Kontak Darurat</label>
                                                    <select class="form-control select2" name="emergencyRelation" id="emergencyRelation">
                                                        <option></option>
                                                        <option value="Suami">Suami</option>
                                                        <option value="Istri">Istri</option>
                                                        <option value="Orang Tua">Orang Tua</option>
                                                        <option value="Saudara">Saudara</option>
                                                        <option value="Teman">Teman</option>
                                                    </select>
                                                    {{-- <input type="text" class="form-control" name="inisial" id="inisial" placeholder="Inisial Karyawan Max = 3 huruf"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" type="button"
                                            data-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                            Data Perusahaan
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="inisial">Inisial Karyawan</label>
                                                    <input type="text" class="form-control" name="inisial" id="inisial" placeholder="Inisial Karyawan Max = 3 huruf">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="grade">Grade</label>
                                                    <select class="form-control select2" name="grade" id="grade">
                                                        <option></option>
                                                        <option value="I">I</option>
                                                        <option value="II">II</option>
                                                        <option value="III">III</option>
                                                        <option value="IV">IV</option>
                                                        <option value="IVA">IVA</option>
                                                        <option value="IVB">IVB</option>
                                                        <option value="V">V</option>
                                                        <option value="VI">VI</option>
                                                        <option value="VII">VII</option>
                                                        <option value="VIII">VIII</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="idDepartemen">Departemen</label>
                                                    <select class="form-control select2" name="idDepartemen" id="departemen">
                                                        <option></option>
                                                        @foreach ($departemen as $item)
                                                            <option value="{{ $item->id_departemen }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="idJabatan">Jabatan</label>
                                                    <select class="form-control select2" name="idJabatan" id="jabatan">
                                                        <option></option>
                                                        @foreach ($role as $item)
                                                            <option value="{{ $item->id_role }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="empDateStart">Tanggal Masuk Karyawan</label>
                                                    <input type="date" class="form-control" name="empDateStart" id="empDateStart" placeholder="Nama Belakang">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="empDateEnd">Tanggal Selesai Karyawan</label>
                                                    <input type="date" class="form-control" name="empDateEnd" id="empDateEnd" placeholder="Nama Belakang">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('v1.data-karyawan.index') }}" class="btn btn-secondary">Cancel</a>
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
        $('#agama').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Agama',
        });

        // Ketika pilihan agama berubah
        $('#agama').on('change', function () {
            if ($(this).val() === 'lainnya') {
                $('#agamaLain').removeClass('d-none').attr('required', true);
            } else {
                $('#agamaLain').addClass('d-none').val('').removeAttr('required');
            }
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#statusTK').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status TK',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#statusPTKP').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status PTKP',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#BpjsKes').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Bpjs',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#AXA').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status AXA',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#emergencyRelation').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Status Hubungan Kontak',
        });
    });

    $(function () {
        //Initialize Select2 Elements
        $('#grade').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Grade',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#departemen').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Departemen',
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $('#jabatan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Jabatan',
        })
    });

</script>
@endsection
