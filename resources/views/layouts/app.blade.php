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
