<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr">
 <head>
  <title>{{ $html_title ?? config('app.name', 'HOT TV PLUS') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8"/>
  <meta content="follow, index" name="robots"/>
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
  <meta content="" name="description"/>
  <meta content="@hottvplus" name="twitter:site"/>
  <meta content="@hottvplus" name="twitter:creator"/>
  <meta content="summary_large_image" name="twitter:card"/>
  <meta content="Hot TV Plus" name="twitter:title"/>
  <meta content="" name="twitter:description"/>
  <meta content="assets/media/app/og-image.png" name="twitter:image"/>
  <meta content="https://store.hottvplus.com" property="og:url"/>
  <meta content="en_US" property="og:locale"/>
  <meta content="website" property="og:type"/>
  <meta content="@hottvplus" property="og:site_name"/>
  <meta content="Hot TV Plus" property="og:title"/>
  <meta content="" property="og:description"/>
  <meta content="assets/media/app/og-image.png" property="og:image"/>
  <link href="assets/media/app/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
  <link href="{{ asset('assets/media/app/favicon-32x32.png') }}" rel="icon" sizes="32x32" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon-16x16.png') }}" rel="icon" sizes="16x16" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon.ico') }}" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  @stack('styles')
 </head>
 <body class="antialiased flex h-full text-base text-foreground bg-background [--header-height:60px] [--sidebar-width:270px] lg:overflow-hidden bg-muted">
  @yield('content')
  <script>
   const defaultThemeMode = 'light';
   let themeMode;
   if (document.documentElement) {
    if (localStorage.getItem('kt-theme')) {
     themeMode = localStorage.getItem('kt-theme');
    } else if (document.documentElement.hasAttribute('data-kt-theme-mode')) {
     themeMode = document.documentElement.getAttribute('data-kt-theme-mode');
    } else {
     themeMode = defaultThemeMode;
    }
    if (themeMode === 'system') {
     themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    document.documentElement.classList.add(themeMode);
   }
  </script>
  <!-- Dependencies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  @stack('scripts')
 </body>
</html>