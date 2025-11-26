<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('dist/img/sq-logo.jpg') }}" />

    <title>@yield('title') | SertcoQuality</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    @yield('head')
</head>

@yield('styles')

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <!--begin::Page loading(append to body)-->
    <div class="page-loading">
        <div class="preloader flex-column justify-content-center align-items-center">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
        </div>
    </div>
    <!--end::Page loading-->

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <div class="row">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </div>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    @include('layouts.notification.notification')
                </li>

                {{-- Fullscreen --}}
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                {{-- Idle Time --}}
                <li class="nav-item">
                    <span class="nav-link">
                        <span id="idle_time" 
                        data-toggle="tooltip" data-placement="bottom" title="Counting Idle Time"
                        class="bg-secondary text-muted py-1 px-2 fw-bold" 
                        style="border-radius: 5px; color: white;">00:00</span>
                    </span>
                </li>

                {{-- Idle Time --}}
                <li class="nav-item">
                    <a class="nav-link py-1" href="{{ route('logout') }}">
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @include('layouts.sidebar')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('PageTitle')</h1>
                        </div>
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                            {{-- Example --}}
                            {{-- <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Starter Page</li>
                            </ol> --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                SertcoQuality &#9829; crafted by RCID
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2025</strong>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Pusher -->
    <script src="{{ asset('dist/js/pusher.min.js') }}"></script>

    @include('layouts.alert')

    <script>
        $(document).ready(function() {
            $('.page-loading').fadeIn();
            setTimeout(function() {
                $('.page-loading').fadeOut();
            }, 1500); // Adjust the timeout duration as needed
        });

        function showLoading() {
            $('#page-loading').fadeIn();
        }
        function hideLoading() {
            $('#page-loading').fadeOut();
        }
        function clearNotif(){
            $.ajax({
                type: 'POST',
                url: '{{ url("user/clear-notifications") }}', // Update this route
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                cache: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notifications Cleared',
                            text: response.message,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong!',
                            text: response.message
                        });
                    }
                },
            });
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000
            });
            @auth    
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            });
            
            var user_id = '{{ Auth::id() }}';
            var channel = pusher.subscribe('user-notification-' + user_id);
            channel.bind('App\\Events\\Notification\\NotificationEvent', function(data) {
                $('.notification-area').load(location.href + ' .notification-area');
                Toast.fire({
                    icon: 'warning',
                    title: 'New Notification Received'
                })
            });
            @endauth
        });
    </script>
    <!--begin::Javascript-->
    @php
    $idleTimeSetting = \App\Models\MaintenanceMode::first();
    $idleMinutes = $idleTimeSetting ? $idleTimeSetting->idle_time : 10; // Default to 10 minutes if not set
    @endphp

    <script>
        let idleTime = 0;
        let idleMin = 0;
        const idleLimit = 3540; // 60 minutes in seconds
        const idleDisplay = document.getElementById('idle_time');

        // Increment the idle time counter every second
        const idleInterval = setInterval(() => {
            idleTime++;

            // Calculate total idle time in minutes and seconds
            const totalIdleTime = idleTime % 3600; // Total seconds in an hour
            const minutes = Math.floor(totalIdleTime / 60);
            const seconds = totalIdleTime % 60;

            // Format minutes and seconds to always show two digits
            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(seconds).padStart(2, '0');

            idleDisplay.textContent = `${formattedMinutes}:${formattedSeconds}`;

            if (minutes >= {{ $idleMinutes }}) {
                window.location.href = '{{ route("logout") }}'; // Update this route as needed
            }
        }, 1000); // Check every second

        // Reset the idle timer on user activity
        const resetIdleTime = () => {
            idleTime = 0;
            idleDisplay.textContent = '00:00'; // Reset display
        };

        // Listen for user activity
        document.addEventListener('mousemove', resetIdleTime);
        document.addEventListener('keypress', resetIdleTime);
        document.addEventListener('click', resetIdleTime);
        document.addEventListener('scroll', resetIdleTime);
        document.addEventListener('touchstart', resetIdleTime);
    </script>

    @yield('scripts')
</body>

</html>
