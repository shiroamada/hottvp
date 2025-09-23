@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
    
     <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
      <i class="ki-filled ki-menu"></i>
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
                    <a href="{{ route('admin.users.create') }}" class="kt-btn kt-btn-primary">{{ __('messages.agent_list.add_new_agent') }}</a>
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
                            <form name="admin_list_sea" class="form-search flex flex-wrap gap-2" method="get"
                                  action="{{ route('admin.users.index') }}">
                                @csrf
                                <div class="flex">
                                    <input class="kt-input w-40" placeholder="{{ __('messages.agent_list.agent_name_placeholder') }}" 
                                           type="text" name="name" value="{{ $condition['name'] ?? '' }}"/>
                                </div>
                                <div class="flex flex-wrap gap-2.5">
                                    <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.agent_list.search') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="kt-card-content">
                        <div class="grid">
                            <div class="kt-scrollable-x-auto">
                                <table class="kt-table table-auto kt-table-border">
                                    <thead>
                                    <tr>
                                        <th class="text-start">{{ __('messages.agent_list.table.id') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.agent_name') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.agent_level') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.balance') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.accumulated_profit') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.remark') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.created_time') }}</th>
                                        <th class="text-end">{{ __('messages.agent_list.table.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @isset($lists)
                                        @foreach($lists as $v)
                                            <tr>
                                                <td>{{ $v['id'] }}</td>

                                                @if($v['is_cancel'] != 0)
                                                    <td class="text-muted">{{ $v['name'] ?? "" }} {{ __('messages.general.is_del') }}</td>
                                                @else
                                                    <td>{{ $v['name'] ?? "" }}</td>
                                                @endif

                                                <td>
                                                    {{ $v->levels->level_name ?? "" }}
                                                    @if(auth()->guard('admin')->user()->level_id <= 3)
                                                        @if($v->type == 2)
                                                            <span class="badge badge-primary">Pro</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <td>{{ number_format($v['balance'], 2) }}</td>

                                                <td>
                                                    <?php
                                                    if (auth()->guard('admin')->user()->id == 1) {
                                                        $where = ["user_id" => $v['id'], 'status' => 0];
                                                    } else {
                                                        $where = ["user_id" => auth()->guard('admin')->user()->id, 'status' => 0, 'type' => 1, 'create_id' => $v['id']];
                                                    }
                                                    $user_lirun = App\Models\Admin\Huobi::query()->where($where)->get();
                                                    $user_pro = 0;
                                                    foreach ($user_lirun as $value) {
                                                        $assort_where = ['user_id' => $parent_id, 'assort_id' => $value['assort_id'], 'level_id' => 3];
                                                        $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                                        $total = isset($assort_level->money) ? bcmul($assort_level->money, $value['number'], 2) : 0;
                                                        $user_pro += $total;
                                                    }
                                                    $profit = App\Repository\Admin\HuobiRepository::levelByRecord($where);
                                                    ?>
                                                    @if(auth()->guard('admin')->user()->id == 1)
                                                        {{ number_format($user_pro, 2) }}
                                                    @else
                                                        {{ number_format($profit, 2) }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if(mb_strlen($v['remark']) > 10)
                                                        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                                                            <span class="cursor-pointer">
                                                                {{ mb_substr($v['remark'], 0, 10) }}...
                                                            </span>
                                                            <div x-show="open"
                                                                 x-transition
                                                                 class="absolute z-10 w-64 p-2 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-gray-800 rounded-lg shadow-lg">
                                                                {{ $v['remark'] }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{ $v['remark'] }}
                                                    @endif
                                                </td>

                                                <td>{{ $v['created_at'] }}</td>

                                                <td class="text-end">
                                                  <!-- KTUI Dropdown wrapper -->
                                                  <div class="inline-flex"
                                                       data-kt-dropdown="true"
                                                       data-kt-dropdown-trigger="click"
                                                       data-kt-dropdown-placement="bottom-end">
                                                    <button type="button" class="kt-btn kt-btn-sm kt-btn-primary"
                                                            data-kt-dropdown-toggle="true">
                                                      {{ __('messages.agent_list.table.action') }}
                                                    </button>

                                                    <!-- KTUI Dropdown content -->
                                                    <div class="kt-dropdown-menu w-56" data-kt-dropdown-menu="true">
                                                      <ul class="kt-dropdown-menu-sub">
                                                        @if(auth()->guard('admin')->user()->id != 2)
                                                          <li>
                                                            <a class="kt-dropdown-menu-link kt-link-stripe"
                                                               href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                              {{ __('messages.agent_list.check') }}
                                                            </a>
                                                          </li>

                                                          @if($v['is_cancel'] == 0)
                                                            <li>
                                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                                 href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                                {{ __('messages.agent_list.recharge') }}
                                                              </a>
                                                            </li>
                                                          @endif

                                                          @if($v['is_cancel'] != 2)
                                                            <li>
                                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                                 href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                                {{ __('messages.agent_list.lower_agent') }}
                                                              </a>
                                                            </li>
                                                          @endif
                                                        @else
                                                          <li>
                                                            <a class="kt-dropdown-menu-link kt-link-stripe"
                                                               href="{{ route('admin.users.look', ['id' => $v['id']]) }}">
                                                              {{ __('messages.agent_list.check_cost') }}
                                                            </a>
                                                          </li>
                                                          <li>
                                                            <a class="kt-dropdown-menu-link kt-link-stripe"
                                                               href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                              {{ __('messages.agent_list.lower') }}
                                                            </a>
                                                          </li>
                                                          <li>
                                                            <a class="kt-dropdown-menu-link kt-link-stripe"
                                                               href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                              {{ __('messages.agent_list.check') }}
                                                            </a>
                                                          </li>
                                                          <li>
                                                            <a class="kt-dropdown-menu-link kt-link-stripe"
                                                               href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                              {{ __('messages.agent_list.recharge') }}
                                                            </a>
                                                          </li>
                                                        @endif
                                                      </ul>
                                                    </div>
                                                  </div>
                                                  <!-- End KTUI Dropdown -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="pages" class="mt-5">
                            {{ $lists->appends(['name' => $lists->name ?? null])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Container -->
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

@push('styles')
<style>
  /* Reusable “left stripe + padding shift” for KTUI items */
  .kt-link-stripe{
    position:relative;display:block;padding:.5rem .75rem .5rem 1rem;transition:all .2s ease
  }
  .kt-link-stripe::before{
    content:"";position:absolute;inset-block:0;inset-inline-start:0;width:3px;
    background: var(--kt-primary, rgb(99 102 241));opacity:0;transition:opacity .2s ease
  }
  .kt-link-stripe:hover,.kt-link-stripe:focus-visible{
    background: rgba(0,0,0,.06);padding-inline-start: calc(1rem - 3px);outline:none
  }
  .kt-link-stripe:hover::before,.kt-link-stripe:focus-visible::before{opacity:1}
  .dark .kt-link-stripe:hover,.dark .kt-link-stripe:focus-visible{background: rgba(255,255,255,.10)}
</style>
@endpush

@push('scripts')
<script>
  // This script addresses the issue of dropdowns inside a scrollable table being clipped.
  // It works by temporarily setting the 'overflow' property of the scrollable container to 'visible'
  // when a dropdown is open, allowing it to render outside the container's bounds.

  const handleToggle = (e) => {
    if (e.target.tagName.toLowerCase() !== 'details') return;

    const dropdown = e.target;
    const scrollParent = dropdown.closest('.kt-scrollable-x-auto');
    if (!scrollParent) return;

    // --- Close other dropdowns ---
    if (dropdown.hasAttribute('open')) {
      document.querySelectorAll('td details[open]').forEach(d => {
        if (d !== dropdown) d.removeAttribute('open');
      });
    }

    // --- Manage Overflow ---
    // Use a timeout to allow the close-others logic to finish before we check for open dropdowns.
    setTimeout(() => {
      const anyOpen = scrollParent.querySelector('details[open]');
      if (anyOpen) {
        scrollParent.style.overflow = 'visible';
      } else {
        // Use overflowX to avoid affecting vertical scroll behavior
        scrollParent.style.overflow = 'auto';
      }
    }, 0);

    // --- ARIA attributes ---
    const summary = dropdown.querySelector('summary');
    if (summary) {
      summary.setAttribute('aria-expanded', dropdown.hasAttribute('open'));
    }
  };

  const handleClickOutside = (e) => {
    const openDetails = document.querySelectorAll('td details[open]');
    if (openDetails.length === 0) return;

    const isClickInside = Array.from(openDetails).some(d => d.contains(e.target));

    if (!isClickInside) {
      openDetails.forEach(d => d.removeAttribute('open'));
      // The 'toggle' event will fire for each closed dropdown and handle the overflow reset.
    }
  };

  document.addEventListener('toggle', handleToggle, true);
  document.addEventListener('click', handleClickOutside);

  // Initialize aria-expanded = false on page load
  document.querySelectorAll('td details > summary').forEach(s => s.setAttribute('aria-expanded', 'false'));
</script>
@endpush
