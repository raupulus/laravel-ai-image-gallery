<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

{{-- Título, 50-60 carácteres máximo --}}
<title>@yield('title', config('app.name'))</title>

<meta name="csrf-token" content="{{ csrf_token() }}" />

{{-- Idioma del contenido --}}
<meta property="og:locale" content="@yield('meta-og-locale', 'en_US')" />

@yield('meta')

<meta name="description" content="@yield('meta-description', config('app.description'))" />
<meta name="author" content="@yield('meta-author', 'Raúl Caro Pastorino (@raupulus)')" />
<meta name="robots" content="@yield('meta-robots', (request()->routeIs('login') || request()->filled('search')) ? 'noindex, nofollow' : 'index, follow')" />

<link rel="canonical" href="@yield('canonical', request()->url())" />

{{-- Redes sociales --}}
<meta property="og:title"
      content="@yield('meta-og-title', config('app.name'))" />
<meta property="og:site_name"
      content="@yield('meta-og-site_name', config('app.name'))" />
<meta property="og:type" content="@yield('og-type', 'website')" />
<meta property="og:description"
      content="@yield('meta-og-description', config('app.description'))" />
<meta property="og:image"
      content="@yield('meta-og-image', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:image:url"
      content="@yield('meta-og-image-url', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:image:secure_url"
      content="@yield('meta-og-image-secure_url', asset('images/logo/logo_640x640.webp'))" />
<meta property="og:url"
      content="@yield('meta-og-url', request()->url())" />
<meta property="og:image:alt"
      content="@yield('meta-og-image_alt', config('app.description'))" />
<meta property="og:image:type"
      content="@yield('meta-og-image-type', 'image/webp')" />

<meta name="twitter:card" content="@yield('meta-twitter-card', 'summary_large_image')" />
<meta name="twitter:title" content="@yield('meta-twitter-title', config('app.name'))" />
<meta name="twitter:description" content="@yield('meta-twitter-description', config('app.description'))" />
<meta name="twitter:image" content="@yield('meta-twitter-image', asset('images/logo/logo_640x640.webp'))" />
<meta name="twitter:image:alt" content="@yield('meta-twitter-image-alt', config('app.description'))" />
<meta name="twitter:site" content="@yield('meta-twitter-site', '@raupulus')" />
<meta name="twitter:creator" content="@yield('meta-twitter-creator', '@raupulus')" />

{{-- Iconos --}}
<link rel="apple-touch-icon" sizes="256x256" href="{{asset('images/favicons/favicon_256x256.ico')}}" />
<link rel="icon" type="image/x-icon" sizes="256x256" href="{{asset('images/favicons/favicon_256x256.ico')}}" />
<link rel="icon" type="image/x-icon" sizes="96x96" href="{{asset('images/favicons/favicon_96x96.ico')}}" />
<link rel="icon" type="image/x-icon" sizes="32x32" href="{{asset('images/favicons/favicon_32x32.ico')}}" />

@yield('structured_data')

<link rel="stylesheet" href="{{asset('theme/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('theme/css/styles.min.css')}}">
<link rel="stylesheet" href="{{asset('css/styles.css')}}">
<link rel="stylesheet" href="{{asset('css/collection_blocks.css')}}">
