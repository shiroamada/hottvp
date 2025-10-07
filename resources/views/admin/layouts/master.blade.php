<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr">
 <head>
  <title>
   {{ $html_title ?? config('app.name', 'HOT TV PLUS') }}
  </title>
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
  <meta content="" name="description"/>
  <meta content="assets/media/app/og-image.png" property="og:image"/>
  <link href="assets/media/app/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
  <link href="{{ asset('assets/media/app/favicon-32x32.png') }}" rel="icon" sizes="32x32" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon-16x16.png') }}" rel="icon" sizes="16x16" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon.ico') }}" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<!-- @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/metronic/dist/assets/css/styles.css'
]) -->
  @endif
  @stack('styles')
 </head>
 <body class="antialiased flex h-full text-base text-foreground bg-background [--header-height:60px] [--sidebar-width:270px] lg:overflow-hidden bg-muted">
  <!-- Theme Mode -->
   @yield('content')
  <script>
   const defaultThemeMode = 'light'; // light|dark|system
			let themeMode;

			if (document.documentElement) {
				if (localStorage.getItem('kt-theme')) {
					themeMode = localStorage.getItem('kt-theme');
				} else if (
					document.documentElement.hasAttribute('data-kt-theme-mode')
				) {
					themeMode =
						document.documentElement.getAttribute('data-kt-theme-mode');
				} else {
					themeMode = defaultThemeMode;
				}

				if (themeMode === 'system') {
					themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches
						? 'dark'
						: 'light';
				}

				document.documentElement.classList.add(themeMode);
			}
  </script>
  <!-- End of Theme Mode -->
  
   <!--end::Custom Javascript-->
    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/layui-v2.4.5/layui.js') }}"></script>
    <script>
        layui.use('layer', function(){
            var layer = layui.layer;
        });
    </script>
    @stack('scripts')
    <!--end::Javascript-->
 </body>
</html>