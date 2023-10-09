@extends('layouts.app')

@section('content')
    <div class="container-xl" style="">
        <div class="row">
            <div class="col">
                <header style="margin-top: 4rem;margin-bottom: 4rem;">
                    <h1 class="text-capitalize fs-1 fw-bold text-center text-primary"
                        style="font-family: Aclonica, sans-serif;">Disléxica</h1>
                </header>

            </div>
        </div>


        <div class="row">
            <div class="col" style="height: auto;">
                <form method="GET" action="{{route('home')}}"
                      style="max-width: 700px;margin: auto;margin-top: 3rem;margin-bottom: 3rem;">
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="1em" height="1em"
                                 fill="currentColor" viewBox="0 0 16 16"
                                 class="bi bi-search-heart-fill"
                                 style="font-size: 38px;">
                                <path
                                    d="M6.5 13a6.474 6.474 0 0 0 3.845-1.258h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.008 1.008 0 0 0-.115-.1A6.471 6.471 0 0 0 13 6.5 6.502 6.502 0 0 0 6.5 0a6.5 6.5 0 1 0 0 13Zm0-8.518c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018Z"></path>
                            </svg>
                        </span>

                        <input class="form-control"
                               name="search"
                               type="search"
                               value="{{$search}}"
                               placeholder="Search for an collection">

                        <button class="btn btn-primary text-center"
                                type="submit"
                                style="text-align: center;">
                            Go
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Grid con las imágenes --}}
        <div class="row">

            @foreach($collections as $collection)

                @if($collection->images->count())
                    @php($image = $collection->primaryImage)

                    <div class="col-md-3 text-center col-sm-4 col-6 mt-1 mb-2">
                        <a href="{{route('collections.show', $collection->id)}}"
                           style="text-decoration: none; color: #fff;"
                           class="text-bg-danger small p-1 rounded-3 d-block">
                            {{$collection->images->count()}}

                            Images in Collection
                        </a>

                        <a href="{{$image->urlImage}}"
                           data-fancybox="gallery">
                            <img class="img-fluid"
                                 alt="{{$collection->title}}"
                                 src="{{$image->urlThumbnail}}">
                        </a>

                        <a href="{{route('collections.show', $collection->id)}}"
                           class="d-block bg-dark text-info"
                           style="text-decoration: none; min-height: 49px;">
                            <p class="text-center text-info">
                                {{$collection->titleResume}}
                            </p>
                        </a>
                    </div>
                @endif

            @endforeach

        </div>


        <div class="row">
            <div class="col-12">
                {{$collections->links()}}
            </div>
        </div>

    </div>
@endsection
