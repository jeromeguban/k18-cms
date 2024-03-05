<!DOCTYPE html>
<html lang="en" class="{{ $dark_mode ? 'dark' : '' }}">
<head>
    <meta name="description" content="CMS">
    <meta name="keywords" content="CMS">
    <meta name="author" content="Jerome Edward Guban">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">  --}}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="X-CSRF-Token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>CMS</title>

    @yield('css_files')

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ mix('dist/css/app.css') }}" rel="stylesheet">


</head>
<body class="overflow font-sans text-xs">
    <div id="app" style="padding: 0">
        @yield('content')
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDoF3SWZis0LJVj2oti0O3upIkodopvhAc"></script>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken'     => csrf_token(),
            'base_url'      => url('/'),
            'user'          => auth()->user() ?  auth()->user() : null,
            'permissions'   => auth()->user() ?  auth()->user()->getPermissions() : null,
            'store'         => session()->get('store_id'),
            'notifications' => auth()->user()->getActiveNotificationChannels(),
            'admin'         => auth()->user()->validateIfDeveloper()
        ]) !!};

        window.notification_server_url = "{{ env('NOTIFICATION_SERVER_URL' )}}";

        window.simulcast_bidding_url = "{{ env('SIMULCAST_BIDDING_URL' )}}";
        window.ams_url = "{{ env('AMS_URL' )}}";

    </script>
     {{-- <script src="{{ mix('dist/js/app.js') }}"></script> --}}

    @yield('js_files')
</body>
</html>
