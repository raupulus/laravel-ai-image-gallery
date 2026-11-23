<nav class="navbar navbar-expand-md bg-dark py-3" data-bs-theme="dark" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{route('home')}}">


            <img src="{{asset('images/logo/logo_40x40.webp')}}"
                 class="bs-icon-sm bs-icon-rounded bs-icon-primary d-flex justify-content-center align-items-center me-2 bs-icon"
                 width="32"
                 height="32"
                 alt="Website Logo"/>

            <span>AI Dyslexic</span>
        </a>

        <button type="button"
                data-bs-toggle="collapse"
                class="navbar-toggler"
                data-bs-target="#navcol-5"
                aria-controls="navcol-5"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navcol-5">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       @if(request()->routeIs('home')) aria-current="page" @endif
                       href="{{route('home')}}">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->route('page') === 'about' ? 'active' : '' }}"
                       @if(request()->routeIs('page.show') && request()->route('page') === 'about') aria-current="page" @endif
                       href="{{route('page.show', 'about')}}">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('page.show') && request()->route('page') === 'social' ? 'active' : '' }}"
                       @if(request()->routeIs('page.show') && request()->route('page') === 'social') aria-current="page" @endif
                       href="{{route('page.show', 'social')}}">
                        Social
                    </a>
                </li>

            </ul>

            <a class="btn btn-primary ms-md-2"
               data-bss-hover-animate="flash"
               href="{{route('collections.random')}}">
                Random
            </a>

            @if(auth()->user())
                <div>
                    <form method="POST" action="{{route('logout')}}">
                        @csrf

                        <button type="submit"
                                class="btn btn-primary ms-md-2 mt-2 mt-md-0">Logout</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>
