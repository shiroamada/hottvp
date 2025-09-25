@extends('layouts.master')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
    .is-invalid {
        border: 1px solid #dc3545 !important; /* Red border only */
    }
    .text-danger {
        color: #dc3545 !important; /* Error text red */
    }
</style>

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
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ __('messages.trial_generate.title') }}
                                </h1>
                            </div>
                        </div>
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
                                                <span class="kt-input" style="border-style:none" id="need">
                                                    {{ \Auth::guard('admin')->user()->try_num }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Quantity input -->
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{trans('authCode.generate_code')}}</label>
                                            <div class="grow">
                                                <input class="kt-input grow" type="text" name="number"
                                                       value="" id="number" maxlength="3"
                                                       onkeyup="onlyNumber(this)" onblur="onlyNumber(this)">
                                                <p id="number-error" class="text-danger text-xs mt-1 hidden"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mt-4">
                                            <label class="kt-form-label max-w-56">{{trans('authCode.remark')}}</label>
                                            <textarea class="kt-textarea grow" id="standardRemark" rows="3"
                                                      name="remark" maxlength="128"></textarea>
                                        </div>

                                        <div class="flex justify-center gap-3 mt-3">
                                            <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                                                {{trans('authCode.apply_code')}}
                                            </button>
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
    </div>
</div>

<script>
    function onlyNumber(obj) {
        obj.value = obj.value.replace(/[^\d]/g, '');
        obj.value = obj.value.replace(/^0+(?=\d)/, '');
        obj.value = obj.value.replace(/^\./g, '');
    }

    jQuery(document).ready(function($) {
        $('#form').submit(function () {
            var numberInput = $('#number');
            var numberError = $('#number-error');
            var value = numberInput.val().trim();

            // Reset validation state
            numberInput.removeClass('is-invalid');
            numberError.text('').addClass('hidden');

            // Validate quantity
            if (value === '' || value === '0') {
                numberInput.addClass('is-invalid');
                numberError.text("{{ trans('messages.license_generate.quantity_required') ?? 'Quantity cannot be 0 or empty.' }}")
                           .removeClass('hidden');
                return false;
            }

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
                        numberInput.addClass('is-invalid');
                        numberError.text(result.msg).removeClass('hidden');
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin.try.list') }}';
                    }
                },
                error: function (resp) {
                    if (window.form_submit) {
                        form_submit.prop('disabled', false);
                    }
                    let parse;
                    try { parse = $.parseJSON(resp.responseText); } catch (e) {}

                    if (resp.status === 422 && parse && parse.errors && parse.errors.number) {
                        numberInput.addClass('is-invalid');
                        numberError.text(parse.errors.number[0]).removeClass('hidden');
                    } else if (parse && parse.msg) {
                        numberInput.addClass('is-invalid');
                        numberError.text(parse.msg).removeClass('hidden');
                    } else {
                        alert("{{trans('general.internal_error')}}");
                    }
                }
            });

            return false;
        });
    });
</script>
@endsection
