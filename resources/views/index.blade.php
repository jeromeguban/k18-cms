@extends('layouts.app')

@section('css_files')
@endsection


@section('content')

    <body class="main">
        <mobile-menu></mobile-menu>
        <div class="flex">
            <sidebar></sidebar>
            <div class="content">
                <navbar :store="'{{ $store }}'"></navbar>
                <container :user="'{{ $user }}'"
                    :account_executive="'{{ $account_executive }}'"
                    :developer="'{{ $developer }}'"
                    :url="'{{ $url }}'"
                    name="{{ $name }}"
                    >
                </container>
                @include('../dark-mode-switcher')
            </div>
        </div>

        <popup></popup>
    </body>
@endsection

@section('js_files')
    <script src="{{ mix('js/admin.js') }}"></script>
    {{-- <script src="{{ asset('js/nav.js') }}"></script> --}}
    <script src="https://kit.fontawesome.com/d7525606a0.js" crossorigin="anonymous"></script>
    <script src="{{ mix('dist/js/app.js') }}"></script>
@endsection
