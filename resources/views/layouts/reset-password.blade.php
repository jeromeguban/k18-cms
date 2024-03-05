<!DOCTYPE html>
<html lang="en" class="{{ $dark_mode ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <link href="../images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="HMR.ph CMS">
    <meta name="keywords" content="HMR.ph CMS">
    <meta name="author" content="HMR.ph">
    <title>Reset Password to CMS</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- @yield('css_files') --}}

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('dist/css/app.css') }}" rel="stylesheet">

</head>

<body class="login">
    <div id="app">
        @yield('content')
    </div>

    <!-- BEGIN: JS Assets-->
    <script src="{{ mix('dist/js/app.js') }}"></script>
    <!-- END: JS Assets-->
    @yield('js_files')
    @yield('script')
</body>

</html>