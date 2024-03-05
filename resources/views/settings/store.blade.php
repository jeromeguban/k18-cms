@extends('layouts.app')

@section('css_files')

@endsection


@section('content')
        <div class="w-full">
        	<store-selection :stores="{{ auth()->user()->stores }}"></store-selection>
        </div>
@endsection

@section('js_files')
	<script src="{{ mix('js/admin.js') }}"></script>
	<script src="{{ asset('js/nav.js') }}"></script>
@endsection
