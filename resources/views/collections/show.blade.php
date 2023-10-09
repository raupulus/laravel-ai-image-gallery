@extends('layouts.app')

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
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/U6glI6vFKL0?si=hd9-qcFRYLj75X0o"
                            style="max-width: 600px; width: 100%"
                            title="{{$collection->title}}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        @else
            @php($firstImage = $images->first())

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
