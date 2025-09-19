@extends('layouts.master')
<!-- using this dashboard view -->
@section('content')
<!-- Page -->
<div class="flex grow">
    <!-- Header -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
            <!-- <a href="html/demo6.html">
                <img class="dark:hidden min-h-[30px]" src="assets/media/app/mini-logo-gray.svg"/>
                <img class="hidden min-h-[30px]" src="assets/media/app/mini-logo-gray-dark.svg"/>
            </a> -->
            <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
                <i class="ki-filled ki-menu"></i>
            </button>
        </div>
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
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">{{ __('home.dashboard') }}</h1>
                            </div>
                            <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                                @php
                                    $currentCarbonDate = \Carbon\Carbon::now();
                                    $monthOptions = [];
                                    for ($i = 0; $i <= 12; $i++) {
                                        $monthOptions[] = $currentCarbonDate->copy()->subMonths($i);
                                    }
                                @endphp
                                <div class="kt-menu kt-menu-default" data-kt-menu="true">
                                    <div class="kt-menu-item" data-kt-menu-item-offset="0, 0" data-kt-menu-item-placement="bottom-end" data-kt-menu-item-toggle="dropdown" data-kt-menu-item-trigger="hover">
                                        <div class="flex items-center flex-nowrap">
                                            <span class="flex items-center me-1">
                                                <i class="ki-filled ki-calendar text-base!"></i>
                                            </span>
                                            <span class="hidden md:inline text-nowrap">{{ $currentCarbonDate->format('F, Y') }}</span>
                                            <span class="inline md:hidden text-nowrap">{{ $currentCarbonDate->format('M y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Toolbar -->
                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="grid gap-5 lg:gap-7.5">
                            <!-- begin: grid -->
                            <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
                                <div class="lg:col-span-3 flex flex-col gap-5 lg:gap-7.5">
         <!-- Top section: Activation Code Generation -->
<div class="kt-card">
    <div class="kt-card-content p-5">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Dropdown for Activation Code Type -->
            <div class="grow w-full sm:w-auto">
                <select class="kt-btn kt-btn-outline w-full" name="assort_id" id="standardSelect" style="appearance: none; padding: 5px; font-weight: 500;">
                    <option value="">{{ __('home.select_code') }}</option>
                    @foreach($activationCodePresets ?? [] as $v)
                        <option value="{{ $v->assort_id }}"
                                data-money="{{ $v->money ?? 0 }}"
                                data-name="{{ $v->assorts->assort_name ?? '' }}"
                                data-duration="{{ $v->assorts->duration ?? 0 }}">
                            {{ $v->assorts->assort_name ?? '' }} {{ $v->money ?? 0 }} MYR
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Button that triggers the modal -->
            <button class="kt-btn kt-btn-primary w-full sm:w-auto whitespace-nowrap" type="button" data-kt-modal-toggle="#activationModal" id="submitBtn">
                {{ __('general.authorization_code') }}
            </button>
        </div>
    </div>
</div>

<!-- Modal for Authorization Code -->
<div class="kt-modal" id="activationModal" data-kt-modal="true">
    <div class="kt-modal-content max-w-md">
        <div class="kt-modal-header">
            <h3 class="kt-modal-title">{{ __('home.message') }}</h3>
            <button
                type="button"
                class="kt-modal-close"
                aria-label="Close modal"
                data-kt-modal-dismiss="#activationModal"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-x"
                    aria-hidden="true"
                >
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <div class="kt-modal-body">
            <form id="form">
                @csrf
                <input class="form-control" type="hidden" name="mini_money" value="" id="mini_money">
                <p class="text-center text-sm text-secondary-foreground" id="users">{{ __('home.membership_authorization_code') }}</p>
                <div class="flex justify-center items-center gap-3 mt-4 mb-4">
                    <h3 class="text-lg font-semibold" id="auth_code"></h3>
                    <button class="kt-btn kt-btn-primary" data-clipboard-text="" id="copy">
                        {{ __('home.copy') }}
                    </button>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-sm text-secondary-foreground" for="standardRemark">{{ __('home.remark') }}</label>
                    <textarea class="kt-btn kt-btn-outline w-full" id="standardRemark" rows="3" name="remark" maxlength="128"></textarea>
                </div>
            </form>
        </div>
        <div class="kt-modal-footer">
            <button class="kt-btn kt-btn-primary" data-kt-modal-dismiss="#activationModal" id="confirmBtn">{{ __('home.confirm') }}</button>
        </div>
    </div>
</div>
                                    <!-- Stats Section 1 -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 lg:gap-7.5">
                                        <!-- HOTCOIN Balance -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-user text-3xl text-purple-400"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold text-purple-400">{{ number_format($balance, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.huobi_balance') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Monthly Generated Quantity -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-chart-line-up text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($monthlyGeneratedCurrentMonth, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.month_code') }}</div>
                                                        <div class="text-xs text-muted-foreground">{{ __('home.month_code1') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Generated Quantity Last Month -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-send text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($generatedLastMonth, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.last_month_code') }}</div>
                                                        <div class="text-xs text-muted-foreground">{{ __('home.last_month_code1') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Generated Activation Code Total -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-archive text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($totalGeneratedQuantity, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.count_code') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Usage of HOTCOIN Last Month -->
                                    <div class="kt-card">
                                        <div class="kt-card-content p-5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-none">
                                                    <i class="ki-filled ki-wallet text-3xl text-primary"></i>
                                                </div>
                                                <div class="grow">
                                                    <div class="text-2xl font-semibold">{{ number_format($usageHotcoinLastMonth, 2) }}</div>
                                                    <div class="text-sm text-secondary-foreground">{{ __('home.last_month_huobi') }}</div>
                                                    <div class="text-xs text-muted-foreground">{{ __('home.last_month_huobi1') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Downline Agent Section -->
                                    <h3 class="text-lg font-semibold text-mono mt-2.5">{{ __('home.lower_agency') }}</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 lg:gap-7.5">
                                        <!-- This Month Profit -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-dollar text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($thisMonthProfit, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.month_lower_profit') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Last Month Profit -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-arrow-up-down text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($lastMonthProfit, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.last_month_profit') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Profit -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-crown text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($totalProfit, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.sum_profit') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Members -->
                                        <div class="kt-card">
                                            <div class="kt-card-content p-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-none">
                                                        <i class="ki-filled ki-people text-3xl text-primary"></i>
                                                    </div>
                                                    <div class="grow">
                                                        <div class="text-2xl font-semibold">{{ number_format($totalMembers, 2) }}</div>
                                                        <div class="text-sm text-secondary-foreground">{{ __('home.count_agency') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end: grid -->
                        </div>
                    </div>
                    <!-- End of Container -->
                </main>
                <!-- Footer -->
                <footer class="footer">
                    <div class="kt-container-fixed">
                        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5">
                            <div class="flex order-2 md:order-1 gap-2 font-normal text-sm">
                                <span class="text-muted-foreground">2025©</span>
                                <a class="text-secondary-foreground hover:text-primary" href="{{ config('app.url') }}">WOW TV</a>
                            </div>
                            <nav class="flex order-1 md:order-2 gap-4 font-normal text-sm text-secondary-foreground">
                                <!-- <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs">Docs</a>
                                <a class="hover:text-primary" href="https://1.envato.market/Vm7VRE">Purchase</a>
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license">FAQ</a>
                                <a class="hover:text-primary" href="https://devs.keenthemes.com">Support</a>
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license">License</a> -->
                            </nav>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('Authorization Code Script: TOP OF SCRIPT BLOCK EXECUTED.');
    console.log('Authorization Code Script: Script started.');
    console.log('Authorization Code Script: DOMContentLoaded');

    // Check dependencies
    if (typeof jQuery === 'undefined') {
        console.error('Authorization Code Script: jQuery is not loaded');
        alert('jQuery is required but not loaded.');
        return;
    }
    if (typeof ClipboardJS === 'undefined') {
        console.error('Authorization Code Script: ClipboardJS is not loaded');
    }

    var $ = jQuery;
    var csrf_token = $('meta[name="csrf-token"]').attr('content') || '{{csrf_token()}}';
    console.log('Authorization Code Script: CSRF Token:', csrf_token);

    // Modal toggle functions
    

    // Button click handler for generating code
    var submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        console.log('Authorization Code Script: submitBtn found');
        submitBtn.addEventListener('click', function() {
                console.log('Authorization Code Script: Authorization Code button clicked');
                
                var mini_money = $("#standardSelect").find("option:selected").attr("data-money");
                var iteValue = $("#standardSelect").find("option:selected").attr("data-name");
                var duration = $("#standardSelect").find("option:selected").attr("data-duration");
                var assort_id = $("#standardSelect").find("option:selected").val();
                
                console.log('Authorization Code Script: Selected values:', {mini_money, iteValue, duration, assort_id});
                
                $("#users").html(iteValue ? iteValue + " {{ __('home.authorization_code') }}" : "{{ __('home.membership_authorization_code') }}");
                
                if (typeof(iteValue) === "undefined" || !assort_id) {
                    alert("{{ __('home.select_code_type') }}");
                    return false;
                }

                // KTUI will show the modal via data-kt-modal-toggle
                // showModal(); // Removed custom showModal call

                var url = '{{ route('admin.code.save') ?? '/admin/code/save' }}';
                console.log('Authorization Code Script: Sending AJAX to:', url);
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {day: duration, type: 1, number: 1, assort_id: assort_id, mini_money: mini_money, _token: csrf_token},
                    dataType: 'json',
                    headers: {'X-CSRF-Token': csrf_token},
                    success: function (result) {
                        console.log('Authorization Code Script: AJAX Success:', result);
                        if (result.code !== 0) {
                            alert(result.msg || "{{ __('home.failed_to_generate_code') }}");
                            // KTUI will hide the modal via data-kt-modal-dismiss
                            // hideModal(); // Removed custom hideModal call
                            return false;
                        }
                        $("#auth_code").html(result.data);
                        $("#copy").attr("data-clipboard-text", result.data);
                        window.code_id = result.id;
                    },
                    error: function (resp, stat, text) {
                        console.error('Authorization Code Script: AJAX Error:', resp.status, text, resp.responseText);
                        alert("{{ __('home.error_generating_code') }}");
                        // KTUI will hide the modal via data-kt-modal-dismiss
                        // hideModal(); // Removed custom hideModal call
                    }
                });
            });
    } else {
        console.error('Authorization Code Script: submitBtn not found');
    }

    

    // Confirm button function
        function authCode() {
            console.log('Authorization Code Script: Confirm button clicked');
            var remark = $("#standardRemark").val();
            var code = $("#auth_code").html();
            var url = '{{ route('admin.code.remark') ?? '/admin/code/remark' }}';
            
            console.log('Authorization Code Script: Sending remark AJAX to:', url, {remark, code});
            
            $.ajax({
                url: url,
                type: 'PUT',
                data: {remark: remark, code: code, _token: csrf_token},
                dataType: 'json',
                headers: {'X-CSRF-Token': csrf_token},
                success: function (result) {
                    console.log('Authorization Code Script: Remark AJAX Success:', result);
                    if (result.code !== 0) {
                        alert(result.msg || "{{ __('home.failed_to_save_remark') }}");
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('license.list') ?? '/admin/code' }}';
                    }
                    // KTUI will hide the modal via data-kt-modal-dismiss
                    // hideModal(); // Removed custom hideModal call
                },
                error: function (resp, stat, text) {
                    console.error('Authorization Code Script: Remark AJAX Error:', resp.status, text, resp.responseText);
                    // Simplified error messages
                    if (resp.status === 404) {
                        alert("{{ __('home.save_feature_not_found') }}");
                    } else if (resp.status === 422) {
                         alert("{{ __('home.invalid_data_provided') }}");
                    } else if (resp.status === 500) {
                        alert("{{ __('home.server_error') }}");
                    } else {
                        alert("{{ __('home.unknown_error') }}");
                    }
                }
            });
        }

        var confirmBtn = document.getElementById('confirmBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', authCode);
        } else {
            console.error('Authorization Code Script: confirmBtn not found');
        }

    // Clipboard functionality
    if (typeof ClipboardJS !== 'undefined') {
        var clipboard = new ClipboardJS('#copy');
        clipboard.on('success', function (e) {
            console.log('Authorization Code Script: Clipboard copy success:', e.text);
            alert("{{ __('home.code_copied_successfully') }}");
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Authorization Code Script: Clipboard copy error:', e.action, e.trigger);
            alert("{{ __('home.failed_to_copy_code') }}");
        });
    } else {
        console.error('Authorization Code Script: ClipboardJS not initialized');
    }
});
</script>
@endpush