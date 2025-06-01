<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
  <title>{{ config('app.name', 'Laravel') }} - Sign In</title>
  <meta charset="utf-8"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
  <link href="{{ asset('assets/media/app/apple-touch-icon.png') }}" rel="apple-touch-icon" sizes="180x180"/>
  <link href="{{ asset('assets/media/app/favicon-32x32.png') }}" rel="icon" sizes="32x32" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon-16x16.png') }}" rel="icon" sizes="16x16" type="image/png"/>
  <link href="{{ asset('assets/media/app/favicon.ico') }}" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
	@vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
  @stack('styles')
 </head>
 <body class="antialiased flex h-full text-base text-foreground bg-background">
  <!-- Theme Mode -->
  <script>
   const defaultThemeMode = 'light'; // light|dark|system
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
  <!-- End of Theme Mode -->
  <!-- Page -->
  <style>
   .page-bg {
    background-image: url('{{ asset('assets/media/images/2600x1200/bg-10.png') }}');
   }
   .dark .page-bg {
    background-image: url('{{ asset('assets/media/images/2600x1200/bg-10-dark.png') }}');
   }
  </style>
  <div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
   <div class="kt-card max-w-[370px] w-full">
    <form method="POST" action="{{ route('login') }}" class="kt-card-content flex flex-col gap-5 p-10" id="sign_in_form">
     @csrf
     <div class="text-center mb-2.5">
      <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
       Sign in
      </h3>
      @if (Route::has('register'))
      <div class="flex items-center justify-center font-medium">
       <span class="text-sm text-secondary-foreground me-1.5">
        Need an account?
       </span>
       <a class="text-sm link" href="{{ route('register') }}">
        Sign up
       </a>
      </div>
      @endif
     </div>

     <!-- Session Status -->
     <x-auth-session-status class="mb-4" :status="session('status')" />

     <!-- Validation Errors -->
     {{-- <x-input-error :messages="$errors->all()" class="mt-2" /> --}}
      @if ($errors->any())
        <div class="bg-danger/10 text-danger text-sm rounded-md p-4 mb-0">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

     <div class="grid grid-cols-2 gap-2.5">
      <a class="kt-btn kt-btn-outline justify-center" href="#"> {{-- Google OAuth placeholder --}}
       <img alt="" class="size-3.5 shrink-0" src="{{ asset('assets/media/brand-logos/google.svg') }}"/>
       Use Google
      </a>
      <a class="kt-btn kt-btn-outline justify-center" href="#"> {{-- Apple OAuth placeholder --}}
       <img alt="" class="size-3.5 shrink-0 dark:hidden" src="{{ asset('assets/media/brand-logos/apple-black.svg') }}"/>
       <img alt="" class="size-3.5 shrink-0 light:hidden" src="{{ asset('assets/media/brand-logos/apple-white.svg') }}"/>
       Use Apple
      </a>
     </div>
     <div class="flex items-center gap-2">
      <span class="border-t border-border w-full">
      </span>
      <span class="text-xs text-muted-foreground font-medium uppercase">
       Or
      </span>
      <span class="border-t border-border w-full">
      </span>
     </div>
     <div class="flex flex-col gap-1">
      <label for="email" class="kt-form-label font-normal text-mono">
       Email
      </label>
      <input id="email" name="email" class="kt-input @error('email') border-danger @enderror" placeholder="email@email.com" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"/>
      @error('email')
        <div class="text-danger text-sm mt-1">{{ $message }}</div>
      @enderror
     </div>
     <div class="flex flex-col gap-1">
      <div class="flex items-center justify-between gap-1">
       <label for="password" class="kt-form-label font-normal text-mono">
        Password
       </label>
       @if (Route::has('password.request'))
       <a class="text-sm kt-link shrink-0" href="{{ route('password.request') }}">
        Forgot Password?
       </a>
       @endif
      </div>
      <div class="kt-input @error('password') border-danger @enderror" data-kt-toggle-password="true">
       <input id="password" name="password" placeholder="Enter Password" type="password" value="" required autocomplete="current-password"/>
       <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true" type="button">
        <span class="kt-toggle-password-active:hidden">
         <i class="ki-filled ki-eye text-muted-foreground">
         </i>
        </span>
        <span class="hidden kt-toggle-password-active:block">
         <i class="ki-filled ki-eye-slash text-muted-foreground">
         </i>
        </span>
       </button>
      </div>
      @error('password')
        <div class="text-danger text-sm mt-1">{{ $message }}</div>
      @enderror
     </div>
     <label class="kt-label">
      <input id="remember_me" class="kt-checkbox kt-checkbox-sm" name="remember" type="checkbox" value="1"/>
      <span class="kt-checkbox-label">
       Remember me
      </span>
     </label>
     <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
      Sign In
     </button>
    </form>
   </div>
  </div>
  <!-- End of Page -->
  <!-- Scripts -->
   <!--end::Custom Javascript-->
   @stack('scripts')
    <!--end::Javascript-->
 </body>
</html>
