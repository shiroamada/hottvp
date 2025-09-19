@extends('admin.layouts.master')

@section('content')
<!-- Page -->
<div class="flex grow">
<header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
            
            <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
                <i class="ki-filled ki-menu"></i>
            </button>
        </div>
    </header>
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
                  @if(isset($id))
                    {{ __('adminUser.editAdministrator') }}
                  @else
                    {{ __('adminUser.newAdministrator') }}
                  @endif
                </h1>
              </div>
            </div>
            <!-- End of Container -->
          </div>
          <!-- End of Toolbar -->

          <!-- Container -->
          <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5 xl:w-[38.75rem] mx-auto">
              <div class="kt-card kt-card-grid">
                <div class="kt-card-header">
                  <h3 class="kt-card-title">
                    @if(isset($id))
                      {{ __('adminUser.edit_form_title') }}
                    @else
                      {{ __('adminUser.create_form_title') }}
                    @endif
                  </h3>
                </div>

                <div class="kt-card-content">
                  <form method="post"
                        action="{{ isset($id) ? route('admin.users.update', $id) : route('admin.users.save') }}"
                        id="form"
                        onsubmit="return false;">
                    <div class="kt-card-content grid gap-5 m-3">
                      @csrf
                      @if(isset($id))
                        @method('PUT')
                      @endif

                      <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56">{{ __('adminUser.name') }}</label>
                        <input class="kt-input grow"
                               placeholder="{{ __('adminUser.agent_name_placeholder') }}"
                               type="text"
                               name="name"
                               value="{{ $user->name ?? '' }}"
                               maxlength="20"
                               id="agency_name">
                      </div>

                      @if(auth()->guard('admin')->user()->id == 1)
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                          <label class="kt-form-label max-w-56">{{ __('adminUser.channel') }}</label>
                          <select class="kt-select grow" id="channel_id" name="channel_id">
                            <option value="0">{{ __('general.select') }}</option>
                            @foreach($channels ?? [] as $v)
                              <option value="{{ $v['channel_id'] }}"
                                {{ isset($user) && $user->channel_id == $v['channel_id'] ? 'selected' : '' }}>
                                {{ $v['channel_name'] }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      @endif

                      <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                        <label class="kt-form-label max-w-56">{{ __('adminUser.level') }}</label>
                        <select class="kt-select grow" id="level_id" name="level_id">
                          <option value="0">{{ __('adminUser.select_level') }}</option>
                          @foreach($level ?? [] as $v)
                            @if(($v['id'] ?? null) != 4)
                              <option value="{{ $v['id'] }}"
                                      data-level-name="{{ $v['level_name'] ?? '' }}"
                                      data-min-amount="{{ $v['min_amount'] ?? $v['mini_amount'] ?? 0 }}"
                                      {{ isset($user) && $user->level_id == $v['id'] ? 'selected' : '' }}>
                                {{ $v['level_name'] ?? '' }}
                              </option>
                            @endif
                          @endforeach
                        </select>
                      </div>

                      <!-- Agent costs table will be loaded here -->
                      <div id="costs-container" class="hidden"></div>

                      @if(auth()->guard('admin')->user()->id == 1)
                        <!-- Entry barriers for super admin -->
                        <div>
                          <label class="kt-form-label block font-medium mb-2">{{ __('adminUser.barriers') }}</label>
                          <div class="kt-table-responsive">
                            <table class="kt-table">
                              <thead>
                                <tr>
                                  <th>{{ __('adminUser.gold_threshold') }}</th>
                                  <th>{{ __('adminUser.silver_threshold') }}</th>
                                  <th>{{ __('adminUser.bronze_threshold') }}</th>
                                  <th>{{ __('adminUser.custom_threshold') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <input class="kt-input w-full" type="text" name="barriersList[0]" value=""
                                           maxlength="6" onkeyup="onlyNumber(this, 1)">
                                  </td>
                                  <td>
                                    <input class="kt-input w-full" type="text" name="barriersList[1]" value=""
                                           maxlength="6" onkeyup="onlyNumber(this, 1)">
                                  </td>
                                  <td>
                                    <input class="kt-input w-full" type="text" name="barriersList[2]" value=""
                                           maxlength="6" onkeyup="onlyNumber(this, 1)">
                                  </td>
                                  <td>
                                    <input class="kt-input w-full" type="text" name="barriersList[3]" value=""
                                           maxlength="6" onkeyup="onlyNumber(this, 1)">
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      @endif

                      <div>
                        <label class="kt-form-label block font-medium mb-2">{{ __('adminUser.remark') }}</label>
                        <textarea class="kt-textarea grow" rows="3" name="remark" maxlength="128">{{ $user->remark ?? '' }}</textarea>
                      </div>

                      <div>
                        <label class="kt-form-label block font-medium mb-2">{{ __('adminUser.recharge') }}</label>
                        <input class="kt-input" type="text" name="balance"
                               value="{{ $user->balance ?? '' }}"
                               id="balance" onkeyup="onlyNumber(this, 2)" maxlength="8">
                        <p class="text-sm text-muted-foreground mt-2">
                          {{ __('adminUser.use_huobi') }}:
                          <span class="font-bold text-primary">{{ number_format(auth()->guard('admin')->user()->balance, 2) }}</span>
                        </p>
                        <p class="text-sm text-muted-foreground mt-1" id="need"></p>
                      </div>

                      @if(auth()->guard('admin')->user()->id == 1 ||
                          (auth()->guard('admin')->user()->level_id == 3 && auth()->guard('admin')->user()->type == 2))
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                          <label class="kt-form-label max-w-56">{{ __('adminUser.type') }}</label>
                          <div class="flex gap-5 grow">
                            <div class="flex items-center gap-2">
                              <input class="kt-radio" type="radio" name="type" value="1"
                                     {{ isset($user) && $user->type == 1 ? 'checked' : '' }}>
                              <label for="permission_normal">{{ __('adminUser.general_type') }}</label>
                            </div>
                            <div class="flex items-center gap-2">
                              <input class="kt-radio" type="radio" name="type" value="2"
                                     {{ !isset($user) || (isset($user) && $user->type == 2) ? 'checked' : '' }}>
                              <label for="permission_enhanced">{{ __('adminUser.enhance_type') }}</label>
                            </div>
                          </div>
                        </div>
                      @endif

                      <div class="flex justify-end gap-3">
                        <button type="button" class="kt-btn kt-btn-outline" onclick="history.go(-1);">
                          {{ __('general.return') }}
                        </button>
                        <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                          @if(isset($id))
                            {{ __('adminUser.editAdministrator') }}
                          @else
                            {{ __('adminUser.newAdministrator') }}
                          @endif
                        </button>
                      </div>
                    </div> <!-- /grid -->
                  </form>
                </div> <!-- /content -->
              </div> <!-- /card -->
            </div> <!-- /grid -->
          </div> <!-- /container -->

        </main>
      </div>
    </div>
    <!-- End of Main -->
  </div>
  <!-- End of Wrapper -->
</div>
<!-- End of Page -->
@endsection

@push('scripts')
<script>
window.addEventListener('load', function () {
  if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
    return;
  }

  jQuery(function($) {

    function readSelectedLevelMeta() {
      const $opt = $('#level_id option:selected');
      let minAmount = $opt.data('min-amount');
      if (typeof minAmount === 'undefined') {
        // legacy fallback if backend still uses mini_amount -> exposed as data-mini-amount
        minAmount = $opt.data('mini-amount');
      }
      const levelName = $opt.data('level-name') || '';
      return { minAmount, levelName };
    }

    function setMinAmountText(meta) {
      if (meta.levelName && (meta.minAmount || meta.minAmount === 0)) {
        // Translation should include placeholders :amount and :level
        const tmpl = @json(__('adminUser.min_amount_message')); // e.g. ":level minimum recharge amount: :amount"
        const html = String(tmpl)
          .replace(':amount', meta.minAmount)
          .replace(':level', meta.levelName);
        $('#need').html(html);
      } else {
        $('#need').empty();
      }
    }

    function loadCosts(levelId) {
      if (!levelId || Number(levelId) <= 0) {
        $('#costs-container').addClass('hidden').empty();
        $('#need').empty();
        return;
      }

      $.ajax({
        url: "{{ route('admin.users.info') }}",
        type: "GET",                 // Change to "POST" if your route expects it
        data: { level_id: levelId }, // Change key to { id: levelId } if controller expects 'id'
        dataType: "html",
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
          $('#costs-container').removeClass('hidden').html(response);
          setMinAmountText(readSelectedLevelMeta());
        },
        error: function (xhr) {
          console.error('Level info load failed', { status: xhr.status, resp: xhr.responseText });
          let msg = "{{ __('general.error') }}";
          if (xhr.status === 419) msg = "Session expired (419). Please refresh and try again.";
          else if (xhr.status === 403) msg = "Forbidden (403). You may lack permission.";
          else if (xhr.status === 404) msg = "Level info route not found (404).";
          else if (xhr.status >= 500) msg = "Server error while loading level info.";
          alert(msg);
          $('#costs-container').addClass('hidden').empty();
          $('#need').empty();
        }
      });
    }

    // Bind change to Level selector
    $('#level_id').on('change', function () {
      loadCosts($(this).val());
    });

    // Auto-load if editing (preselected level)
    const initialVal = $('#level_id').val();
    if (initialVal && Number(initialVal) > 0) {
      loadCosts(initialVal);
    }

    // Submit handler
    $('#form').on('submit', function (e) {
      e.preventDefault();
      $('#submitBtn').prop('disabled', true);

      // Normalize balance (remove thousands separators)
      const balance = $('#balance').val().replace(/,/g, '');
      $('#balance').val(balance);

      $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (result) {
          if (result.code !== 0) {
            alert(result.msg);
            $('#submitBtn').prop('disabled', false);
            return;
          }
          alert(result.msg);
          if (result.redirect) {
            // FIX: concatenate query string correctly
            location.href = "{{ route('admin.users.detail') }}?id=" + result.id;
          } else {
            $('#submitBtn').prop('disabled', false);
          }
        },
        error: function (xhr) {
          $('#submitBtn').prop('disabled', false);
          if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors || {};
            let errorMessage = '';
            for (const key in errors) {
              if (errors[key]?.length) errorMessage += errors[key][0] + '\n';
            }
            alert(errorMessage || 'Validation error.');
          } else {
            alert("{{ __('general.error') }}");
          }
        }
      });
    });

    // keep your number-only helper
    window.onlyNumber = function(obj, type) {
      let value = jQuery(obj).val().replace(/[^\d.]/g, '');
      if (type === 1) {
        value = value.replace(/\./g, '');
      } else if (type === 2) {
        const parts = value.split('.');
        if (parts.length > 2) {
          value = parts[0] + '.' + parts.slice(1).join('');
        }
        if (parts.length === 2 && parts[1].length > 2) {
          value = parts[0] + '.' + parts[1].substring(0, 2);
        }
        if (parts[0].length > 3) {
          parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
          value = parts.length === 2 ? parts[0] + '.' + parts[1] : parts[0];
        }
      }
      jQuery(obj).val(value);
    };

    // Live profit calc for editable agent costs
    $('#costs-container').on('keyup', '.agent-cost-input', function() {
      const agentCost = parseFloat($(this).val().replace(/,/g, '')) || 0;
      const yourCost = parseFloat($(this).closest('tr').find('.your-cost').text().replace(/,/g, '')) || 0;
      const profit = agentCost - yourCost;
      $(this).closest('tr').find('.your-profit').text(profit.toFixed(2));
    });

  });
});
</script>
@endpush
