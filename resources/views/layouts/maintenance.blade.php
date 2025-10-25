<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../" />
    <title>Under Maintenance System! | Sertco Quality</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('dist/img/sq-logo.jpg') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<!--end::Head-->
<body>
    <div class="wrapper">
        <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
            <div class="container">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body py-15 text-center">
                            <h1 class="mt-5"><b>Maintenance Mode</b></h1>
                            <p class="mt-2 mb-4 text-muted">
                                The page you are looking was moved, removed,<br>
                                renamed, or might never exist!
                            </p>
                            <p>
                                {{ $data->reason }}
                            </p>
                            <div class="mb-11">
                                <img src="{{ asset('dist/img/maintenance.png') }}" class="mw-100 mh-300px theme-light-show"
                                    alt="" />
                            </div>
                            <div class="mb-5">
                                <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
