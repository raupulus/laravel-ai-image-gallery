@extends('layouts.app')

@section('title', 'Server Error | ' . config('app.name'))
@section('meta-robots', 'noindex, nofollow')

@section('content')
    <div class="container-xl py-5 my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 fw-bold text-danger mb-3">500</h1>
                <h2 class="h4 text-light mb-3">Something went wrong</h2>
                <p class="text-light-emphasis mb-4">
                    An unexpected error occurred while processing your request. Please try again later.
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary">Back to Gallery</a>
            </div>
        </div>
    </div>
@endsection
