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
                    <i class="ki-filled ki-level me-2"></i>
                    {{trans('adminUser.set_level')}}
                  </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                  <button type="button" class="kt-btn kt-btn-light"
                          onclick="history.go(-1);">
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
                          <span class="text-muted-foreground">{{trans('adminUser.name')}}:</span>
                          <span class="font-medium">{{ $info['name'] }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{trans('adminUser.email')}}:</span>
                          <span class="font-medium">{{ $info['email'] }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{trans('adminUser.phone')}}:</span>
                          <span class="font-medium">{{ $info['phone'] }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{trans('adminUser.level')}}:</span>
                          <span class="font-medium">{{ $info->levels->level_name }}</span>
                        </div>

                        <div class="flex gap-2">
                          <span class="text-muted-foreground">{{trans('adminUser.account')}}:</span>
                          <span class="font-medium">{{ $info['account'] }}</span>
                        </div>
                        <!-- @if($info['is_new'] == 0) //show password if not verified
                          <div class="flex gap-2">
                            <span class="text-muted-foreground">{{trans('adminUser.password')}}:</span>
                            <span class="font-medium">{{ $info['password'] }}</span>
                          </div>
                        @endif -->

                        <div class="md:col-span-2 flex gap-2">
                          <span class="text-muted-foreground">{{trans('adminUser.remark')}}:</span>
                          <span class="font-medium break-words">{{ $info['remark'] }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Card -->
              <div class="kt-card">
                <div class="kt-card-content">
                    <form method="put" action="{{ route('admin.users.update', ['id' => $info['id']]) }}" id="form" onsubmit="return false;">
                        @csrf
                        <div class="p-5 grid gap-5">
                            <div class="grid sm:grid-cols-12 gap-2 items-center">
                                <label class="sm:col-span-1 text-sm text-muted-foreground" for="standardSelect">{{trans('adminUser.level')}}</label>
                                <div class="sm:col-span-11">
                                    <select class="kt-input" id="standardSelect" name="level_id">
                                        <option value="0">{{trans('general.select')}}</option>
                                        @foreach($level ?? null as $v)
                                            @if($info['level_id'] == 5 && $info['person_num'] >= 10)
                                                <option value="{{ $v['id'] }}" emoney="{{ $v['level_name'] }}"
                                                        money="{{ $v['mini_amount'] }}"
                                                        @isset($info) @if($v['id'] == $info->level_id) selected @endif @endisset
                                                >{{ $v['level_name'] }}</option>
                                            @else
                                                @if($v['id'] != 4)
                                                    <option value="{{ $v['id'] }}" emoney="{{ $v['level_name'] }}"
                                                            money="{{ $v['mini_amount'] }}"
                                                            @isset($info) @if($v['id'] == $info->level_id) selected @endif @endisset
                                                    >{{ $v['level_name'] }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-12 gap-2">
                                <div class="sm:col-start-2 sm:col-span-11">
                                    <div class="kt-scrollable-x-auto">
                                        <table class="kt-table kt-table-border table-auto">
                                            <thead>
                                            <tr>
                                                <th>{{trans('adminUser.assort')}}</th>
                                                <th>{{trans('equipment.retail_price')}}</th>
                                                <th>{{trans('adminUser.u_cost')}}</th>
                                                <th>{{trans('adminUser.a_cost')}}</th>
                                                <th>{{trans('adminUser.u_profit')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="list-content">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-12 gap-2">
                                <div class="sm:col-start-2 sm:col-span-11">
                                    <span>{{trans('adminUser.use_huobi')}} <span
                                                style="color: white;">{{ number_format(\Auth::guard('admin')->user()->balance, 2)  }}</span></span>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-12 gap-2 items-center">
                                <label class="sm:col-span-1 text-sm text-muted-foreground" for="balance">{{trans('adminUser.recharge')}}</label>
                                <div class="sm:col-span-11">
                                    <input class="kt-input" type="text" name="balance" aria-label="Balance"
                                           aria-describedby="icon-addon1" value="" id="balance" onkeyup="onlyNumber(this)"
                                           onblur="onlyNumber(this)" onmouseover="onlyNumber(this)">
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-12 gap-2">
                                <div class="sm:col-start-2   sm:col-span-11">
                                    <span class="text-sm" id="choice"></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 border-t border-border flex justify-center sm:justify-start gap-2">
                            <button class="kt-btn kt-btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn">{{trans('adminUser.set_level')}}</button>
                            <button type="reset" class="kt-btn">{{trans('general.reset')}}</button>
                            <button type="button" class="kt-btn kt-btn-warning"
                                    onclick="history.go(-1);">{{trans('general.return')}}</button>
                        </div>
                    </form>
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
    function onlyNumber(obj) {
        var t = obj.value.charAt(0);
        obj.value = obj.value.replace(/[^\d\.]/g, '');
        obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
        obj.value = obj.value.replace(/^\./g, '');
        obj.value = obj.value.replace(/\.{2,}/g, '.');
        obj.value = obj.value.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
        obj.value = obj.value.replace(/^(?:-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
        var a = $('#balance').val();
        var b = TripartiteMethod(a);
        $('#balance').val(b);
        if (t == '-') {
            return;
        }
    }

    function TripartiteMethod(num) {
        var type = true;
        var value = '';
        num = num.replace(/,/g, "");
        if (num.indexOf(".") < 0) {
            var t1 = num.toString().split('');
        } else {
            type = false;
            var arr = num.toString().split('.');
            var t1 = arr[0].toString().split('');
            var t2 = arr[1].toString();
        }

        var result = [],
            counter = 0;
        for (var i = t1.length - 1; i >= 0; i--) {
            counter++;
            result.unshift(t1[i]);
            if ((counter % 3) == 0 && i != 0) {
                result.unshift(',');
            }
        }

        if (type === true) {
            value = result.join('');
        } else {
            value = result.join('') + '.' + t2;
        }
        return value;
    }

    var token = $("input[name='_token']").val();

    $('#standardSelect').change(function () {
        var level_id = $(this).val();
        var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
        var money = $("#standardSelect").find("option:selected").attr("money");
        var str = (typeof(iteValue) == "undefined") ? "" :
            "{{trans('adminUser.tips1')}}" + iteValue + "{{trans('adminUser.tips2')}}" + money + "{{trans('adminUser.tips3')}}";
        $("#choice").html(str);
        var url = '{{ route('admin.users.info') }}';
        if (level_id > 0) {
            $.ajax({
                type: "GET",
                url: url,
                data: {level_id: level_id},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    // Check if the result is a JSON object with a 'code' property
                    if (typeof result === 'object' && result.hasOwnProperty('code')) {
                        if (result.code === 0) {
                            // JSON success
                            toastr.success(result.msg, null, {
                                onHidden: function() {
                                    if (result.reload || result.redirect) {
                                        location.reload();
                                    }
                                }
                            });
                        } else {
                            // JSON error
                            toastr.error(result.msg);
                        }
                    } else {
                        // Assumed to be an HTML response for the table
                        var tableRows = $(result).find('tbody').html();
                        $("#list-content").html(tableRows);
                    }
                },
                error: function (resp, stat, text) {
                    var message = "{{trans('general.internal_error')}}";
                    if (resp.responseJSON && resp.responseJSON.message) {
                        message = resp.responseJSON.message;
                    }
                    toastr.error(message);
                }
            });
        }
    });

    $('#form').submit(function () {
        window.form_submit = $('#form').find('[type=submit]');
        form_submit.prop('disabled', true);
        var method = $("#form").attr("method");
        var action = $('#form').attr("action");
        $.ajax({
            type: method,
            url: action,
            data: $('#form').serializeArray(),
            success: function (result) {
                if (result.code !== 0) {
                    form_submit.prop('disabled', false);
                    toastr.error(result.msg);
                    return false;
                }
                toastr.success(result.msg, null, {
                    onHidden: function() {
                        location.reload();
                    }
                });
            },
            error: function (resp, stat, text) {
                if (window.form_submit) {
                    form_submit.prop('disabled', false);
                }
                var message = "{{trans('general.internal_error')}}";
                if (resp.responseJSON && resp.responseJSON.message) {
                    message = resp.responseJSON.message;
                } else if (resp.responseJSON && resp.responseJSON.msg) {
                    message = resp.responseJSON.msg;
                }
                toastr.error(message);
            }
        });

        return false;
    });
</script>
@endpush