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
                                    {{ __('messages.license_detail.title') }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('license.down') }}" class="kt-btn kt-btn-primary">
                                    {{ __('messages.license_detail.download_batch') }}
                                </a>
                                <a href="{{ route('license.list') }}" class="kt-btn kt-btn-outline kt-btn-primary">
                                    {{ __('messages.license_detail.back_to_list') }}
                                </a>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->
                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ __('messages.license_detail.last_batch_codes') }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.license_list.code') }}</th>
                                                <th>{{ __('messages.license_list.type') }}</th>
                                                <th>{{ __('messages.license_list.remark') }}</th>
                                                <th>{{ __('messages.license_list.status') }}</th>
                                                <th>{{ __('messages.license_list.created_at') }}</th>
                                                <th>{{ __('messages.license_list.expired_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($codes as $code)
                                            <tr>
                                                <td>{{ $code->auth_code }}</td>
                                                <td>{{ $code->assort->assort_name ?? 'N/A' }}</td>
                                                <td>{{ $code->remark }}</td>
                                                <td>
                                                    @if($code->status == 0)
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">{{ __('messages.license_list.status_unused') }}</span>
                                                    @elseif($code->status == 1)
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">{{ __('messages.license_list.status_used') }}</span>
                                                    @else
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-danger">{{ __('messages.license_list.status_expired') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $code->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $code->expire_at ? \Carbon\Carbon::parse($code->expire_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('messages.license_list.no_codes_found') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
@endsection
