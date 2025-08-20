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
                                    {{ __('messages.license_list.title') }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('license.generate') }}" class="kt-btn kt-btn-primary">
                                    {{ __('messages.license_list.generate_new') }}
                                </a>
                                <a href="{{ route('license.detail') }}" class="kt-btn kt-btn-outline kt-btn-secondary">
                                    {{ __('messages.license_list.view_last_batch') }}
                                </a>
                                <a href="{{ route('license.down') }}" class="kt-btn kt-btn-outline kt-btn-secondary">
                                    {{ __('messages.license_list.export_last_batch') }}
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
                                        <label for="auth_code" class="kt-form-label">Code</label>
                                        <input type="text" id="auth_code" name="auth_code" class="kt-input" placeholder="Enter code" value="{{ request('auth_code') }}">
                                    </div>
                                    <div>
                                        <label for="status" class="kt-form-label">Status</label>
                                        <select id="status" name="status" class="kt-select">
                                            <option value="">All</option>
                                            <option value="0" @if(request('status') === '0') selected @endif>Unused</option>
                                            <option value="1" @if(request('status') === '1') selected @endif>Used</option>
                                            <option value="2" @if(request('status') === '2') selected @endif>Expired</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="assort_id" class="kt-form-label">Type</label>
                                        <select id="assort_id" name="assort_id" class="kt-select">
                                            <option value="">All</option>
                                            <tbody>
@forelse ($lists as $list)
    <tr>
        <td>{{ $list->id }}</td>
        <td>{{ $list->code }}</td>
        <td>{{ $list->created_at }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3">No data available</td>
    </tr>
@endforelse
</tbody>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="date_range" class="kt-form-label">Date Range</label>
                                        <input type="text" id="date_range" name="date_range" class="kt-input" placeholder="Select date range" value="{{ request('date_range') }}">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end gap-3">
                                        <button type="submit" class="kt-btn kt-btn-primary">Search</button>
                                        <a href="{{ route('license.export', request()->query()) }}" class="kt-btn kt-btn-outline kt-btn-primary">Export Excel</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ __('messages.license_list.generated_codes') }}</h3>
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
                                                <th>{{ __('messages.license_list.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($lists as $code)
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
        <td colspan="7" class="text-center">No data found</td>
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
                <h3 class="kt-modal-title">{{ __('messages.license_list.update_remark_title') }}</h3>
                <button class="kt-btn kt-btn-icon kt-btn-sm kt-btn-light-primary" data-kt-modal-dismiss="true">
                    <i class="ki-filled ki-cross"></i>
                </button>
            </div>
            <div class="kt-modal-body">
                <form id="update-remark-form-{{ $list->id }}" action="{{ route('license.update', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="remark-{{ $list->id }}" class="kt-form-label">{{ __('messages.license_list.remark') }}</label>
                        <textarea id="remark-{{ $list->id }}" name="remark" class="kt-input" rows="4">{{ $list->remark }}</textarea>
                    </div>
                    <div class="kt-modal-footer">
                        <button type="button" class="kt-btn kt-btn-light" data-kt-modal-dismiss="true">Cancel</button>
                        <button type="submit" class="kt-btn kt-btn-primary">{{ __('messages.license_list.save_remark') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Metronic Update Remark Modal -->
<div class="kt-modal fade" id="kt_modal_update_remark" tabindex="-1">
    <div class="kt-modal-dialog modal-dialog-centered">
        <div class="kt-modal-content">
            <div class="kt-modal-header">
                <h5 class="kt-modal-title">Update Remark</h5>
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
                    <label for="modal_remark_input" class="form-label">New Remark:</label>
                    <input type="text" class="form-control" id="modal_remark_input" maxlength="128">
                </div>
            </div>
            <div class="kt-modal-footer">
                <button type="button" class="btn btn-light" data-kt-modal-dismiss="true">Close</button>
                <button type="button" class="btn btn-primary" id="modal_save_remark_button">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
