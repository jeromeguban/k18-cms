<!DOCTYPE html>
<html>

<head>
    <meta name="description" content="CMS">
    <meta name="keywords" content="CMS">
    <meta name="author" content="Jerome Edward Guban">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>CMS</title>

    @yield('css_files')

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ mix('dist/css/app.css') }}" rel="stylesheet">


</head>

<body class="overflow text-xs font-normal" style="padding: 0; !important">
    <div id="app">
        @yield('content')
    </div>
    {{-- <script src="{{ mix('dist/js/app.js') }}"></script> --}}

    @yield('js_files')
</body>

</html>
