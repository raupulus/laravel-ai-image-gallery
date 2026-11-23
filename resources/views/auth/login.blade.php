@extends('layouts.app')

@section('title', 'Log in | ' . config('app.name'))

@section('content')
    <section class="box-login-section mt-5 mb-5">
        <div class="container-fluid">
            <div class="row g-0 rounded overflow-hidden shadow">
                <div class="box-login-left col-md-6 d-flex align-items-center justify-content-center p-4 p-md-5">
                    <form method="POST" action="{{ route('login') }}" class="w-100" style="max-width: 24rem;">
                        @csrf
                        <h1 class="h3 fw-semibold mb-4 text-light text-center">
                            <img src="{{asset('images/logo/logo_40x40.webp')}}"
                                 class="d-block mx-auto mb-2"
                                 width="40"
                                 height="40"
                                 alt="Website Logo"/>
                            Log in
                        </h1>

                        <div class="mb-3">
                            <label class="form-label text-light" for="email">Email address</label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   class="form-control form-control-lg"
                                   value="{{old('email')}}"
                                   @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                                   required
                                   autofocus
                                   autocomplete="username"/>

                            @if($errors->has('email'))
                                <div id="email-error" class="alert alert-danger mt-2 mb-0" role="alert">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light" for="password">Password</label>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="form-control form-control-lg"
                                   @if($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                                   required
                                   autocomplete="current-password"/>

                            @if($errors->has('password'))
                                <div id="password-error" class="alert alert-danger mt-2 mb-0" role="alert">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Login</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6 px-0 d-none d-md-block">
                    <img src="{{asset('images/ai/ai_1.webp')}}"
                         alt="AI generated artwork showcase"
                         class="w-100 h-100"
                         style="min-height: 460px; object-fit: cover; object-position: center;">
                </div>
            </div>
        </div>
    </section>
@endsection
