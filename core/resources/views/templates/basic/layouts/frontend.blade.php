@extends($activeTemplate.'layouts.app')
@section('main-content')
    @include($activeTemplate.'partials.header')
    @yield('content')
@endsection
