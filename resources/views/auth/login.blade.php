@extends('layouts.login')

@section('css_files')

@endsection


@section('content')
 <login></login>
@endsection

@section('js_files')
    <script src="{{ mix('js/main.js') }}"></script>
    <script src="{{ mix('dist/js/app.js') }}"></script>
@endsection
