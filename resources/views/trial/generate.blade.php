@extends('layouts.master')

@section('content')
<!-- Page -->
<div class="flex grow">
    <!-- Header -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
        <!-- Container -->
        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
            <a href="#">
                <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg" />
                <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg" />
            </a>
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
                                    <h3 class="kt-card-title">{{ __('messages.trial_generate.title') }}</h3>
                                </div>
                                <form method="POST" action="{{ route('trial.store') }}">
                                    @csrf
                                    <div class="kt-card-content grid gap-5">
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{ __('messages.trial_generate.available_quantity') }}</label>
                                            <div class="grow">
                                                <input class="kt-input" type="text" value="{{ $availableTrialCodes }}" readonly>
                                            </div>
                                        </div>
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{ __('messages.trial_generate.generation_quantity') }}</label>
                                            <input class="kt-input grow" name="number" type="number" value="1" min="1">
                                        </div>
                                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                            <label class="kt-form-label max-w-56">{{ __('messages.trial_generate.remarks') }}</label>
                                            <textarea class="kt-input grow" name="remark" rows="3"></textarea>
                                        </div>
                                        <div class="flex justify-center gap-3 mt-3">
                                            <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.trial_generate.apply_for_trial_code') }}</button>
                                            <button class="kt-btn kt-btn-danger" type="reset">{{ __('messages.trial_generate.reset') }}</button>
                                            <a href="{{ route('trial.list') }}" class="kt-btn kt-btn-warning">{{ __('messages.trial_generate.return') }}</a>
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
<!-- End of Page -->
@endsection