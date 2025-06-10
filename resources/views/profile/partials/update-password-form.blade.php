<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">{{ __('messages.profile.update_password.title') }}</h3>
    </div>
    <div class="kt-card-content">
        <p class="text-sm text-muted-foreground mb-4">
            {{ __('messages.profile.update_password.description') }}
        </p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-4">
                <label for="update_password_current_password" class="kt-form-label max-w-56">{{ __('messages.profile.form.current_password') }}</label>
                <div class="grow">
                    <input id="update_password_current_password" name="current_password" type="password" class="kt-input" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-4">
                <label for="update_password_password" class="kt-form-label max-w-56">{{ __('messages.profile.form.new_password') }}</label>
                <div class="grow">
                    <input id="update_password_password" name="password" type="password" class="kt-input" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-4">
                <label for="update_password_password_confirmation" class="kt-form-label max-w-56">{{ __('messages.profile.form.confirm_password') }}</label>
                <div class="grow">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="kt-input" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="kt-btn kt-btn-primary">{{ __('messages.profile.form.save') }}</button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-muted-foreground"
                    >{{ __('messages.profile.saved') }}</p>
                @endif
            </div>
        </form>
    </div>
</div>
