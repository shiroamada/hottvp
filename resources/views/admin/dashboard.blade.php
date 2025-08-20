@extends('admin.layouts.master')

@section('content')
<!-- Page -->
<div class="flex grow">
    <!-- Header -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
            <a href="html/demo6.html">
                <img class="dark:hidden min-h-[30px]" src="assets/media/app/mini-logo-gray.svg"/>
                <img class="hidden min-h-[30px]" src="assets/media/app/mini-logo-gray-dark.svg"/>
            </a>
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
                                <h1 class="font-medium text-lg text-mono">Dashboard</h1>
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
                                        <button class="kt-menu-toggle kt-btn kt-btn-outline flex-nowrap">
                                            <span class="flex items-center me-1">
                                                <i class="ki-filled ki-calendar text-base!"></i>
                                            </span>
                                            <span class="hidden md:inline text-nowrap">{{ $currentCarbonDate->format('F, Y') }}</span>
                                            <span class="inline md:hidden text-nowrap">{{ $currentCarbonDate->format('M y') }}</span>
                                            <span class="flex items-center lg:ms-4">
                                                <i class="ki-filled ki-down text-xs!"></i>
                                            </span>
                                        </button>
                                        <div class="kt-menu-dropdown w-48 py-2 kt-scrollable-y max-h-[250px]">
                                            @foreach ($monthOptions as $month)
                                                <div class="kt-menu-item{{ $month->isSameMonth($currentCarbonDate) ? ' active' : '' }}">
                                                    <a class="kt-menu-link" href="#" data-month="{{ $month->format('Y-m') }}">
                                                        <span class="kt-menu-title">{{ $month->format('F, Y') }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
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
                    <option value="">Select Code</option>
                    @foreach($activationCodePresets ?? [] as $v)
                        <option value="{{ $v->assort_id }}"
                                data-money="{{ $v->money ?? 0 }}"
                                data-name="{{ $v->assorts->assort_name ?? '' }}"
                                data-duration="{{ $v->assorts->duration ?? 0 }}">
                            {{ $v->assorts->assort_name ?? '' }} {{ $v->money ?? 0 }} USD
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Button that triggers the modal -->
            <button class="kt-btn kt-btn-primary w-full sm:w-auto whitespace-nowrap" type="button" id="submitBtn">
                Authorization Code
            </button>
        </div>
    </div>
</div>

<!-- Modal for Authorization Code -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" id="activationModal">
    <div class="kt-card bg-background rounded-xl w-full max-w-md p-5">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold text-mono">Message</h5>
            <button class="kt-btn kt-btn-icon kt-btn-ghost" id="closeModal">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
        <div class="kt-card-content">
            <form id="form">
                @csrf
                <input class="form-control" type="hidden" name="mini_money" value="" id="mini_money">
                <p class="text-center text-sm text-secondary-foreground" id="users">Membership Authorization Code</p>
                <div class="flex justify-center items-center gap-3 mt-4 mb-4">
                    <h3 class="text-lg font-semibold" id="auth_code"></h3>
                    <button class="kt-btn kt-btn-primary" data-clipboard-text="" id="copy">
                        Copy
                    </button>
                </div>
                <div class="flex flex-col gap-3">
                    <label class="text-sm text-secondary-foreground" for="standardRemark">Remark</label>
                    <textarea class="kt-btn kt-btn-outline w-full" id="standardRemark" rows="3" name="remark" maxlength="128"></textarea>
                </div>
            </form>
        </div>
        <div class="flex justify-center mt-4">
            <button class="kt-btn kt-btn-primary" id="confirmBtn">Confirm</button>
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
                                                        <div class="text-sm text-secondary-foreground">HOTCOIN Balance</div>
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
                                                        <div class="text-sm text-secondary-foreground">Monthly Generated Quantity</div>
                                                        <div class="text-xs text-muted-foreground">No. Activation Code (This Month)</div>
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
                                                        <div class="text-sm text-secondary-foreground">Generated Quantity</div>
                                                        <div class="text-xs text-muted-foreground">Last Month Activation Code</div>
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
                                                        <div class="text-sm text-secondary-foreground">Generated Activation Code Total Quantity</div>
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
                                                    <div class="text-sm text-secondary-foreground">Usage of HOTCOIN</div>
                                                    <div class="text-xs text-muted-foreground">Last Month</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Downline Agent Section -->
                                    <h3 class="text-lg font-semibold text-mono mt-2.5">Downline Agent</h3>
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
                                                        <div class="text-sm text-secondary-foreground">This Month Profit</div>
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
                                                        <div class="text-sm text-secondary-foreground">Last Month Profit</div>
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
                                                        <div class="text-sm text-secondary-foreground">Total Profit</div>
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
                                                        <div class="text-sm text-secondary-foreground">Total Members</div>
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
                                <a class="text-secondary-foreground hover:text-primary" href="https://keenthemes.com">HotTV+ Inc.</a>
                            </div>
                            <nav class="flex order-1 md:order-2 gap-4 font-normal text-sm text-secondary-foreground">
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs">Docs</a>
                                <a class="hover:text-primary" href="https://1.envato.market/Vm7VRE">Purchase</a>
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license">FAQ</a>
                                <a class="hover:text-primary" href="https://devs.keenthemes.com">Support</a>
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license">License</a>
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

@section('js')
<script>
    console.log('JavaScript loaded');

    // Check dependencies
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
        alert('jQuery is required but not loaded.');
    }
    if (typeof layui === 'undefined') {
        console.error('Layui is not loaded');
    }
    if (typeof ClipboardJS === 'undefined') {
        console.error('ClipboardJS is not loaded');
    }

    // Use jQuery directly
    var $ = window.jQuery;
    var layer = window.layui ? layui.layer : {
        msg: function(message, options) {
            console.log('Fallback alert:', message);
            alert(message);
        }
    };

    var csrf_token = $('meta[name=csrf-token]').eq(0).attr('content');
    console.log('CSRF Token:', csrf_token);

    var ajax_options = {
        headers: {'X-CSRF-Token': csrf_token},
        type: 'post',
        dataType: 'json',
        error: function (resp, stat, text) {
            console.error('AJAX Error:', resp.status, text, resp.responseText);
            if (window.form_submit && typeof form_submit.prop === 'function') {
                form_submit.prop('disabled', false);
            }
            if (resp.status === 422) {
                try {
                    var parse = JSON.parse(resp.responseText);
                    if (parse && parse.errors) {
                        var key = Object.keys(parse.errors)[0];
                        layer.msg(parse.errors[key][0], {shift: 6});
                    }
                } catch (e) {
                    console.error('Parse Error:', e);
                    layer.msg('Invalid response format', {shift: 6});
                }
            } else if (resp.status === 404) {
                layer.msg('Resource not found', {icon: 5});
            } else if (resp.status === 401) {
                layer.msg('Please login first', {shift: 6});
            } else if (resp.status === 429) {
                layer.msg('Too many requests, please try again later', {shift: 6});
            } else if (resp.status === 419) {
                layer.msg('Invalid request. Please refresh the page.', {shift: 6});
            } else if (resp.status === 500) {
                layer.msg('Internal server error, please contact administrator', {shift: 6});
            } else {
                try {
                    var parse = JSON.parse(resp.responseText);
                    if (parse && parse.msg) {
                        layer.msg(parse.msg, {shift: 6});
                    } else {
                        layer.msg('Unknown error, please contact administrator', {shift: 6});
                    }
                } catch (e) {
                    layer.msg('Unknown error, please contact administrator', {shift: 6});
                }
            }
        }
    };
    $.ajaxSetup(ajax_options);

    // Modal toggle functions
    function showModal() {
        console.log('Showing modal');
        var modal = document.getElementById('activationModal');
        if (modal) {
            modal.classList.remove('hidden');
        } else {
            console.error('Modal #activationModal not found');
        }
    }

    function hideModal() {
        console.log('Hiding modal');
        var modal = document.getElementById('activationModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Button click handler
    var submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        console.log('submitBtn found');
        submitBtn.addEventListener('click', function() {
            console.log('Authorization Code button clicked');
            showModal();
            var mini_money = $("#standardSelect").find("option:selected").attr("data-money");
            var iteValue = $("#standardSelect").find("option:selected").attr("data-name");
            var duration = $("#standardSelect").find("option:selected").attr("data-duration");
            var assort_id = $("#standardSelect").find("option:selected").val();
            console.log('Selected values:', {mini_money, iteValue, duration, assort_id});
            $("#users").html(iteValue ? iteValue + " Authorization Code" : "Membership Authorization Code");
            if (typeof(iteValue) === "undefined") {
                layer.msg("Please select a code type", {shift: 6});
                hideModal();
                return false;
            }

            var url = '{{ route('admin.code.save') ?? '/admin/code/save' }}';
            console.log('Sending AJAX to:', url);
            $.ajax({
                url: url,
                type: "POST",
                data: {day: duration, type: 1, number: 1, assort_id: assort_id, mini_money: mini_money},
                headers: {'X-CSRF-Token': csrf_token},
                success: function (result) {
                    console.log('AJAX Success:', result);
                    if (result.code !== 0) {
                        layer.msg(result.msg || 'Failed to generate code', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        hideModal();
                        return false;
                    }
                    $("#auth_code").html(result.data);
                    $("#copy").attr("data-clipboard-text", result.data);
                    window.code_id = result.id;
                },
                error: ajax_options.error
            });
        });
    } else {
        console.error('submitBtn not found');
    }

    // Close modal
    var closeModalBtn = document.getElementById('closeModal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', hideModal);
    } else {
        console.error('closeModal button not found');
    }

    // Confirm button
    var confirmBtn = document.getElementById('confirmBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            console.log('Confirm button clicked');
            var remark = $("#standardRemark").val();
            var code = $("#auth_code").html();
            var url = '{{ route('admin.code.remark') ?? '/admin/code/remark' }}';
            console.log('Sending remark AJAX to:', url, {remark, code});
            $.ajax({
                url: url,
                type: "PUT",
                data: {remark: remark, code: code},
                headers: {'X-CSRF-Token': csrf_token},
                success: function (result) {
                    console.log('Remark AJAX Success:', result);
                    if (result.code !== 0) {
                        layer.msg(result.msg || 'Failed to save remark', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin.code.index') ?? '/admin/code' }}';
                    }
                    hideModal();
                },
                error: ajax_options.error
            });
        });
    } else {
        console.error('confirmBtn not found');
    }

    // Clipboard functionality
    if (typeof ClipboardJS !== 'undefined') {
        var clipboard = new ClipboardJS('#copy');
        clipboard.on('success', function (e) {
            console.log('Clipboard copy success:', e.text);
            layer.msg("Code copied successfully", {shift: 5});
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Clipboard copy error:', e.action, e.trigger);
            layer.msg("Failed to copy code", {shift: 6});
        });
    } else {
        console.error('ClipboardJS not initialized');
    }
</script>
@endsection