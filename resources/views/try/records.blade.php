@extends('layouts.master')

@section('content')
<!-- Page -->
<div class="flex grow">
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
                                    {{ trans('authCode.try_records') }}
                                </h1>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->
                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="kt-card">
                            <div class="kt-card-header">
                                <h3 class="kt-card-title">{{ trans('authCode.try_records') }}</h3>
                                <div class="kt-card-toolbar">
                                <button class="kt-btn kt-btn-icon kt-btn-sm kt-btn-light-primary"
        data-kt-modal-toggle="#modal-sample">
    <i class="ki-filled ki-question"></i>
</button>
                                </div>
                            </div>
                            <div class="kt-card-content">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('authCode.id') }}</th>
                                                <th>{{ trans('authCode.try_time') }}</th>
                                                <th>{{ trans('authCode.try_condition') }}</th>
                                                <th>{{ trans('authCode.number') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($lists) @foreach($lists as $k => $v)
                                            <tr>
                                                <td>{{ $v['id'] }}</td>
                                                <td>{{ $v['created_at'] }}</td>
                                                <td>{{ $v['description'] }}</td>
                                                <td>{{ $v['number'] }}</td>
                                            </tr>
                                            @endforeach @endisset
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

<!-- Modal -->
<div class="kt-modal" data-kt-modal="true" id="modal-sample">
    <div class="kt-modal-content max-w-[400px] top-[10%]">
        <div class="kt-modal-header">
            <h3 class="kt-modal-title">{{trans('general.message')}}</h3>
            <button
                type="button"
                class="kt-modal-close"
                aria-label="Close modal"
                data-kt-modal-dismiss="#modal-sample"
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
            <div style="margin-left: 60px">
                <p>{{trans('authCode.one_tips')}}</p>
                @if(isset($assort) && count($assort) > 3)
                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_one')}} {{ $assort[0]['try_num'] }}</p>
                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_two')}} {{ $assort[1]['try_num'] }}</p>
                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_three')}} {{ $assort[2]['try_num'] }}</p>
                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_four')}} {{ $assort[3]['try_num'] }}</p>
                @endif
                <p>{{trans('authCode.two_tips')}}</p>
            </div>
        </div>
        <div class="kt-modal-footer">
            <button type="button" class="kt-btn kt-btn-primary" data-kt-modal-dismiss="#modal-sample">{{trans('general.confirm')}}</button>
        </div>
    </div>
</div>
