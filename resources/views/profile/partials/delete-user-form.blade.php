<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">{{ __('messages.profile.delete_account.title') }}</h3>
    </div>
    <div class="kt-card-content">
        <p class="text-sm text-muted-foreground mb-4">
            {{ __('messages.profile.delete_account.description') }}
        </p>

        <button type="button" class="kt-btn kt-btn-danger" data-kt-modal-toggle="#confirm-user-deletion">
            {{ __('messages.profile.delete_account.button') }}
        </button>
    </div>
</div>

<!-- Confirm User Deletion Modal -->
<div class="kt-modal" id="confirm-user-deletion">
    <div class="kt-modal-dialog">
        <div class="kt-modal-content">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <div class="kt-modal-header">
                    <h3 class="kt-modal-title">{{ __('messages.profile.delete_account.modal_title') }}</h3>
                    <button class="kt-modal-close" data-kt-modal-dismiss="true">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>

                <div class="kt-modal-body">
                    <p class="text-sm text-muted-foreground mb-4">
                        {{ __('messages.profile.delete_account.modal_description') }}
                    </p>

                    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mt-6">
                        <label for="password" class="kt-form-label max-w-56">{{ __('messages.profile.form.password') }}</label>
                        <div class="grow">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="kt-input"
                                placeholder="{{ __('messages.profile.form.password') }}"
                            />
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="kt-modal-footer">
                    <button type="button" class="kt-btn kt-btn-light" data-kt-modal-dismiss="true">{{ __('messages.profile.form.cancel') }}</button>
                    <button type="submit" class="kt-btn kt-btn-danger">{{ __('messages.profile.delete_account.button') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
