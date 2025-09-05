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
                       @section('content')
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
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
        <script src="{{ asset('assets/js/plugins.js')}}"></script>
        <!-- apexcharts -->
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
        <!-- Dashboard init -->
        <script src="{{ asset('assets/js/pages/dashboard-crm.init.js')}}"></script>
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js')}}"></script>
    </body>
</html>