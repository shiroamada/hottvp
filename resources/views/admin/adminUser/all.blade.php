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
          <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg" />
          <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg" />
        </a>
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
                    {{ trans('adminUser.managers') }}
                  </h1>
                </div>
                <!-- <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                  <a href="{{ route('admin.users.create') }}" class="kt-btn kt-btn-primary">
                    {{ trans('adminUser.newAdministrator') }}
                  </a>
                </div> -->
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
                      <form name="admin_list_sea" class="form-search flex flex-wrap gap-2.5" method="get" action="{{ route('admin.users.all') }}">
                        {{ csrf_field() }}
                        <div class="flex">
                          <input class="kt-input w-40"
                                 type="text"
                                 placeholder="{{ trans('adminUser.name') }}"
                                 name="name"
                                 value="{{ $condition['name'] ?? '' }}"
                                 autocomplete="off">
                        </div>
                        <div class="flex">
                            <select class="kt-input w-40" name="level_id">
                                <option value="-1">{{ trans('general.select_level') }}</option>
                                @foreach($level_list ?? null as $key => $v)
                                    <option value="{{ $v['id'] }}"
                                            @if(isset($condition['level_id']) && $condition['level_id'] == $v['id']) selected @endif>{{ $v['level_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                          <button class="kt-btn kt-btn-success" type="submit" id="submitBtn">
                            {{ trans('general.search') }}
                          </button>
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
                            @isset($lists)
                              @foreach($lists as $list)
                                <tr>
                                  <td>{{ $list->id }}</td>

                                  @if($list->is_cancel != 0)
                                    <td title="{{ $list->name ?? '' }}">{{ $list->name ?? '' }} {{ trans('general.is_del') }}</td>
                                  @else
                                    <td title="{{ $list->name ?? '' }}">{{ $list->name ?? '' }}</td>
                                  @endif

                                  <td>
                                    {{ $list->levels->level_name ?? '' }}
                                    @if(Auth::guard('admin')->user()->level_id <= 3)
                                      @if($list->type == 2)
                                        <i>Pro</i>
                                      @endif
                                    @endif
                                  </td>

                                  <td>{{ number_format($list->balance, 2) }}</td>

                                  <td>
                                    @php
                                      $profit = 0;
                                      if (isset($list->monthlyProfits)) {
                                          $profit = $list->monthlyProfits->sum('profit');
                                      }
                                    @endphp
                                    {{ number_format($profit, 2) }}
                                  </td>

                                  <td title="{{ $list->remark }}">
                                    @if(mb_strlen($list->remark) > 10)
                                      {{ mb_substr($list->remark, 0, 10) }}...
                                    @else
                                      {{ $list->remark }}
                                    @endif
                                  </td>

                                  <td>{{ $list->created_at }}</td>

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
                                          @if(Auth::guard('admin')->user()->id != 1)
                                            <li>
                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                 href="{{ route('admin.users.visual', ['id' => $list->id]) }}">
                                                {{ trans('adminUser.check') }}
                                              </a>
                                            </li>

                                            @if($list->is_cancel == 0)
                                              <li>
                                                <a class="kt-dropdown-menu-link kt-link-stripe"
                                                   href="{{ route('admin.users.recharge', ['id' => $list->id]) }}">
                                                  {{ trans('adminUser.chongzhi') }}
                                                </a>
                                              </li>
                                            @endif

                                            @if($list->is_cancel != 2)
                                              <li>
                                                <a class="kt-dropdown-menu-link kt-link-stripe"
                                                   href="{{ route('admin.users.lower', ['id' => $list->id]) }}">
                                                  {{ trans('adminUser.lower_agent') }}
                                                </a>
                                              </li>
                                              
                                            @endif
                                          @else
                                            <li>
                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                 href="{{ route('admin.users.look', ['id' => $list->id]) }}">
                                                {{ trans('adminUser.check_cost') }}
                                              </a>
                                            </li>
                                            <li>
                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                 href="{{ route('admin.users.lower', ['id' => $list->id]) }}">
                                                {{ trans('adminUser.lower_agent') }}
                                              </a>
                                            </li>
                                            <li>
                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                 href="{{ route('admin.users.check', ['id' => $list->id]) }}">
                                                {{ trans('adminUser.check') }}
                                              </a>
                                            </li>
                                            <li>
                                              <a class="kt-dropdown-menu-link kt-link-stripe"
                                                 href="{{ route('admin.users.recharge', ['id' => $list->id]) }}">
                                                {{ trans('adminUser.chongzhi') }}
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
                  </div>

                  <div class="kt-card-footer">
                    @isset($lists)
                      {!! $lists->appends(request()->except('page'))->render() !!}
                    @endisset
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
  {{-- Ensure KTUI JS is available (remove if your master already loads it) --}}
  <script src="{{ asset('assets/js/ktui.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.KTDropdown && typeof KTDropdown.createInstances === 'function') {
        KTDropdown.createInstances();
      }
    });
  </script>
@endpush
