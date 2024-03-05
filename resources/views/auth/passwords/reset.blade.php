@extends('layouts.reset-password')

@section('css_files')

@endsection

@section('content')

    <div>
        <reset-password token="{{$token}}" email="{{request()->email}}"></reset-password>
        @include('../dark-mode-switcher')
    </div>

@endsection

@section('js_files')
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ mix('dist/js/app.js') }}"></script>
@endsection
