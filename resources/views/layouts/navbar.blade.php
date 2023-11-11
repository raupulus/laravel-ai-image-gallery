<nav class="navbar navbar-expand-md bg-dark py-3" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{route('home')}}">


            <img src="{{asset('images/logo/logo_40x40.webp')}}"
                 class="bs-icon-sm bs-icon-rounded bs-icon-primary d-flex justify-content-center align-items-center me-2 bs-icon"
                 alt="Website Logo"/>

            <span>AI Dyslexic</span>
        </a>

        <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-5">
            <span class="visually-hidden">Toggle navigation</span>
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navcol-5">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('home')}}">
                        Home
                    </a>
                </li>

                {{--
                <li class="nav-item"><a class="nav-link" href="#">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Likes</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Downloads</a></li>
                --}}

                <li class="nav-item">
                    <a class="nav-link" href="{{route('page.show', 'about')}}">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('page.show', 'social')}}">
                        Social
                    </a>
                </li>

            </ul>

            <a class="btn btn-primary ms-md-2"
               role="button"
               data-bss-hover-animate="flash"
               href="{{route('collections.random')}}">
                Random
            </a>

            @if(auth()->user())
                <span>
                    <form method="POST" action="{{route('logout')}}">
                        @csrf

                        <button type="submit"
                                class="btn btn-primary ms-md-2"
                                role="button">Logout</button>
                    </form>
                </span>
            @endif
        </div>
    </div>
</nav>
