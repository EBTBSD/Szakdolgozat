<?php
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/style_footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_course.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_round_gauges.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_pupup_form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_contact.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/style_.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style_.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style_.css')}}"> --}}

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logos/favico.ico') }}">

    <title>@yield('title', 'Edu:Score')</title>
</head>
<body>
    <canvas id="pipes"></canvas>
    <div class="div_main">
    <header>
        @include('public.main_header')
    </header>
        <nav>
            @include('public.main_menu')
        </nav>
    <div class="div_body">
            @yield('dynamic_content')
    </div>
    <footer>
        @include('public.main_footer')
    </footer>
    </div>
</body>
</html>
<script src="{{ asset('javascript/js_menu.js') }}"></script>
<script src="{{ asset('javascript/js_pupup.js') }}"></script>
<script src="{{ asset('javascript/js_totop.js') }}"></script>
<script src="{{ asset('javascript/js_time.js') }}"></script>
<script src="{{ asset('javascript/js_round_gauges.js') }}"></script>
<script src="{{ asset('javascript/js_canvas.js') }}"></script>
