@extends('admin.layouts.master')

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
                                    <i class="ki-filled ki-eye me-2"></i>
                                    {{ __('adminUser.check') }}
                                </h1>
                            </div>
                            <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                                <a href="{{ route('admin.users.visual', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light-primary">{{trans('general.index')}}</a>
                                <a href="{{ route('admin.users.stepOne', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('adminUser.managers')}}</a>
                                <a href="{{ route('admin.users.stepTwo', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('huobi.managers')}}</a>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->

                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5">
                            <!-- Huobi Balance -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-wallet text-primary text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($userInfo->balance, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.huobi_balance')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Month Code -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-chart-pie-simple text-info text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($month_code, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.month_code')}}</div>
                                            @if($locale == 'en' || $locale == 'my')
                                                <div class="text-xs text-muted-foreground">{{trans('home.month_code1')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Last Month Code -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-send text-success text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($last_month_code, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.last_month_code')}}</div>
                                             @if($locale == 'en' || $locale == 'my')
                                                <div class="text-xs text-muted-foreground">{{trans('home.last_month_code1')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Last Month Huobi -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-mailbox text-warning text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($month_expend, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.last_month_huobi')}}</div>
                                            @if($locale == 'en' || $locale == 'my')
                                                <div class="text-xs text-muted-foreground">{{trans('home.last_month_huobi1')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="mt-10 mb-5">{{trans('home.lower_agency')}}</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5">
                            <!-- Month Lower Profit -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-user text-primary text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($month_profit, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.month_lower_profit')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Last Month Profit -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-chart-line-up text-info text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($last_month_profit, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.last_month_profit')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Count Agency -->
                            @if(auth()->guard('admin')->user()->level_id <= 3)
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-people text-success text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($user_count, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('home.count_agency')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- End Container -->
                </main>
            </div>
        </div>
        <!-- End of Main -->
    </div>
    <!-- End of Wrapper -->
</div>
<!-- End of Page -->
@endsection