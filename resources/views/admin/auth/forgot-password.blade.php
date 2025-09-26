<x-guest-layout title="Forgot Password">
    <div class="kt-card max-w-[370px] w-full">
        <form method="POST" action="{{ route('password.email') }}" class="kt-card-content flex flex-col gap-5 p-10" id="reset_password_enter_email_form">
        @csrf

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

        <div class="text-center">
            <h3 class="text-lg font-medium text-mono">
                Forgot Password?
            </h3>
            <span class="text-sm text-secondary-foreground">
                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </span>
        </div>

        <!-- Email Address -->
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono" for="email">
                Email
            </label>
            <input id="email" class="kt-input" type="email" name="email" :value="old('email')" required autofocus placeholder="email@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
            {{ __('Email Password Reset Link') }}
            <i class="ki-filled ki-black-right ms-1"></i>
        </button>

        <div class="text-center mt-2">
            <a href="{{ route('admin.login') }}" class="text-sm text-primary hover:underline">
                Back to login
            </a>
        </div>
        </form>
    </div>
</x-guest-layout>
