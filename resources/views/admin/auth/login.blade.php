<x-admin.guest-layout>
    <x-slot name="title">{{ __('admin_login.sign_in_title') }}</x-slot>

    <div class="kt-card max-w-[370px] w-full">
        <div class="flex items-center justify-between pt-3 ">
            <!-- <h3 class="text-lg font-medium text-mono">
                Sign in
            </h3> -->
        <div class="relative" data-kt-dropdown="true" data-kt-dropdown-placement="bottom-end" data-kt-dropdown-trigger="click">
            <button class="kt-btn kt-btn-sm kt-btn-ghost inline-flex items-center gap-2" data-kt-dropdown-toggle="true" title="Change language">
                @if(app()->getLocale() == 'en')
                    <div class="w-6 h-6 flex-shrink-0 flex-grow-0">
                        <img alt="English" class="w-full h-full rounded-full object-cover" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
                    </div>
                    <span class="font-medium text-gray-600">English</span>
                @elseif(app()->getLocale() == 'zh_CN')
                    <div class="w-6 h-6 flex-shrink-0 flex-grow-0">
                        <img alt="中文" class="w-full h-full rounded-full object-cover" src="{{ asset('assets/media/flags/china.svg') }}"/>
                    </div>
                    <span class="font-medium text-gray-600">中文</span>
                @elseif(app()->getLocale() == 'ms')
                    <div class="w-6 h-6 flex-shrink-0 flex-grow-0">
                        <img alt="Bahasa" class="w-full h-full rounded-full object-cover" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
                    </div>
                    <span class="font-medium text-gray-600">Bahasa Melayu</span>
                @endif
            </button>
            <div class="kt-dropdown-menu w-[160px]" data-kt-dropdown-menu="true">
                <ul class="kt-dropdown-menu-sub">
                    <li class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'en') }}">
                            <span class="flex items-center gap-2">
                                <img alt="English" class="inline-block size-4 rounded-full object-cover shrink-0" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
                                <span class="kt-menu-title">English</span>
                            </span>
                            @if(app()->getLocale() == 'en')
                                <i class="ki-solid ki-check-circle ms-auto text-green-500"></i>
                            @endif
                        </a>
                    </li>
                    <li class="{{ app()->getLocale() == 'zh_CN' ? 'active' : '' }}">
                        <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'zh_CN') }}">
                            <span class="flex items-center gap-2">
                                <img alt="中文" class="inline-block size-4 rounded-full object-cover shrink-0" src="{{ asset('assets/media/flags/china.svg') }}"/>
                                <span class="kt-menu-title">中文</span>
                            </span>
                            @if(app()->getLocale() == 'zh_CN')
                                <i class="ki-solid ki-check-circle ms-auto text-green-500"></i>
                            @endif
                        </a>
                    </li>
                    <li class="{{ app()->getLocale() == 'ms' ? 'active' : '' }}">
                        <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'ms') }}">
                            <span class="flex items-center gap-2">
                                <img alt="Bahasa" class="inline-block size-4 rounded-full object-cover shrink-0" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
                                <span class="kt-menu-title">Bahasa Melayu</span>
                            </span>
                            @if(app()->getLocale() == 'ms')
                                <i class="ki-solid ki-check-circle ms-auto text-green-500"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        </div>
        <form method="POST" action="{{ route('admin.login') }}" class="kt-card-content flex flex-col gap-5 p-6" id="sign_in_form">
            @csrf
            <div class="text-center mb-2.5">
                <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
                    {{ __('admin_login.sign_in_header') }}
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
                        <span class="kt-alert-title">{{ __('admin_login.error_title') }}</span>
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
                    {{ __('admin_login.email_username_label') }}
                </label>
                <input
                    id="login"
                    name="login"
                    class="kt-input @error('login') border-danger @enderror"
                    placeholder="{{ __('admin_login.email_username_placeholder') }}"
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
                        {{ __('admin_login.password_label') }}
                    </label>
                </div>
                <div class="kt-input @error('password') border-danger @enderror" data-kt-toggle-password="true">
                    <input id="password" name="password" placeholder="{{ __('admin_login.password_placeholder') }}" type="password" required autocomplete="current-password"/>
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
                    {{ __('admin_login.forgot_password') }}
                </a>
                @endif
                @error('password')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- REMEMBER ME --}}
            <label class="kt-label">
                <input id="remember_me" class="kt-checkbox kt-checkbox-sm" name="remember" type="checkbox" value="1"/>
                <span class="kt-checkbox-label">{{ __('admin_login.remember_me') }}</span>
            </label>

            <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
                {{ __('admin_login.sign_in_button') }}
            </button>
        </form>
    </div>
</x-admin.guest-layout>
