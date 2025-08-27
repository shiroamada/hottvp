@extends('admin.layouts.master')

@section('content')
<!-- Page -->
<div class="flex grow">
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
                  <i class="ki-filled ki-abstract-28 me-2"></i>
                  {{ __('adminUser.chongzhi') }}
                </h1>
              </div>
              <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                <button class="kt-btn kt-btn-secondary" onclick="history.go(-1);">
                  {{ __('general.return') }}
                </button>
              </div>
            </div>
          </div>
          <!-- End Toolbar -->

          <!-- Container -->
          <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5">

              <!-- Profile/Summary (adds avatar/email/phone/password as in legacy) -->
              <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-conten m-4">
                  <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <!-- <div class="shrink-0">
                      <img
                        class="rounded-xl object-cover"
                        width="165" height="165"
                        src="{{ !empty($info['photo']) ? $info['photo'] : '/public/images/users/user-09-247x247.png' }}"
                        alt="{{ $info['name'] ?? '' }}"
                        style="height:165px"
                      >
                    </div> -->

                    <div class="grow grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.name') }}:</span>
                        <span class="font-medium break-words">{{ $info['name'] }}</span>
                      </div>
                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.email') }}:</span>
                        <span class="font-medium break-words">{{ $info['email'] }}</span>
                      </div>

                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.phone') }}:</span>
                        <span class="font-medium break-words">{{ $info['phone'] }}</span>
                      </div>
                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.level') }}:</span>
                        <span class="font-medium break-words">{{ $info->levels->level_name ?? '' }}</span>
                      </div>

                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.account') }}:</span>
                        <span class="font-medium break-words">{{ $info['account'] }}</span>
                      </div>
                      {{-- Remark (full width row) --}}
                    <div class="md:col-span-2">
                      <div class="text-sm text-muted-foreground mb-1">{{ __('adminUser.remark') }}:</div>
                      <div class="font-medium break-words">{{ $info['remark'] ?? '' }}</div>
                    </div>
                      @if(($info['is_new'] ?? 1) == 0)
                      <div class="flex gap-2">
                        <span class="text-muted-foreground">{{ __('adminUser.password') }}:</span>
                        <span class="font-medium break-words">{{ $info['password'] }}</span>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <!-- Recharge Form Card -->
              <div class="kt-card kt-card-grid min-w-full">
                <div class="kt-card-header">
                  <h3 class="kt-card-title">{{ __('messages.agent_recharge.form_title') }}</h3>
                </div>

                <div class="kt-card-content m-4">
                  <form method="post" action="{{ route('admin.users.pay') }}" id="form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id" value="{{ $info['id'] }}">

                    <!-- Your balance notice (use_huobi) -->
                    <div class="text-sm">
                      <span class="text-muted-foreground">{{ __('adminUser.use_huobi') }}</span>
                      <span class="font-semibold text-warning ms-1">
                        {{ number_format(auth()->guard('admin')->user()->balance, 2) }}
                      </span>
                    </div>

                    <!-- Amount -->
                    <div>
                      <label for="balance" class="kt-form-label">{{ __('adminUser.recharge') }}</label>
                      <div class="relative">
                        <span class="absolute start-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">RM</span>
                        <input
                          class="kt-input ps-10"
                          type="text"
                          name="balance"
                          id="balance"
                          inputmode="decimal"
                          autocomplete="off"
                          placeholder="0.00"
                          onkeyup="onlyNumber(this)"
                          onblur="onlyNumber(this)"
                          onmouseover="onlyNumber(this)"
                          maxlength="14"
                          aria-label="Balance"
                        >
                      </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                      <div class="flex gap-3 order-2 sm:order-1">
                        <button type="reset" class="kt-btn">{{ __('general.reset') }}</button>
                        <button type="button" class="kt-btn kt-btn-warning" onclick="history.go(-1);">
                          {{ __('general.return') }}
                        </button>
                      </div>
                      <button class="kt-btn kt-btn-primary order-1 sm:order-2" type="submit" id="submitBtn">
                        {{ __('adminUser.chongzhi') }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- End Card -->

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
<!-- End Page -->
@endsection

@push('scripts')
<script>
  // === Number filter (match legacy logic, keep 2 decimals, thousand separators) ===
  function TripartiteMethod(num) {
    num = (num || '').toString().replace(/,/g, '');
    const hasDot = num.indexOf('.') >= 0;
    let left = hasDot ? num.split('.')[0] : num;
    let right = hasDot ? (num.split('.')[1] || '') : '';

    // thousands on left
    left = left.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    return hasDot ? (left + '.' + right) : left;
  }

  window.onlyNumber = function (obj) {
    const t = obj.value.charAt(0);
    let v = obj.value;

    // strip non-numeric (allow dot)
    v = v.replace(/[^\d.]/g, '');
    // leading zeros
    v = v.replace(/^0\d[0-9]*/g, '');
    // first char not dot
    v = v.replace(/^\./g, '');
    // single dot
    v = v.replace(/\.{2,}/g, '.');
    v = v.replace('.', '$#
).replace(/\./g, '').replace('$#
, '.');
    // keep 2 decimals
    v = v.replace(/^(\-)?(\d+)\.(\d\d).*$/, '$1$2.$3');

    // apply thousands
    v = TripartiteMethod(v);

    obj.value = v;
    if (t === '-') return;
  };

  // === Submit (jQuery style to match your stack) ===
  $(function () {
    const typeFlag = @json($type ?? null); // legacy conditional redirect
    $('#form').on('submit', function (e) {
      e.preventDefault();
      const $btn = $('#submitBtn').prop('disabled', true);

      // remove commas before submit
      const $bal = $('#balance');
      $bal.val(($bal.val() || '').replace(/,/g, ''));

      $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (result) {
          if (result.code !== 0) {
            alert(result.msg || '{{ __("messages.general.error") }}');
            $btn.prop('disabled', false);
            return;
          }
          // conditional redirect (match legacy)
          if (result.redirect) {
            if (String(typeFlag) === '1') {
              window.location.href = "{{ route('admin.users.all') }}";
            } else {
              window.location.href = "{{ route('admin.users.index') }}";
            }
          } else {
            alert(result.msg || '{{ __("messages.general.success") }}');
            $btn.prop('disabled', false);
          }
        },
        error: function (xhr) {
          $btn.prop('disabled', false);
          if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            let s = '';
            for (const k in errors) { s += (errors[k]?.[0] || '') + '\n'; }
            alert(s || '{{ __("messages.general.error") }}');
          } else {
            alert('{{ __("messages.general.error") }}');
          }
        }
      });
    });
  });
</script>
@endpush
