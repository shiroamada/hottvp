@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
    <!-- Header (mobile) -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
      <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
        
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
                    {{ __('adminUser.check') }}
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
<!-- if not yet verify, show password -->
                        <!-- @if(($info['is_new'] ?? 1) == 0)
                          <div class="flex gap-2">
                            <span class="text-muted-foreground">{{ __('adminUser.password') }}:</span>
                            <span class="font-medium">{{ $info['password'] }}</span>
                          </div>
                        @endif -->

                        <div class="md:col-span-2 flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.remark') }}:</span>
                          <span class="font-medium break-words">{{ $info['remark'] }}</span>
                        </div>
                      </div>

                      <div class="border-t border-border mt-5 pt-4 flex flex-wrap items-center gap-3">
                        @if($info['is_cancel'] == 0)
                          <button type="button" class="kt-btn kt-btn-primary"
                                  data-kt-modal-toggle="#remarkModal">
                            {{ __('adminUser.up_remark') }}
                          </button>

                          @if($type == 2 || auth('admin')->user()->id == 1)
                            <button class="kt-btn kt-btn-primary" disabled>
                              {{ __('adminUser.set_level') }}
                            </button>
                          @else
                            <a class="kt-btn kt-btn-primary"
                               href="{{ route('admin.users.level', ['id' => $info['id']]) }}">
                              {{ __('adminUser.set_level') }}
                            </a>
                          @endif

                          @if(($info['level_id'] ?? null) == 8)
                            <a class="kt-btn kt-btn-primary"
                               href="{{ route('admin.users.cost', ['id' => $info['id']]) }}">
                              {{ __('adminUser.cost') }}
                            </a>
                          @endif
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
                            @if(auth('admin')->user()->id == 1)
                              {{ number_format($user_pro, 2) }}
                            @else
                              {{ number_format($profit, 2) }}
                            @endif
                          </span>
                        </span>

                        @if(auth('admin')->user()->id == 1)
                          <span class="text-sm">
                            <span class="text-muted-foreground">{{ __('adminUser.last_profit') }}:</span>
                            <span class="font-semibold">{{ number_format($profit_time, 2) }}</span>
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Tabs Card (FIXED) --}}
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
      @if(auth('admin')->user()->id == 1)
        <form name="export_profit" class="flex flex-wrap items-end gap-3 mb-5" method="post"
              action="{{ route('admin.users.export') }}" id="export">
          @csrf
          <input type="hidden" name="user_id" value="{{ $info['id'] }}">
          <input type="hidden" name="name" value="{{ $info['name'] }}">
          <div class="flex flex-col gap-1">
            <label for="date2" class="text-sm text-muted-foreground">{{ __('general.export_excel') }}</label>
            <input class="kt-input w-48" type="text" name="month" id="date2" autocomplete="off"
                   placeholder="{{ __('general.export_excel') }}">
          </div>
          <button class="kt-btn kt-btn-success" type="submit" id="submitBtn">
            {{ __('general.excel') }}
          </button>
        </form>
      @endif

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
                if (auth('admin')->user()->id == 1) {
                    $assort_where = ['user_id' => $v['user_id'], 'assort_id' => $v['assort_id'], 'level_id' => 3];
                    $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                    $money = $assort_level->money ?? 0;
                    $total = bcmul($money, $v['number'], 2);
                    $profitAmount = $total;
                    $rowShow = ($total > 0);
                } else {
                    $rowShow = ($v['money'] > 0);
                }

                if (auth('admin')->user()->id == 1) {
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

<!-- Remark Modal -->
<div id="remarkModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="fixed inset-0 bg-black/40" data-kt-modal-dismiss></div>

    <div class="relative bg-card text-card-foreground w-full max-w-lg rounded-2xl shadow-xl border border-border">
      <div class="p-5 border-b border-border flex items-center justify-between">
        <h5 class="text-base font-semibold">{{ __('general.message') }}</h5>
        <button class="kt-btn kt-btn-icon" data-kt-modal-dismiss aria-label="Close" onclick="clears()">
          <i class="ki-filled ki-cross"></i>
        </button>
      </div>

      <div class="p-5">
        <form action="javascript:void(0)" method="post" id="form">
          @csrf
          <input type="hidden" id="beizhu" value="{{ $info['remark'] }}">
          <div class="grid gap-2">
            <label for="standardRemark" class="text-sm text-muted-foreground">{{ __('adminUser.remark') }}</label>
            <textarea class="kt-input min-h-28" id="standardRemark" name="remark" maxlength="128">{{ $info['remark'] }}</textarea>
          </div>
        </form>
      </div>

      <div class="p-5 border-t border-border flex justify-end gap-2">
        <button class="kt-btn" data-kt-modal-dismiss onclick="clears()">{{ __('general.cancel') }}</button>
        <button class="kt-btn kt-btn-primary"
                onclick="adminRemark('{{ route('admin.users.remark', ['id' => $info['id']]) }}')">
          {{ __('general.confirm') }}
        </button>
      </div>
    </div>
  </div>
</div>
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
  // --- KT modal toggles (simple) ---
  document.querySelectorAll('[data-kt-modal-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-kt-modal-toggle');
      const el = document.querySelector(target);
      if (el) el.classList.remove('hidden');
    });
  });
  document.querySelectorAll('[data-kt-modal-dismiss]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#remarkModal').forEach(m => m.classList.add('hidden'));
    });
  });
  // click outside to close
  document.addEventListener('click', (e) => {
    const modal = document.getElementById('remarkModal');
    if (!modal || modal.classList.contains('hidden')) return;
    if (e.target.matches('#remarkModal .bg-black\\/40')) modal.classList.add('hidden');
  });

  // laydate month picker (kept for compatibility)
  if (window.laydate) {
    laydate.render({
      elem: '#date2',
      type: 'month',
      lang: '{{ app()->getLocale() }}'
    });
  }

  function clears() {
    const remark = document.getElementById('beizhu')?.value || '';
    const ta = document.getElementById('standardRemark');
    if (ta) ta.value = remark;
  }

  const token = document.querySelector('input[name="_token"]')?.value || '';
  function adminRemark(url) {
    const remark = document.getElementById('standardRemark')?.value || '';
    $.ajax({
      url: url,
      type: "PUT",
      data: {remark},
      headers: {'X-CSRF-Token': token},
      success: function (result) {
        if (result.code !== 0) {
          layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
          return;
        }
        layer.msg(result.msg, {shift: 1}, function () {
          if (result.reload) location.reload();
          if (result.redirect) location.href = '{!! url()->current() !!}';
        });
      },
      error: function (resp) {
        const code = resp?.status;
        const t = {
          422: "{{ __('general.illegal_request') }}",
          404: "{{ __('general.resources_not') }}",
          401: "{{ __('general.login_first') }}",
          429: "{{ __('general.Overvisiting') }}",
          419: "{{ __('general.illegal_request') }}",
          500: "{{ __('general.internal_error') }}"
        }[code];
        if (t) { layer.msg(t, {shift: 6, skin: 'alert-secondary alert-lighter'}); return; }
        try { const parse = $.parseJSON(resp.responseText); if (parse) layer.alert(parse.msg); } catch(e){}
      }
    });
  }
</script>
@endpush

