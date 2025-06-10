<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">{{ __('messages.profile.update_profile_information.title') }}</h3>
    </div>
    <div class="kt-card-content">
        <p class="text-sm text-muted-foreground mb-4">
            {{ __("messages.profile.update_profile_information.description") }}
        </p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-4">
                <label for="name" class="kt-form-label max-w-56">{{ __('messages.profile.form.name') }}</label>
                <div class="grow">
                    <input id="name" name="name" type="text" class="kt-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-4">
                <label for="email" class="kt-form-label max-w-56">{{ __('messages.profile.form.email') }}</label>
                <div class="grow">
                    <input id="email" name="email" type="email" class="kt-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-muted-foreground">
                            {{ __('messages.profile.unverified_email') }}
                            <button form="send-verification" class="underline text-sm text-primary hover:text-primary-focus">
                                {{ __('messages.profile.resend_verification') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-success">
                                {{ __('messages.profile.verification_link_sent') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="kt-btn kt-btn-primary">{{ __('messages.profile.form.save') }}</button>

                @if (session('status') === 'profile-updated')
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

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
