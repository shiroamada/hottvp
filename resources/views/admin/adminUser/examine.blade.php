@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
    <!-- Header (mobile) -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
      <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.dashboard') }}">
          <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg"/>
          <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg"/>
        </a>
        <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
          <i class="ki-filled ki-menu"></i>
        </button>
      </div>
    </header>
    <!-- End Header -->

    @include('layouts/partials/_sidebar')

    <!-- Wrapper -->
    <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
      <!-- Main -->
      <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
        <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
          <main class="grow" role="content">
            <!-- Toolbar -->
            <div class="pb-5">
              <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                  <h1 class="font-medium text-lg text-mono">
                    <i class="ki-filled ki-user me-2"></i>
                    {{ trans('adminUser.check') }}
                  </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                  <button type="button" class="kt-btn kt-btn-light"
                          onclick="window.location='{{ route('admin.users.index') }}'">
                    {{ __('general.return') }}
                  </button>
                </div>
              </div>
            </div>
            <!-- End Toolbar -->

            <!-- Container -->
            <div class="kt-container-fixed grid gap-5 lg:gap-7.5">
              <!-- Profile / Summary Card -->
              <div class="kt-card kt-card-grid">
                <div class="kt-card-content m-3">
                  <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <div class="shrink-0">
                      <img class="rounded-xl object-cover" width="165" height="165"
                           src="{{ !empty($info['photo']) ? $info['photo'] : '/public/images/users/user-09-247x247.png' }}"
                           alt="{{ $info['name'] ?? '' }}">
                    </div>

                    <div class="grow">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.name') }}:</span>
                          <span class="font-medium">{{ $info['name'] }}</span>
                        </div>

                        @if($info['is_cancel'] == 0)
                          <div class="flex gap-2">
                            <span class="text-muted-foreground">{{ __('adminUser.email') }}:</span>
                            <span class="font-medium">{{ $info['email'] }}</span>
                          </div>
                        @endif

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.phone') }}:</span>
                          <span class="font-medium">{{ $info['phone'] }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.level') }}:</span>
                          <span class="font-medium">{{ $info->levels->level_name ?? '' }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.account') }}:</span>
                          <span class="font-medium">{{ $info['account'] }}</span>
                        </div>

                        @if(($info['is_new'] ?? 1) == 0)
                          <div class="flex gap-2">
                            <span class="text-muted-foreground">{{ __('adminUser.password') }}:</span>
                            <span class="font-medium">{{ $info['password'] }}</span>
                          </div>
                        @endif

                        <div class="md:col-span-2 flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.remark') }}:</span>
                          <span class="font-medium break-words">{{ $info['remark'] }}</span>
                        </div>
                      </div>

                      <div class="border-t border-border mt-5 pt-4 flex flex-wrap items-center gap-3">
                        @if($info['is_cancel'] == 0)
                          {{-- The original examine.blade.php did not have set_level or cost buttons --}}
                          {{-- I will omit them for now to keep it similar to original examine.blade.php --}}
                        @endif

                        <!-- Metrics -->
                        <span class="ms-0 sm:ms-6 text-sm">
                          <span class="text-muted-foreground">{{ __('adminUser.balance') }}:</span>
                          <span class="font-semibold">{{ number_format($info['balance'], 2) }}</span>
                        </span>

                        <span class="text-sm">
                          <span class="text-muted-foreground">{{ __('adminUser.add_recharge') }}:</span>
                          <span class="font-semibold">{{ number_format($info['recharge'], 2) }}</span>
                        </span>

                        <span class="text-sm">
                          <span class="text-muted-foreground">{{ __('adminUser.add_profit') }}:</span>
                          <span class="font-semibold">
                            {{ number_format($profit, 2) }}
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Tabs Card --}}
@php
  $activeTab = ($tags ?? 0) == 2 ? 'recharge' : 'profit';
@endphp

