<x-guest-layout>
    <x-slot name="title">Sign In</x-slot>

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
            @if (session('status'))
                <div class="kt-alert kt-alert-outline kt-alert-success mb-4">
                    <div class="kt-alert-icon">
                        <i class="ki-filled ki-check-circle"></i>
                    </div>
                    <div class="kt-alert-content">
                        <span class="kt-alert-description">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="kt-alert kt-alert-outline kt-alert-destructive mb-4">
                    <div class="kt-alert-icon">
                        <i class="ki-filled ki-information"></i>
                    </div>
                    <div class="kt-alert-content">
                        <span class="kt-alert-title">{{ __('Whoops! Something went wrong.') }}</span>
                        <ul class="kt-alert-description list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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
</x-guest-layout>
