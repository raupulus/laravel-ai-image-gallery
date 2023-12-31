@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col">
                <header style="margin-top: 4rem;margin-bottom: 1rem;">
                    <h1 class="text-capitalize fs-1 fw-bold text-center text-primary main-title">
                        <span class="d-block">AI</span>
                        Dyslexic
                    </h1>
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

                        <input id="main-input-search"
                               class="form-control"
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
        <div id="box-all-collections-blocks" class="row">

            @foreach($collections as $collection)

                @if($collection->images->count())
                    @include('components._collection_block')
                @endif

            @endforeach

        </div>

        <div class="row">
            <div class="col-12">
                {{-- $collections->links() --}}
            </div>
        </div>

        <div id="box-collections-end" class="row">
            <div class="col-12 text-info p-1">

                Total Collections:
                <span id="total-images" class="font-monospace">0</span>

            </div>
        </div>

    </div>
@endsection



@section('js')
    <script>
        let currentPage = parseInt("{{isset($page) && $page ? $page : 1}}");
        let hasMorePages = true;

        function fetchNextCollectionBlocks(box) {
            currentPage += 1

            const search = document.getElementById('main-input-search').value;
            let url = "{{route('collection.ajax.get.cards')}}";

            url += '/' + (2 + currentPage); // Sumo 3 para compensar 24 imágenes al comenzar (8 por carga de página)

            if (search) {
                url += '/' + search;
            }

            fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {

                        if (data.html) {
                            box.insertAdjacentHTML('beforeend', data.html);
                        }

                        hasMorePages = data.hasMorePages;

                        let totalSpan = document.getElementById('total-images');

                        if (totalSpan && data.total) {
                            totalSpan.textContent = data.total;
                        }
                    }
                })
        }

        document.addEventListener('DOMContentLoaded', () => {
            const boxCollections = document.getElementById('box-all-collections-blocks');

            if (boxCollections) {
                const elementInView = (el, percentageScroll = 100) => {
                    const elementTop = el.getBoundingClientRect().top;

                    return (
                        elementTop <=
                        ((window.innerHeight || document.documentElement.clientHeight) * (percentageScroll/100))
                    );
                };

                const handleScrollAnimation = () => {
                    const ele = document.getElementById('box-collections-end');

                    if (elementInView(ele, 90)) {
                        fetchNextCollectionBlocks(boxCollections);
                    }
                }

                window.addEventListener('scroll', () => {
                    if (hasMorePages) {
                        handleScrollAnimation();
                    }
                })

                handleScrollAnimation();
            }
        });
    </script>
@endsection
