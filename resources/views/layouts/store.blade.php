<!DOCTYPE html>
<html lang="en" class="text-normal">
<head>
    <meta name="description" content="HMRPH CMS">
    <meta name="keywords" content="CMS">
    <meta name="author" content="Raymart Amoranto">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">  --}}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>CMS</title>

    @yield('css_files')

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ mix('resources/css/app.css') }}" rel="stylesheet">

</head>
<body class="overflow-hidden antialiased font-sans text-xs">
    <div id="app" style="padding: 0">
        @yield('content')
    </div>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken'     => csrf_token(),
            'base_url'      => url('/'),
            'user'          => auth()->user() ?  auth()->user() : null,
            'permissions'   => auth()->user() ?  auth()->user()->getPermissions() : null,
            'store'         => session()->get('store_id'),
            'admin'         => auth()->user()->validateIfDeveloper()
        ]) !!};

    </script>
    @yield('js_files')
</body>
</html>
