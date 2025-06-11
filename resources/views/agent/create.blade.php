@extends('layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
     <a href="html/demo6.html">
      <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg"/>
      <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg"/>
     </a>
     <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
      <i class="ki-filled ki-menu">
      </i>
     </button>
    </div>
    <!-- End of Container -->
   </header>
   <!-- End of Header -->
   @include('layouts/partials/_sidebar')
   <!-- Wrapper -->
   <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
    <!-- Main -->
    <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
     <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
      <main class="grow" role="content">
       <!-- Toolbar -->
        <div class="pb-5">
            <!-- Container -->
            <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                    <h1 class="font-medium text-lg text-mono">
                        {{ __('messages.agent_create.title') }}
                    </h1>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5 xl:w-[38.75rem] mx-auto">
                <div class="kt-card pb-2.5">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_create.title') }}</h3>
                    </div>
                    <form>
                        <div class="kt-card-content grid gap-5">
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.agent_list.table.agent_name') }}</label>
                                <input class="kt-input grow" id="agent_name" type="text" placeholder="{{ __('messages.agent_list.agent_name_placeholder') }}">
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.agent_list.table.agent_level') }}</label>
                                <select class="kt-select grow" id="agent_level">
                                    <option value="">{{ __('messages.agent_create.select_level') }}</option>
                                    <option value="gold">Gold</option>
                                    <option value="silver">Silver</option>
                                    <option value="bronze">Bronze</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.agent_list.table.remark') }}</label>
                                <textarea class="kt-input grow" id="remark" rows="3"></textarea>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.agent_create.top_up_amount') }}</label>
                                <div class="grow">
                                    <input class="kt-input" id="top_up_amount" type="text">
                                    <p class="text-sm text-muted-foreground mt-1">{{ __('messages.agent_create.available_hotcoin') }}: <span class="font-bold text-primary">710.50</span></p>
                                </div>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.agent_create.permission') }}</label>
                                <div class="flex gap-5 grow">
                                    <div class="flex items-center gap-2">
                                        <input class="kt-radio" type="radio" name="permission" id="permission_normal" value="normal" checked>
                                        <label for="permission_normal">{{ __('messages.agent_create.permission_normal') }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="kt-radio" type="radio" name="permission" id="permission_enhanced" value="enhanced">
                                        <label for="permission_enhanced">{{ __('messages.agent_create.permission_enhanced') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button class="kt-btn kt-btn-outline-secondary" type="reset">{{ __('messages.agent_create.reset') }}</button>
                                <a href="{{ route('agent.list') }}" class="kt-btn kt-btn-outline">{{ __('messages.agent_create.return') }}</a>
                                <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.agent_list.add_new_agent') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End of Container -->
      </main>
     </div>
    </div>
    <!-- End of Main -->
   </div>
   <!-- End of Wrapper -->
  </div>
  <!-- End of Base -->
<!-- End of Page -->
@endsection
