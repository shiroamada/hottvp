@extends('layouts.master')

@section('content')
<!-- Page -->
<!-- Base -->
<div class="flex grow">
<!-- Header -->
<header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
<!-- Container -->
<div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
   
    <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
    <i class="ki-filled ki-menu">
    </i>
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
                    {{ __('messages.license_generate.title') }}
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
                    <h3 class="kt-card-title">{{ __('messages.license_generate.title') }}</h3>
                </div>
                <form action="{{ route('admin.code.save') }}" method="post" id="form" onsubmit="return false;">
{{ csrf_field() }}
<input class="kt-input" type="hidden" name="mini_money" value="" id="mini_money">

<div class="kt-card-content grid gap-5">
    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
        <label class="kt-form-label max-w-56">{{ __('messages.license_generate.type') }}</label>
        <select class="kt-select grow" id="standardSelect" name="assort_id">
            <option value="0">{{ __('messages.license_generate.choose_code_type') }}</option>
            @foreach($equipment ?? null as $v)
                <option value="{{ $v->assort_id }}"
                        emoney="{{ $v->money }}">{{ $v->assorts->assort_name }} {{ $v->money }} {{ trans('huobi.money') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
        <label class="kt-form-label max-w-56">{{ __('messages.license_generate.quantity') }}</label>
        <input class="kt-input grow" id="num" name="number" type="text" maxlength="3" autocomplete="off" />

    </div>

    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
        <label class="kt-form-label max-w-56">{{ __('messages.license_generate.remarks') }}</label>
        <textarea class="kt-input grow" id="standardRemark" name="remark" rows="3" maxlength="128"></textarea>
    </div>

    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
        <label class="kt-form-label max-w-56">{{ __('messages.license_generate.need_hotcoin') }}</label>
        <div class="grow">
            <input class="kt-input" type="text" name="huobi" value="0" readonly id="huobi">
        </div>
    </div>

    <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 justify-end">
        <div class="text-right">
            <p>{{ __('messages.license_generate.hotcoin_balance') }}</p>
            <p class="font-bold text-primary">{{ number_format(\Auth::guard('admin')->user()->balance, 2) }}</p>
        </div>
    </div>

    <div class="flex justify-center gap-3 mt-3">
        <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
            {{ __('messages.license_generate.batch_generate') }}
        </button>
        <button class="kt-btn kt-btn-danger" type="reset">{{ __('messages.license_generate.reset') }}</button>
        <button class="kt-btn kt-btn-warning" type="button" onclick="history.go(-1);">
            {{ __('messages.license_generate.return') }}
        </button>
    </div>
</div>
</form>

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
<!-- End of Base -->
<!-- End of Page -->
@endsection

@push('scripts')
@vite('resources/js/license-generator.js')
@endpush