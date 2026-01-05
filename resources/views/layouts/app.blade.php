<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
    <head>
        <meta charset="utf-8" />
        <title>Fast | Mark - Bluebook Renewal Service</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Bluebook Renewal Service" name="description" />
        <meta content="Yantra" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
       
        <!-- Sweet Alert css-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Layout config Js -->
        <script src="{{ asset('assets/js/layout.js') }}"></script>
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

        <!--Toastr css-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Nepali Datepicker CSS -->
        <link href="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/css/nepali.datepicker.v5.0.6.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('layouts.partials._header')

            @include('layouts.partials._sidebar')
            
            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                       @yield('content')
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

               @include('layouts.partials._footer')
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->
        
        
        <!-- JAVASCRIPT -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
        <script src="{{ asset('assets/js/plugins.js')}}"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/sweetalerts.init.js') }}"></script>

        <!-- Nepali datepicker -->
        <script src="https://nepalidatepicker.sajanmaharjan.com.np/v5/nepali.datepicker/js/nepali.datepicker.v5.0.6.min.js"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js')}}"></script>

        <!-- Toastr js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 3000,
                positionClass: "toast-top-right",
                preventDuplicates: true,
                showDuration: 300,
                hideDuration: 1000,
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            @if(session()->has('toastr'))
                const toast = @json(session('toastr'));
                switch(toast.type){
                    case 'success':
                        toastr.success(toast.message);
                        break;
                    case 'error':
                        toastr.error(toast.message);
                        break;
                    case 'info':
                        toastr.info(toast.message);
                        break;
                    case 'warning':
                        toastr.warning(toast.message);
                        break;
                    default:
                        toastr.info(toast.message);
                }
            @endif

            @if($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error(@json($error));
                @endforeach
            @endif
        </script>


        @stack('script_2')

    </body>
</html>