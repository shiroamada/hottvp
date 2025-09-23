<x-admin.guest-layout>
    <x-slot name="title">Sign In</x-slot>

    <div class="kt-card max-w-[370px] w-full">
        <form method="POST" action="{{ route('admin.login') }}" class="kt-card-content flex flex-col gap-5 p-10" id="sign_in_form">
            @csrf
            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
                    Sign in to your account
                </h3>
                @if (Route::has('admin.register'))
                <div class="flex items-center justify-center font-medium">

                </div>
                @endif
            </div>

            {{-- Session Status --}}
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

            {{-- Validation Errors --}}
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

            {{-- LOGIN (email or username) --}}
            <div class="flex flex-col gap-1">
                <label for="login" class="kt-form-label font-normal text-mono">
                    Email or Username
                </label>
                <input
                    id="login"
                    name="login"
                    class="kt-input @error('login') border-danger @enderror"
                    placeholder="email@example.com or yourusername"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    autofocus
                    autocomplete="username"
                />
                @error('login')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between gap-1">
                    <label for="password" class="kt-form-label font-normal text-mono">
                        Password
                    </label>
                </div>
                <div class="kt-input @error('password') border-danger @enderror" data-kt-toggle-password="true">
                    <input id="password" name="password" placeholder="Enter Password" type="password" required autocomplete="current-password"/>
                    <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true" type="button">
                        <span class="kt-toggle-password-active:hidden">
                            <i class="ki-filled ki-eye text-muted-foreground"></i>
                        </span>
                        <span class="hidden kt-toggle-password-active:block">
                            <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                        </span>
                    </button>
                </div>
                @if (Route::has('admin.password.request'))
                <a class="text-sm kt-link shrink-0" href="{{ route('admin.password.request') }}">
                    Forgot Password?
                </a>
                @endif
                @error('password')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- REMEMBER ME --}}
            <label class="kt-label">
                <input id="remember_me" class="kt-checkbox kt-checkbox-sm" name="remember" type="checkbox" value="1"/>
                <span class="kt-checkbox-label">Remember me</span>
            </label>

            <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
                Sign In
            </button>
        </form>
    </div>
</x-admin.guest-layout>
