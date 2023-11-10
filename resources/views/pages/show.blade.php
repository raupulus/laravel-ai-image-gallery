@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col mt-5 page-head-container">
                <header>
                    <h1 class="text-capitalize fs-1 fw-bold text-center text-primary main-title">
                        {{$page['title']}}
                    </h1>
                </header>

            </div>
        </div>


        <div class="row mt-5 mb-5">
            <div class="col page-content-container">
                @include($partial_view)
            </div>
        </div>

        <div class="row">
            <div class="col page-footer-container">
                <div class="page-content-link-container">
                    <div>
                        <ul>
                            <li>
                                <a href="{{route('page.show', 'about')}}">
                                    About
                                </a>
                            </li>

                            {{--
                            <li><a href="#">Cookies</a></li>
                            <li><a href="#">TEST</a></li>
                            --}}
                        </ul>
                    </div>

                    <div>
                        <ul>
                            <li>
                                <a href="{{route('page.show', 'social')}}">
                                    Social
                                </a>
                            </li>
                            {{--
                            <li><a href="#">ENLACE TEST</a></li>
                            <li><a href="#">RGPD</a></li>
                            --}}
                        </ul>
                    </div>

                    <div>
                        <ul>
                            {{--
                            <li><a href="#">ENLACE TEST</a></li>
                            <li><a href="#">ENLACE TEST</a></li>
                            <li><a href="#">ENLACE TEST</a></li>
                            --}}
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('css')
        <link rel="stylesheet" href="{{asset('css/pages/show.css')}}">
@endsection
