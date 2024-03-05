@extends('layouts.forget-password')

@section('css_files')

@endsection

@section('content')
    <div>
        <forgot-password></forgot-password>
        @include('../dark-mode-switcher')
    </div>
@endsection


@section('js_files')
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ mix('dist/js/app.js') }}"></script>
@endsection
