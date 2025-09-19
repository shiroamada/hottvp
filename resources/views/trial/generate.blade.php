@extends('layouts.master')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Page -->
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
                                    {{ __('messages.trial_generate.title') }}
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
                                    <h3 class="kt-card-title">
                                        {{trans('authCode.apply_code')}}
                                    </h3>
                                </div>

                                <div class="kt-card-content grid gap-5">
                                    <form action="{{ route('admin.try.hold')}}" method="post" id="form" onsubmit="return false;">
                                        {{ csrf_field() }}
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{trans('authCode.av_number')}}</label>
                                            <div class="grow">
                                                <span class="kt-input" style="border-style:none" id="need">{{ \Auth::guard('admin')->user()->try_num }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{trans('authCode.generate_code')}}</label>
                                            <input class="kt-input grow" type="text" name="number"
                                                   value="" id="number" maxlength="3" onkeyup="onlyNumber(this)"
                                                   onblur="onlyNumber(this)" onmouseover="onlyNumber(this)">
                                        </div>
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mt-4">
                                            <label class="kt-form-label max-w-56">{{trans('authCode.remark')}}</label>
                                            <textarea class="kt-input grow" id="standardRemark" rows="3"
                                                      name="remark" maxlength="128"> </textarea>
                                        </div>
                                        <div class="flex justify-center gap-3 mt-3">
                                            <button class="kt-btn kt-btn-primary" type="submit"
                                                    id="submitBtn">{{trans('authCode.apply_code')}}</button>
                                            <div class="flex justify-end gap-3">
                                                <button type="reset" class="kt-btn kt-btn-secondary">{{trans('general.reset')}}</button>
                                                <button type="button" class="kt-btn kt-btn-warning"
                                                        onclick="history.go(-1);">{{trans('general.return')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
<!-- End of Page -->
<script>
    function onlyNumber(obj) {
        // Remove any character that is not a digit
        obj.value = obj.value.replace(/[^\d]/g, '');
        // Remove leading zeros unless the number is just '0'
        obj.value = obj.value.replace(/^0+(?=\d)/, '');
        // Ensure no leading decimal point (though not relevant for integers)
        obj.value = obj.value.replace(/^\./g, '');
    }

    jQuery(document).ready(function($) {
        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            console.log(method);
            console.log(action);
            $.ajax({
                type: method,
                url: action,
                data: $('#form').serializeArray(),
                success: function (result) {
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        // Using alert as layer.msg is not guaranteed to be available
                        alert(result.msg);
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin.try.list') }}';
                    }
                },
                error: function (resp, stat, text) {
                    if (window.form_submit) {
                        form_submit.prop('disabled', false);
                    }
                    // Using alert as layer.msg is not guaranteed to be available
                    if (resp.status === 422) {
                        var parse = $.parseJSON(resp.responseText);
                        if (parse) {
                            alert(parse.msg);
                        }
                        return false;
                    } else if (resp.status === 404) {
                        alert("{{trans('general.resources_not')}}");
                        return false;
                    } else if (resp.status === 401) {
                        alert("{{trans('general.login_first')}}");
                        return false;
                    } else if (resp.status === 429) {
                        alert("{{trans('general.Overvisiting')}}");
                        return false;
                    } else if (resp.status === 419) {
                        alert("{{trans('general.illegal_request')}}");
                        return false;
                    } else if (resp.status === 500) {
                        alert("{{trans('general.internal_error')}}");
                        return false;
                    } else {
                        var parse = $.parseJSON(resp.responseText);
                        if (parse) {
                            alert(parse.msg);
                        }
                        return false;
                    }
                }
            });

            return false;
        });
    });
</script>
@endsection