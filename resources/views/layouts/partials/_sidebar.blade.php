<!-- Sidebar -->
   <div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-(--sidebar-width) bg-muted [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start flex top-0 bottom-0" id="sidebar">
    <!-- Sidebar Header -->
    <div id="sidebar_header">
     <div class="flex items-center gap-2.5 px-3.5 h-[70px]">
      <a href="html/demo6/index.html">
       <img class="dark:hidden h-[42px]" src="/assets/media/app/mini-logo-circle.svg"/>
       <img class="hidden h-[42px]" src="/assets/media/app/mini-logo-circle-dark.svg"/>
      </a>
      <div class="kt-menu kt-menu-default grow" data-kt-menu="true">
       <div class="kt-menu-item grow" data-kt-menu-item-offset="0px,0px" data-kt-menu-item-placement="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="hover">
        <div class="kt-menu-label cursor-pointer text-mono font-medium grow justify-between">
         <span class="text-base font-medium text-mono grow justify-start">
          HOT TV+
         </span>
        </div>
       </div>
      </div>
     </div>
    </div>
    <!-- End of Sidebar Header -->
    <!-- Sidebar menu -->
    <div class="flex items-stretch grow shrink-0 justify-center my-5" id="sidebar_menu">
     <div class="kt-scrollable-y-auto grow" data-kt-scrollable="true" data-kt-scrollable-dependencies="#sidebar_header, #sidebar_footer" data-kt-scrollable-height="auto" data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_menu">
      <!-- Primary Menu -->
      <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" data-kt-menu-accordion-expand-all="false" id="sidebar_primary_menu">
       <div class="kt-menu-item {{ request()->routeIs('dashboard') || request()->is('/') ? 'active' : '' }}">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('dashboard') }}">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.dashboard') }}
         </span>
        </a>
       </div>
       <div class="kt-menu-item {{ request()->routeIs('license.*') ? 'here show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-profile-circle">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.license_code_management.title') }}
         </span>
         <span class="kt-menu-arrow text-muted-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <span class="inline-flex kt-menu-item-show:hidden">
           <i class="ki-filled ki-down text-xs">
           </i>
          </span>
          <span class="hidden kt-menu-item-show:inline-flex">
           <i class="ki-filled ki-up text-xs">
           </i>
          </span>
         </span>
        </div>
        <div class="kt-menu-accordion gap-px ps-7">
         <div class="kt-menu-item {{ request()->routeIs('license.generate') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('license.generate') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.license_code_management.generate') }}
           </span>
          </a>
         </div>
         <div class="kt-menu-item {{ request()->routeIs('license.list') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('license.list') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.license_code_management.list') }}
           </span>
          </a>
         </div>
        </div>
       </div>
       <div class="kt-menu-item {{ request()->routeIs('trial.*') ? 'here show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-setting-2">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.trial_code_management.title') }}
         </span>
         <span class="kt-menu-arrow text-muted-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <span class="inline-flex kt-menu-item-show:hidden">
           <i class="ki-filled ki-down text-xs">
           </i>
          </span>
          <span class="hidden kt-menu-item-show:inline-flex">
           <i class="ki-filled ki-up text-xs">
           </i>
          </span>
         </span>
        </div>
        <div class="kt-menu-accordion gap-px ps-7">
         <div class="kt-menu-item {{ request()->routeIs('trial.generate') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('trial.generate') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.trial_code_management.generate') }}
           </span>
          </a>
         </div>
         <div class="kt-menu-item {{ request()->routeIs('trial.list') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('trial.list') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.trial_code_management.list') }}
           </span>
          </a>
         </div>
         
         
        </div>
       </div>
       <div class="kt-menu-item {{ request()->routeIs('agent.*') ? 'here show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-users">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.agent_management.title') }}
         </span>
         <span class="kt-menu-arrow text-muted-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <span class="inline-flex kt-menu-item-show:hidden">
           <i class="ki-filled ki-down text-xs">
           </i>
          </span>
          <span class="hidden kt-menu-item-show:inline-flex">
           <i class="ki-filled ki-up text-xs">
           </i>
          </span>
         </span>
        </div>
        <div class="kt-menu-accordion gap-px ps-7">
         <div class="kt-menu-item {{ request()->routeIs('agent.list') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('agent.list') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.agent_management.list') }}
           </span>
          </a>
         </div>
         <div class="kt-menu-item {{ request()->routeIs('agent.create') ? 'active' : '' }}">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('agent.create') }}">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.agent_management.add_new') }}
           </span>
          </a>
         </div>
         
        </div>
       </div>
        <div class="kt-menu-item {{ request()->routeIs('hotcoin.transaction') ? 'active' : '' }}">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('hotcoin.transaction') }}">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.hotcoin_transaction') }}
         </span>
        </a>
       </div>
       <div class="kt-menu-item {{ request()->routeIs('all-agents.list') ? 'active' : '' }}">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('all-agents.list') }}">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          {{ __('messages.sidebar.all_agents') }}
         </span>
        </a>
       </div>
       
      </div>
      <!-- End of Primary Menu -->
      <div class="border-b border-input mt-4 mb-1 mx-3.5">
      </div>
      <!-- Secondary Menu -->
      <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" data-kt-menu-accordion-expand-all="true" id="sidebar_secondary_menu">
       <div class="kt-menu-item {{ request()->routeIs(['profile.edit', 'password.change', 'costing.index']) ? 'here show' : '' }}" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-label flex items-center justify-between">
         <div class="kt-menu-toggle cursor-pointer pb-2 pt-3 ps-[14.5px] rounded-md border border-transparent">
          <span class="kt-menu-arrow me-2.5">
           <span class="inline-flex kt-menu-item-show:hidden">
            <i class="ki-filled ki-down text-xs">
            </i>
           </span>
           <span class="hidden kt-menu-item-show:inline-flex">
            <i class="ki-filled ki-up text-xs">
            </i>
           </span>
          </span>
          <span class="kt-menu-title text-sm text-foreground font-medium">
           {{ __('messages.sidebar.settings.title') }}
          </span>
         </div>
        </div>
        <div class="kt-menu-accordion">
         <div class="kt-menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('profile.edit') }}">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-41">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.profile') }}
           </span>
          </a>
         </div>
         <div class="kt-menu-item {{ request()->routeIs('costing.index') ? 'active' : '' }}">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('costing.index') }}">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-39">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.costing_management') }}
           </span>
          </a>
         </div>
         <div class="kt-menu-item {{ request()->routeIs('license.list') ? 'active' : '' }}">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="{{ route('license.list') }}">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-39">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            {{ __('messages.sidebar.license_code_management.list') }}
           </span>
          </a>
         </div>
         
        </div>
       </div>
       <div class="border-b border-input mt-2 mb-1 mx-1.5">
       </div>
       
      </div>
      <!-- End of Secondary Menu -->
     </div>
    </div>
    <!-- End of Sidebar kt-menu-->
    <!-- Footer -->
    <div class="flex flex-center justify-between shrink-0 ps-4 pe-3.5 mb-3.5" id="sidebar_footer">
     <!-- User -->
     <div data-kt-dropdown="true" data-kt-dropdown-offset="10px, 10px" data-kt-dropdown-offset-rtl="-20px, 10px" data-kt-dropdown-placement="bottom-start" data-kt-dropdown-placement-rtl="bottom-end" data-kt-dropdown-trigger="click">
      <div class="cursor-pointer shrink-0" data-kt-dropdown-toggle="true">
       <img alt="" class="size-9 rounded-full border-2 border-mono/25 shrink-0" src="/assets/media/avatars/gray/5.png"/>
      </div>
      <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
       <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
        <div class="flex items-center gap-2">
         <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500" src="/assets/media/avatars/300-2.png"/>
         <div class="flex flex-col gap-1.5">
          <span class="text-sm text-foreground font-semibold leading-none">
           Anwar Ibrahim
          </span>
          <a class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none" href="html/demo6/account/home/get-started.html">
           anwar.ibrahim@gmail.com
          </a>
         </div>
        </div>
        <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">
         D
        </span>
       </div>
       <ul class="kt-dropdown-menu-sub">
        <li>
         <div class="kt-dropdown-menu-separator">
         </div>
        </li>
        <li>
         <a class="kt-dropdown-menu-link" href="html/demo6/account/home/user-profile.html">
          <i class="ki-filled ki-profile-circle">
          </i>
          {{ __('messages.sidebar.my_profile') }}
         </a>
        </li>
       
        @include('layouts/partials/_sidebar_language_switch')
        
        <li>
         <div class="kt-dropdown-menu-separator">
         </div>
        </li>
       </ul>
       <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
        <div class="flex items-center gap-2 justify-between">
         <span class="flex items-center gap-2">
          <i class="ki-filled ki-moon text-base text-muted-foreground">
          </i>
          <span class="font-medium text-2sm">
           {{ __('messages.sidebar.dark_mode') }}
          </span>
         </span>
         <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1"/>
        </div>
        {{--form logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="kt-btn kt-btn-outline justify-center w-full" type="submit">
               {{ __('messages.sidebar.log_out') }}
            </button>
        </form>
       </div>
      </div>
     </div>
     <!-- End of User -->
      
     <div class="flex items-center gap-1.5">
      <!-- Notifications -->
      
      <form id="logout-form2" action="{{ route('logout') }}" method="POST">
            @csrf
      <button class="kt-btn kt-btn-ghost kt-btn-icon size-8 hover:bg-background hover:[&_i]:text-primary" 
      data-kt-tooltip="#external_tooltip"
      data-kt-tooltip-placement="top-start" type="submit">
       <i class="ki-filled ki-exit-right">
       </i>
      </button>
      <div id="external_tooltip" class="kt-tooltip">
         {{ __('messages.sidebar.log_out') }}
      </div>
      </form>
     </div>
    </div>
    <!-- End of Footer -->
   </div>
   <!-- End of Sidebar -->