<div class="kt-card kt-card-grid">
  <div class="kt-card-content">
    {{-- Tab Buttons --}}
    <div class="flex flex-wrap gap-2 m-3 pb-2 border-b border-input">
      <button
        type="button"
        class="kt-btn {{ $activeTab === 'profit' ? 'kt-btn-primary' : 'kt-btn-light' }}"
        data-tab-target="profit"
        aria-controls="tab-profit"
        aria-selected="{{ $activeTab === 'profit' ? 'true' : 'false' }}"
        role="tab"
      >
        {{ __('adminUser.profit_record') }}
      </button>

      <button
        type="button"
        class="kt-btn {{ $activeTab === 'recharge' ? 'kt-btn-primary' : 'kt-btn-light' }}"
        data-tab-target="recharge"
        aria-controls="tab-recharge"
        aria-selected="{{ $activeTab === 'recharge' ? 'true' : 'false' }}"
        role="tab"
      >
        <i class="ki-filled ki-bolt me-1"></i>
        {{ __('adminUser.recharge_record') }}
      </button>
    </div>

    {{-- Profit --}}
    <div id="tab-profit" class="{{ $activeTab === 'profit' ? '' : 'hidden' }}" role="tabpanel">
      <div class="kt-scrollable-x-auto">
        <table class="kt-table kt-table-border table-auto">
          <thead>
          <tr>
            <th class="text-start">{{ __('adminUser.create_time') }}</th>
            <th class="text-start">{{ __('adminUser.code_func') }}</th>
            <th class="text-start">{{ __('adminUser.make_profit') }}</th>
          </tr>
          </thead>
          <tbody id="list-content">
          @isset($user_profit)
            @foreach($user_profit as $v)
              @php
                $rowShow = false;
                $profitAmount = $v['money'];
                if (Auth::guard('admin')->user()->id == 1) {
                    $assort_where = ['user_id' => $v['user_id'], 'assort_id' => $v['assort_id'], 'level_id' => 3];
                    $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                    $money = $assort_level->money ?? 0;
                    $total = bcmul($money, $v['number'], 2);
                    $profitAmount = $total;
                    $rowShow = ($total > 0);
                } else {
                    $rowShow = ($v['money'] > 0);
                }

                if (Auth::guard('admin')->user()->id == 1) {
                    $details = App\Repository\Admin\AdminUserRepository::find($v['user_id']);
                } else {
                    $details = App\Repository\Admin\AdminUserRepository::find($v['own_id']);
                }
                $assort = App\Repository\Admin\AssortRepository::find($v['assort_id']);
              @endphp

              @if($rowShow)
                <tr>
                  <td>{{ $v['created_at'] }}</td>
                  <td>
                    @if($v['status'] == 1 && $v['type'] == 2)
                      {{ isset($details->name) ? $details->name.' ' : '' }}{{ __('adminUser.lower') }}
                    @elseif($v['status'] == 1 && $v['type'] == 1)
                      {{ __('adminUser.myself') }}
                    @elseif($v['status'] == 0 && $v['type'] == 1)
                      @if(isset($details->account) && $details->account == $v['user_account'])
                        {{ $details->name }} {{ __('general.generate') }} {{ $assort->assort_name ?? '' }}
                      @else
                        {{ $details->name ?? '' }} {{ __('general.as_lower') }} {{ $v['user_account'] ?? '' }} {{ __('general.generate') }} {{ $assort->assort_name ?? '' }}
                      @endif
                    @elseif($v['status'] == 0 && $v['type'] == 2)
                      {{ $details->name ?? '' }} {{ __('general.generate') }} {{ $assort->assort_name ?? '' }}
                    @endif
                  </td>
                  <td>{{ number_format($profitAmount, 2) }}</td>
                </tr>
              @endif
            @endforeach
          @endisset
          </tbody>
        </table>
      </div>

      <div class="mt-5">
        {{ $user_profit->appends(['profit'=>1])->links() }}
      </div>
    </div>

    {{-- Recharge --}}
    <div id="tab-recharge" class="{{ $activeTab === 'recharge' ? '' : 'hidden' }}" role="tabpanel">
      <div class="kt-scrollable-x-auto">
        <table class="kt-table kt-table-border table-auto">
          <thead>
          <tr>
            <th class="text-start">{{ __('adminUser.recharge_time') }}</th>
            <th class="text-start">{{ __('adminUser.recharge_num') }}</th>
          </tr>
          </thead>
          <tbody id="list-content">
          @isset($user_recharge)
            @foreach($user_recharge as $v)
              <tr>
                <td>{{ $v['created_at'] }}</td>
                <td>{{ number_format($v['money'], 2) }}</td>
              </tr>
            @endforeach
          @endisset
          </tbody>
        </table>
      </div>
      <div class="mt-5">
        {{ $user_recharge->appends(['profit'=>2])->links() }}
      </div>
    </div>
  </div>
</div>

                  <!-- End Tabs -->
                </div>
              </div>

              <!-- Footer actions (mobile duplication of top back button) -->
              <div class="flex justify-center lg:justify-end">
                <button type="button" class="kt-btn kt-btn-warning"
                        onclick="window.location='{{ route('admin.users.index') }}'">
                  {{ __('general.return') }}
                </button>
              </div>
            </div>
            <!-- End Container -->
          </main>
        </div>
      </div>
      <!-- End Main -->
    </div>
    <!-- End Wrapper -->
  </div>
  <!-- End Base -->
<!-- End Page -->

@endsection

@push('scripts')
<script>
     // Pure JS tab switcher for KT Tailwind pages
  (function () {
    const btns = document.querySelectorAll('[data-tab-target]');
    if (!btns.length) return;

    function activate(target) {
      // toggle content
      document.querySelectorAll('#tab-profit, #tab-recharge').forEach(pane => {
        pane.classList.toggle('hidden', pane.id !== `tab-${target}`);
      });
      // toggle buttons
      btns.forEach(b => {
        const isActive = b.getAttribute('data-tab-target') === target;
        b.classList.toggle('kt-btn-primary', isActive);
        b.classList.toggle('kt-btn-light', !isActive);
        b.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });
    }

    btns.forEach(b => {
      b.addEventListener('click', () => activate(b.getAttribute('data-tab-target')));
    });

    // Optional: allow ?tab=recharge in URL
    const urlTab = new URLSearchParams(location.search).get('tab');
    if (urlTab && (urlTab === 'profit' || urlTab === 'recharge')) {
      activate(urlTab);
    }
  })();

  // laydate month picker (kept for compatibility)
  if (window.laydate) {
    laydate.render({
      elem: '#date2',
      type: 'month',
      lang: '{{ app()->getLocale() }}'
    });
  }
</script>
@endpush