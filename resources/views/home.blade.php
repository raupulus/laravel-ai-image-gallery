@extends('layouts.app')

@section('title', config('app.name') . ' - AI Image Gallery & Prompts')
@section('meta-description', config('app.description'))
@section('canonical', route('home'))
@section('meta-og-title', config('app.name') . ' - AI Image Gallery & Prompts')
@section('meta-og-description', config('app.description'))
@section('meta-og-url', route('home'))
@section('meta-twitter-title', config('app.name') . ' - AI Image Gallery & Prompts')
@section('meta-twitter-description', config('app.description'))

@section('structured_data')
    @php
        $homeSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'description' => config('app.description'),
            'url' => route('home'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('home') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($homeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endsection

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
                <search>
                    <form method="GET" action="{{route('home')}}"
                          style="max-width: 700px;margin: auto;margin-top: 3rem;margin-bottom: 3rem;">
                        <label for="main-input-search" class="visually-hidden">Search collections by title or tags</label>
                        <div class="input-group">
                            <span class="input-group-text" aria-hidden="true">
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
                                   placeholder="Search for a collection">

                            @if($search)
                                <a href="{{route('home')}}"
                                   class="btn btn-outline-light d-inline-flex align-items-center"
                                   aria-label="Clear search filter">
                                    Clear
                                </a>
                            @endif

                            <button class="btn btn-primary text-center"
                                    type="submit">
                                Go
                            </button>
                        </div>
                    </form>
                </search>
            </div>
        </div>


        {{-- Grid con las imágenes --}}
        <div id="box-all-collections-blocks" class="row">

            @forelse($collections as $collection)

                @if(($collection->images_count ?? $collection->images->count()) > 0)
                    @include('components._collection_block')
                @endif

            @empty
                <div class="col-12">
                    <div class="alert alert-dark text-center py-4 my-3" role="status">
                        @if($search)
                            <p class="mb-2">No collections matched "<strong>{{ $search }}</strong>".</p>
                            <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Reset search</a>
                        @else
                            <p class="mb-0">No collections are available yet.</p>
                        @endif
                    </div>
                </div>
            @endforelse

        </div>

        <div class="row">
            <div class="col-12">
                {{-- $collections->links() --}}
            </div>
        </div>

        <div id="box-collections-end" class="row">
            <div class="col-12 text-info p-1" aria-live="polite">

                Total Collections:
                <span id="total-images" class="font-monospace">{{ $collections->total() }}</span>

                <span id="ajax-load-error" class="text-danger ms-2 d-none" role="status">
                    Could not load more collections.
                </span>

            </div>
        </div>

    </div>
@endsection



@section('js')
    <script>
        let currentPage = parseInt(@json($collections->currentPage()), 10) || 1;
        let hasMorePages = @json($collections->hasMorePages());
        let isLoading = false;

        function fetchNextCollectionBlocks(box, observer) {
            if (!hasMorePages || isLoading) {
                return;
            }

            isLoading = true;
            currentPage += 1;

            const errorBanner = document.getElementById('ajax-load-error');
            if (errorBanner) {
                errorBanner.classList.add('d-none');
            }

            const searchInput = document.getElementById('main-input-search');
            const search = searchInput ? searchInput.value.trim() : '';
            let url = "{{route('collection.ajax.get.cards')}}";

            url += '/' + (2 + currentPage);

            if (search) {
                url += '/' + encodeURIComponent(search);
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.html) {
                            box.insertAdjacentHTML('beforeend', data.html);
                        }

                        hasMorePages = Boolean(data.hasMorePages);

                        const totalSpan = document.getElementById('total-images');
                        if (totalSpan && typeof data.total !== 'undefined') {
                            totalSpan.textContent = data.total;
                        }

                        if (!hasMorePages && observer) {
                            observer.disconnect();
                        }
                    }
                })
                .catch(() => {
                    currentPage -= 1;
                    if (errorBanner) {
                        errorBanner.classList.remove('d-none');
                    }
                })
                .finally(() => {
                    isLoading = false;
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const boxCollections = document.getElementById('box-all-collections-blocks');
            const sentinel = document.getElementById('box-collections-end');

            if (!boxCollections || !sentinel || !hasMorePages) {
                return;
            }

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    if (entries.some(entry => entry.isIntersecting)) {
                        fetchNextCollectionBlocks(boxCollections, observer);
                    }
                }, {
                    rootMargin: '250px 0px',
                });

                observer.observe(sentinel);
            }
        });
    </script>
@endsection
