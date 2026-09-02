<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'SageSwap')</title>
    <meta name="description" content="@yield('description')" />
    <meta name="author" content="SageSwap" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', 'SageSwap')" />
    <meta property="og:description" content="@yield('description')" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Questrial&family=JetBrains+Mono:wght@300;400;500;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  </head>
  <body>
    <div class="page">
      @include('partials.header')

      @yield('content')

      @include('partials.footer')
    </div>
  </body>
</html>
