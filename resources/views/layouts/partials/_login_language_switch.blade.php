<div class="relative" data-kt-dropdown="true" data-kt-dropdown-placement="bottom-end" data-kt-dropdown-trigger="click">
    <button class="kt-btn kt-btn-icon kt-btn-sm kt-btn-ghost" data-kt-dropdown-toggle="true" title="Change language">
        @if(app()->getLocale() == 'en')
            <img alt="English" class="inline-block size-5 rounded-full" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
        @elseif(app()->getLocale() == 'zh_CN')
            <img alt="中文" class="inline-block size-5 rounded-full" src="{{ asset('assets/media/flags/china.svg') }}"/>
        @elseif(app()->getLocale() == 'ms')
            <img alt="Bahasa" class="inline-block size-5 rounded-full" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
        @endif
    </button>
    <div class="kt-dropdown-menu w-[160px]" data-kt-dropdown-menu="true">
        <ul class="kt-dropdown-menu-sub">
            <li class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'en') }}">
                    <span class="flex items-center gap-2">
                        <img alt="English" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
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
                        <img alt="中文" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/china.svg') }}"/>
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
                        <img alt="Bahasa" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
                        <span class="kt-menu-title">Bahasa</span>
                    </span>
                    @if(app()->getLocale() == 'ms')
                        <i class="ki-solid ki-check-circle ms-auto text-green-500"></i>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>
