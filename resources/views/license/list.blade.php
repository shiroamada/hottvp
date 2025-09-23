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
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ __('authCode.managers') }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('license.generate') }}" class="kt-btn kt-btn-primary">
                                    {{ __('authCode.newAuthCode') }}
                                </a>
                                <a href="{{ route('license.detail') }}" class="kt-btn kt-btn-outline kt-btn-secondary">
                                    {{ __('authCode.view_last_batch') }}
                                </a>
                                <a href="{{ route('license.down') }}" class="kt-btn kt-btn-outline kt-btn-secondary">
                                    {{ __('authCode.export_last_batch') }}
                                </a>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->
                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="kt-card mb-5">
                            <div class="kt-card-content">
                                <form action="{{ route('license.list') }}" method="GET" class="grid sm:grid-cols-2 md:grid-cols-4 gap-5">
                                    <div>
                                        <label for="auth_code" class="kt-form-label">{{ __('authCode.auth_code') }}</label>
                                        <input type="text" id="auth_code" name="auth_code" class="kt-input" placeholder="{{ __('authCode.enter_code') }}" value="{{ request('auth_code') }}">
                                    </div>
                                    <div>
                                        <label for="status" class="kt-form-label">{{ __('general.status') }}</label>
                                        <select id="status" name="status" class="kt-select">
                                            <option value="">{{ __('general.all') }}</option>
                                            <option value="0" @if(request('status') === '0') selected @endif>{{ __('authCode.status_unused') }}</option>
                                            <option value="1" @if(request('status') === '1') selected @endif>{{ __('authCode.status_have_used') }}</option>
                                            <option value="2" @if(request('status') === '2') selected @endif>{{ __('authCode.status_was_due') }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="assort_id" class="kt-form-label">{{ __('authCode.type') }}</label>
                                        <select id="assort_id" name="assort_id" class="kt-select">
                                            <option value="">{{ __('general.all') }}</option>
                                            @foreach($assort_list as $assort)
                                                <option value="{{ $assort->id }}" @if(request('assort_id') == $assort->id) selected @endif>{{ $assort->assort_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="date_range" class="kt-form-label">{{ __('general.date_range') }}</label>
                                        <input type="text" id="date_range" name="date2" class="kt-input" placeholder="{{ __('general.select_date_range') }}" value="{{ request('date2') }}">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end gap-3">
                                        <button type="submit" class="kt-btn kt-btn-primary">{{ __('general.search') }}</button>
                                        <a href="{{ route('license.export', request()->query()) }}" class="kt-btn kt-btn-outline kt-btn-primary">{{ __('general.export_excel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ __('authCode.generated_codes') }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('authCode.auth_code') }}</th>
                                                <th>{{ __('authCode.type') }}</th>
                                                <th>{{ __('authCode.remark') }}</th>
                                                <th>{{ __('general.status') }}</th>
                                                <th>{{ __('general.created_at') }}</th>
                                                <th>{{ __('authCode.expire_at') }}</th>
                                                <th>{{ __('general.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($lists as $code)
    <tr>
        <td>{{ $code->auth_code }}</td>
        <td>{{ $code->assort->assort_name ?? 'N/A' }}</td>
        <td>
            @if(mb_strlen($code->remark) > 10)
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <span class="cursor-pointer">
                        {{ mb_substr($code->remark, 0, 10) }}...
                    </span>
                    <div x-show="open"
                         x-transition
                         class="absolute z-10 w-64 p-2 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-gray-800 rounded-lg shadow-lg">
                        {{ $code->remark }}
                    </div>
                </div>
            @else
                {{ $code->remark }}
            @endif
        </td>
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
        <td>
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-light-primary update-remark-button"
                data-kt-modal-toggle="#kt_modal_update_remark"
                data-id="{{ $code->id }}"
                data-remark="{{ $code->remark }}">
                <i class="ki-filled ki-pencil"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center">{{ __('general.no_data_found') }}</td>
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

@foreach($lists as $list)
<!-- Modal for updating remark -->
<div class="kt-modal" id="modal-update-remark-{{ $list->id }}">
    <div class="kt-modal-dialog">
        <div class="kt-modal-content">
            <div class="kt-modal-header">
                <h3 class="kt-modal-title">{{ __('authCode.up_remark') }}</h3>
                <button class="kt-btn kt-btn-icon kt-btn-sm kt-btn-light-primary" data-kt-modal-dismiss="true">
                    <i class="ki-filled ki-cross"></i>
                </button>
            </div>
            <div class="kt-modal-body">
                <form id="update-remark-form-{{ $list->id }}" action="{{ route('license.update', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="remark-{{ $list->id }}" class="kt-form-label">{{ __('authCode.remark') }}</label>
                        <textarea id="remark-{{ $list->id }}" name="remark" class="kt-input" rows="4">{{ $list->remark }}</textarea>
                    </div>
                    <div class="kt-modal-footer">
                        <button type="button" class="kt-btn kt-btn-light" data-kt-modal-dismiss="true">{{ __('general.cancel') }}</button>
                        <button type="submit" class="kt-btn kt-btn-primary">{{ __('general.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Metronic Update Remark Modal -->
<div class="kt-modal fade" id="kt_modal_update_remark" tabindex="-1" data-title-template="{{ __('authCode.up_remark_for_code') }}">
    <div class="kt-modal-dialog modal-dialog-centered">
        <div class="kt-modal-content">
            <div class="kt-modal-header">
                <h5 class="kt-modal-title">{{ __('authCode.up_remark') }}</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-kt-modal-dismiss="true" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="kt-modal-body">
                <input type="hidden" id="modal_code_id_input">
                <div class="mb-3">
                    <label for="modal_remark_input" class="form-label">{{ __('authCode.new_remark') }}:</label>
                    <input type="text" class="form-control" id="modal_remark_input" maxlength="128">
                </div>
            </div>
            <div class="kt-modal-footer">
                <button type="button" class="btn btn-light" data-kt-modal-dismiss="true">{{ __('general.close') }}</button>
                <button type="button" class="btn btn-primary" id="modal_save_remark_button">{{ __('general.save') }}</button>
            </div>
        </div>
    </div>
</div>
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
                const form = document.querySelector('form[action="{{ route('license.list') }}"]');
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