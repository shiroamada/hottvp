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
                                    {{ __('messages.pre_generated_codes.index.title') }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.pre_generated_codes.create') }}" class="kt-btn kt-btn-primary">
                                    {{ __('messages.pre_generated_codes.index.import_button') }}
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
                                <form action="{{ route('admin.pre_generated_codes.index') }}" method="GET" class="grid sm:grid-cols-2 md:grid-cols-4 gap-5" id="filter-form">
                                    <div class="kt-form-item">
                                        <label for="code" class="kt-form-label">{{ __('messages.pre_generated_codes.index.code_label') }}</label>
                                        <input type="text" id="code" name="code" class="kt-input" placeholder="{{ __('messages.pre_generated_codes.index.code_placeholder') }}" value="{{ request('code') }}">
                                    </div>
                                    <div class="kt-form-item">
                                        <label for="type" class="kt-form-label">{{ __('messages.pre_generated_codes.index.type_label') }}</label>
                                        <select id="type" name="type" class="kt-select">
                                            <option value="">{{ __('messages.pre_generated_codes.index.type_all') }}</option>
                                            @foreach($types as $key => $value)
                                                <option value="{{ $key }}" @if(request('type') === $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="kt-form-item">
                                        <label for="status" class="kt-form-label">{{ __('messages.pre_generated_codes.index.status_label') }}</label>
                                        <select id="status" name="status" class="kt-select">
                                            <option value="">{{ __('messages.pre_generated_codes.index.status_all') }}</option>
                                            <option value="available" @if(request('status') === 'available') selected @endif>{{ __('messages.pre_generated_codes.index.status_available') }}</option>
                                            <option value="requested" @if(request('status') === 'requested') selected @endif>{{ __('messages.pre_generated_codes.index.status_requested') }}</option>
                                        </select>
                                    </div>
                                    <div class="kt-form-item">
                                        <label for="date_range" class="kt-form-label">{{ __('messages.pre_generated_codes.index.date_range_label') }}</label>
                                        <input type="text" id="date_range" name="imported_at" class="kt-input" placeholder="{{ __('messages.pre_generated_codes.index.date_range_placeholder') }}" value="{{ request('imported_at') }}">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end gap-3">
                                        <button type="submit" class="kt-btn kt-btn-primary">{{ __('messages.pre_generated_codes.index.search') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ __('messages.pre_generated_codes.index.table_title') }}</h3>
                            </div>
                            <div class="kt-card-content">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.pre_generated_codes.index.col_code') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_type') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_vendor') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_remark') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_status') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_imported_by') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_imported_at') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_requested_by') }}</th>
                                                <th>{{ __('messages.pre_generated_codes.index.col_requested_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lists as $code)
                                            <tr>
                                                <td>{{ $code->code }}</td>
                                                <td>{{ $code->type }}</td>
                                                <td>{{ $code->vendor }}</td>
                                                <td>{{ $code->remark }}</td>
                                                <td>
                                                    @if($code->requested_at)
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">{{ __('messages.pre_generated_codes.index.badge_requested') }}</span>
                                                    @else
                                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">{{ __('messages.pre_generated_codes.index.badge_available') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $code->importer->name ?? __('messages.pre_generated_codes.index.na') }}</td>
                                                <td>{{ $code->imported_at }}</td>
                                                <td>{{ $code->requester->name ?? __('messages.pre_generated_codes.index.na') }}</td>
                                                <td>{{ $code->requested_at }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="text-center">{{ __('messages.pre_generated_codes.index.empty') }}</td>
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
    });
</script>
@endpush
