<li data-kt-dropdown="true" data-kt-dropdown-placement="right-start" data-kt-dropdown-trigger="hover">
 <button class="kt-dropdown-menu-toggle py-1" data-kt-dropdown-toggle="true">
  <span class="flex items-center gap-2">
   <i class="ki-filled ki-icon">
   </i>
   {{ __('messages.Language') }}
  </span>
  <span class="ms-auto kt-badge kt-badge-stroke shrink-0">
   @if(app()->getLocale() == 'en')
    English
    <img alt="" class="inline-block size-3.5 rounded-full" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
   @elseif(app()->getLocale() == 'zh_CN')
    中文
    <img alt="" class="inline-block size-3.5 rounded-full" src="{{ asset('assets/media/flags/china.svg') }}"/>
   @elseif(app()->getLocale() == 'ms')
    Bahasa
    <img alt="" class="inline-block size-3.5 rounded-full" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
   @endif
  </span>
 </button>
 <div class="kt-dropdown-menu w-[180px]" data-kt-dropdown-menu="true">
  <ul class="kt-dropdown-menu-sub">
   <li class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
    <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'en') }}">
     <span class="flex items-center gap-2">
      <img alt="" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/united-states.svg') }}"/>
      <span class="kt-menu-title">
       {{ __('messages.English') }}
      </span>
     </span>
     @if(app()->getLocale() == 'en')
     <i class="ki-solid ki-check-circle ms-auto text-green-500 text-base">
     </i>
     @endif
    </a>
   </li>
   <li class="{{ app()->getLocale() == 'zh_CN' ? 'active' : '' }}">
    <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'zh_CN') }}">
     <span class="flex items-center gap-2">
      <img alt="" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/china.svg') }}"/>
      <span class="kt-menu-title">
       {{ __('messages.中文') }}
      </span>
     </span>
     @if(app()->getLocale() == 'zh_CN')
     <i class="ki-solid ki-check-circle ms-auto text-green-500 text-base">
     </i>
     @endif
    </a>
   </li>
   <li class="{{ app()->getLocale() == 'ms' ? 'active' : '' }}">
    <a class="kt-dropdown-menu-link" href="{{ route('language.switch', 'ms') }}">
     <span class="flex items-center gap-2">
      <img alt="" class="inline-block size-4 rounded-full" src="{{ asset('assets/media/flags/malaysia.svg') }}"/>
      <span class="kt-menu-title">
       {{ __('messages.Bahasa') }}
      </span>
     </span>
     @if(app()->getLocale() == 'ms')
     <i class="ki-solid ki-check-circle ms-auto text-green-500 text-base">
     </i>
     @endif
    </a>
   </li>
  </ul>
 </div>
</li>
