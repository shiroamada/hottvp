<x-guest-layout title="Reset Password">
    <div class="kt-card max-w-[370px] w-full">
        <form method="POST" action="{{ route('password.store') }}" class="kt-card-content flex flex-col gap-5 p-10" id="reset_password_change_password_form">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="text-center">
            <h3 class="text-lg font-medium text-mono">
                Reset Password
            </h3>
            <span class="text-sm text-secondary-foreground">
                Enter your new password
            </span>
        </div>

        <!-- Email Address -->
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono" for="email">
                Email
            </label>
            <input id="email" class="kt-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus placeholder="email@email.com" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="flex flex-col gap-1">
            <label class="kt-form-label text-mono" for="password">
                New Password
            </label>
            <label class="kt-input" data-kt-toggle-password="true">
                <input id="password" name="password" type="password" placeholder="Enter a new password" required autocomplete="new-password"/>
                <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true">
                    <span class="kt-toggle-password-active:hidden">
                        <i class="ki-filled ki-eye text-muted-foreground"></i>
                    </span>
                    <span class="hidden kt-toggle-password-active:block">
                        <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                    </span>
                </div>
            </label>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm New Password -->
        <div class="flex flex-col gap-1">
            <label class="kt-form-label font-normal text-mono" for="password_confirmation">
                Confirm New Password
            </label>
            <label class="kt-input" data-kt-toggle-password="true">
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter new Password" required autocomplete="new-password"/>
                <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true">
                    <span class="kt-toggle-password-active:hidden">
                        <i class="ki-filled ki-eye text-muted-foreground"></i>
                    </span>
                    <span class="hidden kt-toggle-password-active:block">
                        <i class="ki-filled ki-eye-slash text-muted-foreground"></i>
                    </span>
                </div>
            </label>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
            {{ __('Reset Password') }}
        </button>
        </form>
    </div>
</x-guest-layout>
