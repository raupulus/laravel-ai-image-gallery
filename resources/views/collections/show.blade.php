@extends('layouts.app')

@section('meta-description', $collection->description)
@section('meta-keywords', 'AI, Stable Diffusion, Dall-e, Raúl Caro Pastorino, raupulus, ' . $collection->tags)
@section('meta-og-title', $collection->title)
@section('meta-og-description', $collection->description)
@section('meta-og-image', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-image-url', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-image-secure_url', $firstImage?->urlImage ?? asset('images/logo/logo_640x640.webp'))
@section('meta-og-url', $collection->url)
@section('meta-og-image_alt', $collection->title)
@section('meta-twitter-title', $collection->title)

@section('content')
    <div class="container-xl" style="">
        <div class="row">
            <div class="col">
                <header style="margin-top: 4rem;margin-bottom: 4rem;">
                    <h1 class="text-capitalize fs-1 fw-bold text-center text-primary"
                        style="font-family: Aclonica, sans-serif;">
                        {{$collection->title}}
                    </h1>
                </header>
            </div>
        </div>
        @if($collection->url_youtube)
            <div class="row">
                <div class="col text-center">
                    <iframe width="{{$collection->isHorizontal ? '560' : '315'}}"
                            height="{{$collection->isHorizontal ? '315' : '560'}}"
                            src="{{$collection->url_youtube}}"
                            style="max-width: 600px; width: 100%"
                            title="{{$collection->title}}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        @else
            @if($firstImage)
                <div class="row">
                    <div class="col text-center">
                        <a href="{{$firstImage->urlImage}}"
                           data-fancybox="gallery-title">
                            <img class="img-fluid"
                                 style="width: 100%; max-width: 560px;"
                                 alt="{{$collection->title}}"
                                 src="{{$firstImage->urlThumbnail}}">
                        </a>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col text-center">
                        <div class="alert alert-warning" role="alert">
                            The images have not been uploaded yet, it is very likely that they will be ready in a few minutes.
                        </div>
                    </div>
                </div>
            @endif

        @endif

        {{-- Metadatos --}}
        <div class="row">
            <div class="col">
                <p>
                    {{$collection->description}}
                </p>
            </div>
        </div>

        {{-- Grid con las imágenes --}}
        @include('components._grid_images')

        {{-- Otras relacionadas --}}
        <div class="row">
            <div class="col">

            </div>
        </div>

    </div>
@endsection


@section('css')
    <link rel="stylesheet" href="{{asset('css/collection_show.css')}}">
@endsection
