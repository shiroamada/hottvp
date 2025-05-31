<!-- Sidebar -->
   <div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-(--sidebar-width) bg-muted [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]" data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start flex top-0 bottom-0" id="sidebar">
    <!-- Sidebar Header -->
    <div id="sidebar_header">
     <div class="flex items-center gap-2.5 px-3.5 h-[70px]">
      <a href="html/demo6/index.html">
       <img class="dark:hidden h-[42px]" src="assets/media/app/mini-logo-circle.svg"/>
       <img class="hidden dark:inline-block h-[42px]" src="assets/media/app/mini-logo-circle-dark.svg"/>
      </a>
      <div class="kt-menu kt-menu-default grow" data-kt-menu="true">
       <div class="kt-menu-item grow" data-kt-menu-item-offset="0px,0px" data-kt-menu-item-placement="bottom-start" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="hover">
        <div class="kt-menu-label cursor-pointer text-mono font-medium grow justify-between">
         <span class="text-base font-medium text-mono grow justify-start">
          HOT TV+
         </span>
         <span class="kt-menu-arrow">
          <i class="ki-filled ki-down">
          </i>
         </span>
        </div>
        <div class="kt-menu-dropdown w-48 py-2">
         <div class="kt-menu-item">
          <a class="kt-menu-link" href="html/demo6/public-profile/profiles/default.html" tabindex="0">
           <span class="kt-menu-icon">
            <i class="ki-filled ki-profile-circle">
            </i>
           </span>
           <span class="kt-menu-title">
            English
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link" href="html/demo6.html" tabindex="0">
           <span class="kt-menu-icon">
            <i class="ki-filled ki-setting-2">
            </i>
           </span>
           <span class="kt-menu-title">
            中文
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link" href="html/demo6/network/get-started.html" tabindex="0">
           <span class="kt-menu-icon">
            <i class="ki-filled ki-users">
            </i>
           </span>
           <span class="kt-menu-title">
            Bahasa
           </span>
          </a>
         </div>
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
       <div class="kt-menu-item">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6.html">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-active:text-mono dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          Dashboard
         </span>
        </a>
       </div>
       <div class="kt-menu-item" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-profile-circle">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          License Code Management
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
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/works.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Generate License Code
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/teams.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            List Code
           </span>
          </a>
         </div>
        </div>
       </div>
       <div class="kt-menu-item" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-setting-2">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          Trial Code Management
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
         
         
         
         
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/account/integrations.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Generate Trial Code
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/account/notifications.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            List Trial Code
           </span>
          </a>
         </div>
         
         
        </div>
       </div>
       <div class="kt-menu-item" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
        <div class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent">
         <span class="kt-menu-icon items-start text-secondary-foreground text-lg kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-users">
          </i>
         </span>
         <span class="kt-menu-title font-medium text-sm text-foreground kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          Agent Management
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
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/network/get-started.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Agent List
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/network/get-started.html">
           <span class="kt-menu-title text-sm text-foreground kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Add New Agent
           </span>
          </a>
         </div>
         
        </div>
       </div>
        <div class="kt-menu-item">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6.html">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-active:text-mono dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          HotCoin Transaction
         </span>
        </a>
       </div>
       <div class="kt-menu-item">
        <a class="kt-menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6.html">
         <span class="kt-menu-icon items-start text-lg text-secondary-foreground kt-menu-item-active:text-foreground kt-menu-item-here:text-foreground kt-menu-item-show:text-foreground kt-menu-link-hover:text-foreground dark:menu-item-active:text-mono dark:menu-item-here:text-mono dark:menu-item-show:text-mono dark:menu-link-hover:text-mono">
          <i class="ki-filled ki-home-3">
          </i>
         </span>
         <span class="kt-menu-title text-sm text-foreground font-medium kt-menu-item-here:text-mono kt-menu-item-show:text-mono kt-menu-link-hover:text-mono">
          All Agents
         </span>
        </a>
       </div>
       
      </div>
      <!-- End of Primary Menu -->
      <div class="border-b border-input mt-4 mb-1 mx-3.5">
      </div>
      <!-- Secondary Menu -->
      <div class="kt-menu flex flex-col w-full gap-1.5 px-3.5" data-kt-menu="true" data-kt-menu-accordion-expand-all="true" id="sidebar_secondary_menu">
       <div class="kt-menu-item show" data-kt-menu-item-toggle="accordion" data-kt-menu-item-trigger="click">
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
           Settings
          </span>
         </div>
        </div>
        <div class="kt-menu-accordion">
         <div class="kt-menu-item">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/profiles/creator.html">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none dark:menu-item-active:text-mono kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-26">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Change Password
           </span>
          </a>
         </div>
         <div class="kt-menu-item active">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/profiles/company.html">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none dark:menu-item-active:text-mono kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-41">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Profile
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/profiles/nft.html">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none dark:menu-item-active:text-mono kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-39">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Costing Management
           </span>
          </a>
         </div>
         <div class="kt-menu-item">
          <a class="kt-menu-link py-1 px-2 my-0.5 rounded-md border border-transparent kt-menu-item-active:border-border kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-border" href="html/demo6/public-profile/profiles/blogger.html">
           <span class="kt-menu-icon text-secondary-foreground kt-menu-link-hover:text-mono rounded-md flex place-content-center size-7 me-2.5 bg-border border border-input kt-menu-item-active:border-none kt-menu-link-hover:border-light kt-menu-item-active:bg-background kt-menu-link-hover:bg-background kt-menu-link-hover:border-none dark:menu-item-active:text-mono kt-menu-icon-xs">
            <i class="ki-filled ki-abstract-35">
            </i>
           </span>
           <span class="kt-menu-title text-sm text-secondary-foreground kt-menu-item-active:font-medium kt-menu-item-active:text-mono kt-menu-link-hover:text-mono">
            Delete Account
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
       <img alt="" class="size-9 rounded-full border-2 border-mono/25 shrink-0" src="assets/media/avatars/gray/5.png"/>
      </div>
      <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
       <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
        <div class="flex items-center gap-2">
         <img alt="" class="size-9 shrink-0 rounded-full border-2 border-green-500" src="assets/media/avatars/300-2.png"/>
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
          My Profile
         </a>
        </li>
        
        <li data-kt-dropdown="true" data-kt-dropdown-placement="right-start" data-kt-dropdown-trigger="hover">
         <button class="kt-dropdown-menu-toggle py-1" data-kt-dropdown-toggle="true">
          <span class="flex items-center gap-2">
           <i class="ki-filled ki-icon">
           </i>
           Language
          </span>
          <span class="ms-auto kt-badge kt-badge-stroke shrink-0">
           English
           <img alt="" class="inline-block size-3.5 rounded-full" src="assets/media/flags/united-states.svg"/>
          </span>
         </button>
         <div class="kt-dropdown-menu w-[180px]" data-kt-dropdown-menu="true">
          <ul class="kt-dropdown-menu-sub">
           <li class="active">
            <a class="kt-dropdown-menu-link" href="?dir=ltr">
             <span class="flex items-center gap-2">
              <img alt="" class="inline-block size-4 rounded-full" src="assets/media/flags/united-states.svg"/>
              <span class="kt-menu-title">
               English
              </span>
             </span>
             <i class="ki-solid ki-check-circle ms-auto text-green-500 text-base">
             </i>
            </a>
           </li>
           <li class="">
            <a class="kt-dropdown-menu-link" href="?dir=rtl">
             <span class="flex items-center gap-2">
              <img alt="" class="inline-block size-4 rounded-full" src="assets/media/flags/china.svg"/>
              <span class="kt-menu-title">
               中文
              </span>
             </span>
            </a>
           </li>
           <li class="">
            <a class="kt-dropdown-menu-link" href="?dir=ltr">
             <span class="flex items-center gap-2">
              <img alt="" class="inline-block size-4 rounded-full" src="assets/media/flags/malaysia.svg"/>
              <span class="kt-menu-title">
               Bahasa
              </span>
             </span>
            </a>
           </li>
          </ul>
         </div>
        </li>
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
           Dark Mode
          </span>
         </span>
         <input class="kt-switch" data-kt-theme-switch-state="dark" data-kt-theme-switch-toggle="true" name="check" type="checkbox" value="1"/>
        </div>
        <a class="kt-btn kt-btn-outline justify-center w-full" href="html/demo6/authentication/classic/sign-in.html">
         Log out
        </a>
       </div>
      </div>
     </div>
     <!-- End of User -->
      
     <div class="flex items-center gap-1.5">
      <!-- Notifications -->
      
      <a class="kt-btn kt-btn-ghost kt-btn-icon size-8 hover:bg-background hover:[&_i]:text-primary" href="html/demo6/authentication/classic/sign-in.html">
       <i class="ki-filled ki-exit-right">
       </i>
      </a>
     </div>
    </div>
    <!-- End of Footer -->
   </div>
   <!-- End of Sidebar -->