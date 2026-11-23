@extends('layouts.app')

@section('title', 'Page Not Found | ' . config('app.name'))
@section('meta-robots', 'noindex, nofollow')

@section('content')
    <div class="container-xl py-5 my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 fw-bold text-primary mb-3">404</h1>
                <h2 class="h4 text-light mb-3">Page or collection not found</h2>
                <p class="text-light-emphasis mb-4">
                    The resource you are looking for may have been moved or removed.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Gallery</a>
                    <a href="{{ route('collections.random') }}" class="btn btn-outline-light">Explore Random Collection</a>
                </div>
            </div>
        </div>
    </div>
@endsection
