@extends('layouts.master')
@section('title', 'Akses Dibatasi')
@section('PageTitle', '')
@section('head')
@endsection
@section('breadcrumb')
@endsection
@section('content')
<!--begin::Content-->
    <div class="d-flex flex-column flex-center text-center p-10">
        <!--begin::Wrapper-->
        <div class="card card-flush w-lg-650px py-5 shadow">
            <div class="card-body py-15 py-lg-20">
                <!--begin::Title-->
                <h1 class="fw-bolder fs-2qx text-gray-900 mb-4">Access Forbidden</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="fw-semibold fs-4 text-gray-700 mb-7">Kamu Tidak Memiliki Akses <br> Ke Halaman ini!</div>
                <!--end::Text-->
                <!--begin::Illustration-->
                <div class="mb-11">
                    <img src="{{asset('assets/img/403.png')}}" class="mw-100 mh-300px theme-light-show" alt="" />
                </div>
                <!--end::Illustration-->
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
@endsection
