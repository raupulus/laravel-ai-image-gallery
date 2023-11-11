@extends('layouts.app')

{{--
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
--}}

@section('content')

    <section class="box-login-section mt-5 mb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="box-login-left col-sm-6">

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

                        <form method="POST" action="{{ route('login') }}" style="width: 23rem;">
                            @csrf
                            <h3 class="fw-normal mb-3 pb-3 text-danger text-center" style="letter-spacing: 1px;">
                                <img src="{{asset('images/logo/logo_40x40.webp')}}"
                                     style="translate: 0 -4px; display: block; margin: auto;"
                                     alt="Website Logo"/>
                                &nbsp;
                                Log in
                            </h3>

                            <div class="form-outline mb-4">
                                <input id="email" type="email" name="email" class="form-control form-control-lg"
                                       value="{{old('email')}}" required autofocus autocomplete="username"/>
                                <label class="form-label" for="email">Email address</label>


                                @foreach($errors->get('email') as $mailError)
                                    <div class="alert alert-danger" role="alert">
                                        {{$mailError}}
                                    </div>
                                @endforeach

                            </div>

                            <div class="form-outline mb-4">
                                <input id="password" type="password" name="password"  class="form-control form-control-lg" required autocomplete="current-password"/>
                                <label class="form-label" for="password">Password</label>

                                @foreach($errors->get('password') as $mailError)
                                    <div class="alert alert-danger" role="alert">
                                        {{$mailError}}
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-1 mb-4">
                                <button class="btn btn-primary btn-block w-100 ml-3 mr-3" type="submit">Login</button>
                            </div>

                            {{--
                            <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Forgot password?</a></p>
                            <p>Don't have an account? <a href="#!" class="link-info">Register here</a></p>
                            --}}
                        </form>

                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="{{asset('images/ai/ai_1.webp')}}"
                         alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: center;">
                </div>
            </div>
        </div>
    </section>



    {{--
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>

            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    --}}
@endsection


@section('css')
    <style>
        .box-login-section {
            margin: auto;
            width: 80%;
            max-width: 1200px;
            color: #f3f3f3;
        }

        .box-login-left {
            background-color: #20374c;
        }

        .box-login-left input {
            color: #0f2537;
        }

        .bg-image-vertical {
            position: relative;
            overflow: hidden;
            background-repeat: no-repeat;
            background-position: right center;
            background-size: auto 100%;
        }

        @media (min-width: 1025px) {
            .h-custom-2 {
                height: 100%;
            }
        }
    </style>
@endsection
