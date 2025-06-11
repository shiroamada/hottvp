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
                        <i class="ki-filled ki-abstract-28 me-2"></i>
                        {{ __('messages.agent_list.title') }}
                    </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                    <a href="{{ route('agent.create') }}" class="kt-btn kt-btn-primary">{{ __('messages.agent_list.add_new_agent') }}</a>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5">
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header flex-wrap gap-2">
                        <div class="flex flex-wrap gap-2 lg:gap-5">
                            <div class="flex">
                                <input class="kt-input w-40" placeholder="{{ __('messages.agent_list.agent_name_placeholder') }}" type="text" value=""/>
                            </div>
                            <div class="flex flex-wrap gap-2.5">
                                <button class="kt-btn kt-btn-primary">{{ __('messages.agent_list.search') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="kt-card-content">
                        <div class="grid">
                            <div class="kt-scrollable-x-auto">
                                <table class="kt-table table-auto kt-table-border">
                                    <thead>
                                    <tr>
                                        <th class="text-start min-w-[80px]">{{ __('messages.agent_list.table.id') }}</th>
                                        <th class="text-start min-w-[150px]">{{ __('messages.agent_list.table.agent_name') }}</th>
                                        <th class="text-start min-w-[150px]">{{ __('messages.agent_list.table.agent_level') }}</th>
                                        <th class="text-start min-w-[100px]">{{ __('messages.agent_list.table.balance') }}</th>
                                        <th class="text-start min-w-[200px]">{{ __('messages.agent_list.table.accumulated_profit') }}</th>
                                        <th class="text-start min-w-[150px]">{{ __('messages.agent_list.table.remark') }}</th>
                                        <th class="text-start min-w-[200px]">{{ __('messages.agent_list.table.created_time') }}</th>
                                        <th class="text-end">{{ __('messages.agent_list.table.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{-- @foreach($agents as $agent) --}}
                                        <tr>
                                            <td>25</td>
                                            <td>Unstoppable</td>
                                            <td>Diamond Pro</td>
                                            <td>558.25</td>
                                            <td>226,046.50</td>
                                            <td></td>
                                            <td>2020-07-26 23:52:28</td>
                                            <td class="text-end">
                                                <div class="kt-menu-item grow" data-kt-menu-item-offset="0px,0px" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="click|hover">
                                                    <button class="kt-btn kt-btn-sm kt-btn-primary">{{ __('messages.agent_list.table.action') }}</button>
                                                    <div class="kt-dropdown-menu">
                                                        <div class="kt-menu-item">
                                                            <a class="kt-menu-link" href="#">Action 1</a>
                                                        </div>
                                                        <div class="kt-menu-item">
                                                            <a class="kt-menu-link" href="#">Action 2</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    {{-- @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
