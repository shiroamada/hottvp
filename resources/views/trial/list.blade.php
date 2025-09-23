@extends('layouts.master')

@section('content')
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
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3 mb-5">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ __('authCode.try_managers') }}
                                </h1>
                            </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.try.records') }}" class="kt-btn kt-btn-info">
                                        {{__('authCode.access_records')}}
                                    </a>
                                    <a href="{{ route('admin.try.add') }}" class="kt-btn kt-btn-primary">
                                        {{ __('authCode.tryNewAuthCode') }}
                                    </a>
                                </div>
                            </div>
                            <!-- End of Container -->
                        </div>
                        <!-- End of Toolbar -->
                    <!-- Container -->
                    <div class="kt-container-fixed pb-5">
                        <div class="kt-card mb-5">
                            <div class="kt-card-content">
                                <form action="{{ route('admin.try.list') }}" method="GET" class="grid sm:grid-cols-2 md:grid-cols-3 gap-5" id="filter-form">
                                    <div class="kt-form-item">
                                        <label for="auth_code" class="kt-form-label">{{ __('authCode.try_code') }}</label>
                                        <input type="text" id="auth_code" name="auth_code" class="kt-input" placeholder="{{ __('authCode.enter_code') }}" value="{{ request('auth_code') }}">
                                    </div>
                                    <div class="kt-form-item">
                                        <label for="status" class="kt-form-label">{{ __('general.status') }}</label>
                                        <select id="status" name="status" class="kt-select">
                                            <option value="">{{ __('general.all') }}</option>
                                            <option value="0" @if(request('status') === '0') selected @endif>{{ __('authCode.status_unused') }}</option>
                                            <option value="1" @if(request('status') === '1') selected @endif>{{ __('authCode.status_have_used') }}</option>
                                            <option value="2" @if(request('status') === '2') selected @endif>{{ __('authCode.status_was_due') }}</option>
                                        </select>
                                    </div>
                                    <div class="kt-form-item">
                                        <label for="date_range" class="kt-form-label">{{ __('general.date_range') }}</label>
                                        <input type="text" id="date_range" name="created_at" class="kt-input" placeholder="{{ __('general.select_date_range') }}" value="{{ request('created_at') }}">
                                    </div>
                                    <div class="md:col-span-3 flex justify-end gap-3">
                                        <button type="submit" class="kt-btn kt-btn-primary">{{ __('general.search') }}</button>
                                        <a href="{{ route('admin.try.export') }}" id="export-btn" class="kt-btn kt-btn-outline kt-btn-primary">{{ __('general.export_excel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ __('authCode.generated_trial_codes') }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('authCode.try_code') }}</th>
                                                <th>{{ __('authCode.remark') }}</th>
                                                <th>{{ __('general.status') }}</th>
                                                <th>{{ __('general.created_at') }}</th>
                                                <th>{{ __('authCode.expire_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lists as $code)
                                            <tr>
                                                <td>{{ $code->auth_code }}</td>
                                                <td>{{ $code->remark }}</td>
                                                <td>
                                                    @if($code->status == 0)
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">{{ __('authCode.status_unused') }}</span>
                                                    @elseif($code->status == 1)
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">{{ __('authCode.status_have_used') }}</span>
                                                    @else
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-danger">{{ __('authCode.status_was_due') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $code->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $code->expire_at ? \Carbon\Carbon::parse($code->expire_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">{{ __('general.no_data_found') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-5">
                                    {{ $lists->links() }}
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

        const exportBtn = document.getElementById('export-btn');
        if(exportBtn) {
            exportBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                // remove empty params
                for (let p of params) {
                    if (!p[1]) {
                        params.delete(p[0]);
                    }
                }
                window.location.href = this.href + '?' + params.toString();
            });
        }
    });
</script>
@endpush
