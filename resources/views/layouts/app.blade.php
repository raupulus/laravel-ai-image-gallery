<!DOCTYPE html>
<!--
@author Raúl Caro Pastorino
@copyright Copyright (c) 2023 Raúl Caro Pastorino
@license https://www.gnu.org/licenses/gpl-3.0-standalone.html

Author Web: https://fryntiz.es
E-mail: raul@fryntiz.dev
-->

<html data-bs-theme="auto" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if(config('services.google.analytics'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{config('services.google.analytics')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{config('services.google.analytics')}}');
        </script>
    @endif

    @include('layouts.head')
    @yield('head_css')
    @yield('head_js')
</head>

<body class="text-info" style="height: 100%;min-height: 100vh;">
@include('layouts.navbar')

<div id="app">
    @yield('content')
</div>

@include('layouts.footer')
@include('layouts.footer_meta')
@yield('css')
@yield('js')
</body>
</html>
