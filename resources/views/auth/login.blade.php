@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<!-- /.login-logo -->
<div class="card card-outline card-dark">
    <div class="card-header text-center">
        <a href="{{ route('v1.dashboard') }}" class="h1"><b style="color: purple;">SQ-ERP</b> System</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="{{ route('authenticate') }}" method="POST" enctype="multipart/form-data" id="loginForm">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><b>NIK</b></span>
                    </div>
                    <input type="text" class="form-control" name="nik" data-inputmask='"mask": "SQ-AAA-999"' data-mask placeholder="SQ-XYZ-000">
                  </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember">
                        <label for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
        </p>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection
