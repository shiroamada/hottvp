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
                    {{ __('adminUser.cost') }}
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

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{ __('adminUser.email') }}:</span>
                          <span class="font-medium">{{ $info['email'] }}</span>
                        </div>

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
<!-- 
                        @if(($info['is_new'] ?? 1) == 0)
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
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cost Management Table -->
              <div class="kt-card kt-card-grid">
                <div class="kt-card-content">
                  <div class="kt-scrollable-x-auto">
                    <form method="post" action="{{ route('admin.users.adjust') }}" id="form">
                      @csrf
                      <input class="kt-input" type="hidden" name="id" value="{{ $id ?? '' }}" id="agency_id">
                      <table class="kt-table kt-table-border table-auto">
                        <thead>
                          <tr>
                            <th class="text-start">{{ __('adminUser.assort') }}</th>
                            <th class="text-start">{{ __('equipment.retail_price') }}</th>
                            <th class="text-start">{{ __('adminUser.u_cost') }}</th>
                            <th class="text-start">{{ __('adminUser.a_cost') }}</th>
                            <th class="text-start">{{ __('adminUser.u_profit') }}</th>
                          </tr>
                        </thead>
                        <tbody id="list-content">
                          @isset($lists)
                            @foreach($lists as $k => $v)
                              <tr>
                                <td>{{ $v['assort'][$k] }}</td>
                                <td class="cost">{{ $v['cost'][$k] }}</td>
                                <td class="own">{{ $v['own'][$k] }}</td>
                                <td class="editable">
                                  <input class="kt-input w-24" type="text" name="agency" value="{{ $v['agency'][$k] }}"
                                         maxlength="8" id="defind" data-content-id="{{ $k }}"
                                         onkeyup="onlyNumber(this)">
                                </td>
                                <td class="profit">{{ $v['diff'][$k] }}</td>
                              </tr>
                            @endforeach
                          @endisset
                        </tbody>
                      </table>
                      <div class="mt-5 flex justify-center lg:justify-end gap-3">
                        <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                          {{ __('adminUser.cost') }}
                        </button>
                        <button type="button" class="kt-btn kt-btn-warning"
                                onclick="window.location='{{ route('admin.users.index') }}'">
                          {{ __('general.return') }}
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
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
  /**
   * Validate agent cost input
   */
  function onlyNumber(obj) {
    var t = obj.value.charAt(0);
    obj.value = obj.value.replace(/[^\d\.]/g, '');
    obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
    obj.value = obj.value.replace(/^\./g, '');
    obj.value = obj.value.replace(/\.{2,}/g, '.');
    obj.value = obj.value.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
    obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
    if (t == '-') return;
  }

  var agencyList = [];
  var ownList = [];
  var assortList = [];
  var priceList = [];

  $(document).on('blur', "#defind", function () {
    var agency = $(this).val();
    var id = $(this).attr('data-content-id');
    var own = $(this).parent().prev().html();
    var price = $(this).parent().prev().prev().html();
    var assort = $(this).parent().prev().prev().prev().html();

    if (id > 2) {
      if (accSub(price, own) < 2) {
        $(this).val(price - 1);
        var profit = accSub(price - 1, own);
        $(this).parent().next().html(profit);
      } else {
        if (Number(agency) < Number(own)) {
          toastr.error("{{ __('adminUser.agency_tips') }}");
          $(this).val(0);
          return false;
        } else if (Number(agency) >= Number(price)) {
          toastr.error("{{ __('equipment.gltPrice') }}");
          $(this).val(0);
          return false;
        } else if (Number(agency) - Number(own) < 1) {
          toastr.error("{{ __('equipment.gltZero') }}");
          $(this).val(0);
          return false;
        }
        var profit = accSub(agency, own);
        $(this).parent().next().html(profit);
      }
    } else {
      if (Number(agency) > Number(price)) {
        toastr.error("{{ __('equipment.gtPrice') }}");
        $(this).val(0);
        return false;
      } else if (Number(agency) < Number(own)) {
        toastr.error("{{ __('equipment.ltZero') }}");
        $(this).val(0);
        return false;
      }
    }
  });

  var token = $("input[name='_token']").val();
  $('#form').submit(function () {
    window.form_submit = $('#form').find('[type=submit]');
    form_submit.prop('disabled', true);
    ownList = [];
    assortList = [];
    agencyList = [];
    priceList = [];
    var agency_id = $("#agency_id").val();

    // Collect agency costs
    $(".editable > input").each(function (index) {
      agencyList.push(Number($(this).val()));
    });

    // Collect own costs
    $(".editable").prev().each(function () {
      ownList.push(Number($(this).text()));
    });

    // Collect retail prices
    $(".editable").prev().prev().each(function () {
      priceList.push(Number($(this).text()));
    });

    // Collect assort types
    $(".editable").prev().prev().prev().each(function () {
      assortList.push($(this).text());
    });

    var method = $("#form").attr("method");
    var action = $('#form').attr("action");
    $.ajax({
      type: method,
      url: action,
      data: {
        id: agency_id,
        agency: agencyList,
        own: ownList,
        price: priceList,
        assort: assortList
      },
      headers: {'X-CSRF-Token': token},
      success: function (result) {
        if (result.code !== 0) {
          form_submit.prop('disabled', false);
          toastr.error(result.msg);
          return;
        }
        toastr.success(result.msg);
        if (result.redirect) {
          setTimeout(function() {
            location.href = '{{ route('admin.users.index') }}';
          }, 1000);
        }
      },
      error: function (resp) {
        if (window.form_submit) {
          form_submit.prop('disabled', false);
        }
        const code = resp?.status;
        const t = {
          422: "{{ __('general.illegal_request') }}",
          404: "{{ __('general.resources_not') }}",
          401: "{{ __('general.login_first') }}",
          429: "{{ __('general.Overvisiting') }}",
          419: "{{ __('general.illegal_request') }}",
          500: "{{ __('general.internal_error') }}"
        }[code];
        if (t) { toastr.error(t); return; }
        try {
          const parse = $.parseJSON(resp.responseText);
          if (parse && parse.msg) {
            toastr.error(parse.msg);
          }
        } catch(e){}
      }
    });
    return false;
  });

  /**
   * Precise subtraction function
   */
  function accSub(arg1, arg2) {
    var r1, r2, m, n;
    try {
      r1 = arg1.toString().split(".")[1].length;
    } catch (e) {
      r1 = 0;
    }
    try {
      r2 = arg2.toString().split(".")[1].length;
    } catch (e) {
      r2 = 0;
    }
    m = Math.pow(10, Math.max(r1, r2));
    n = (r1 >= r2) ? r1 : r2;
    return ((arg1 * m - arg2 * m) / m).toFixed(n);
  }
</script>
@endpush
