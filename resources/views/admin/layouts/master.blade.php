<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Favicon -->
        <link href="{{ asset('assets/admin/images/favicon.png') }}" rel="shortcut icon">
        <!-- Styles -->
        <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet">
        @stack('styles')
        <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="layout-wrapper">
        @include ('admin.partials.header')
        @include ('admin.partials.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include ('admin.partials.footer')
            </div>
        </div>
        <!-- Scripts -->
        <script src="{{ asset('assets/admin/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/app.js') }}"></script>
        <script>var BASE_URL = "{{ url('/') }}";</script>
        @stack('scripts')
    </body>
</html>