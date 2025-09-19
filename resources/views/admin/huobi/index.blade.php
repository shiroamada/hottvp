@extends('admin.layouts.master')

@section('content')
<!-- Page -->
<!-- Base -->
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
                                    <i class="ki-filled ki-wallet me-2"></i>
                                    {{__('huobi.managers')}}
                                </h1>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->

                    <!-- Summary boxes -->
                    <div class="kt-container-fixed">
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5">
                            <div class="kt-card flex-col justify-between p-5">
                                <p class="text-sm text-muted-foreground">{{__('huobi.huobi_balance')}}</p>
                                <h3 class="text-2xl font-semibold mt-2">{{ number_format(\Auth::guard('admin')->user()->balance, 2) }}</h3>
                            </div>
                            <div class="kt-card flex-col justify-between p-5">
                                <p class="text-sm text-muted-foreground">{{__('huobi.this_month_recharge')}}</p>
                                <h3 class="text-2xl font-semibold mt-2">{{ number_format($lower_recharge, 2) }}</h3>
                            </div>
                            <div class="kt-card flex-col justify-between p-5">
                                <p class="text-sm text-muted-foreground">{{__('huobi.add_recharge')}}</p>
                                <h3 class="text-2xl font-semibold mt-2">{{ number_format(\Auth::guard('admin')->user()->recharge, 2) }}</h3>
                            </div>
                            <div class="kt-card flex-col justify-between p-5">
                                <p class="text-sm text-muted-foreground">{{__('huobi.add_lower_profit')}}</p>
                                <h3 class="text-2xl font-semibold mt-2">{{ number_format($add_profit, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- End of Summary boxes -->

                    <!-- Container -->
                    <div class="kt-container-fixed mt-5 lg:mt-7.5">
                        <div class="grid gap-5 lg:gap-7.5">
                            <div class="kt-card kt-card-grid min-w-full">
                                <div class="kt-card-header flex-wrap gap-2">
                                    <div class="flex flex-wrap gap-2 lg:gap-5">
                                        <form name="admin_list_sea" class="form-search flex flex-wrap gap-2" method="get" id="export" action="{{ route('admin.huobi.index') }}">
                                            @csrf
                                            <div class="flex">
                                                <select class="kt-input" name="status">
                                                    <option value="0">{{__('general.select')}}</option>
                                                    <option value="1" @if(isset($condition['status']) && $condition['status'] == 1) selected @endif>{{__('huobi.into_code')}}</option>
                                                    <option value="2" @if(isset($condition['status']) && $condition['status'] == 2) selected @endif>{{__('huobi.for_subordinates')}}</option>
                                                    <option value="3" @if(isset($condition['status']) && $condition['status'] == 3) selected @endif>{{__('huobi.generate_code')}}</option>
                                                    <option value="4" @if(isset($condition['status']) && $condition['status'] == 4) selected @endif>{{__('huobi.lower_generate_code')}}</option>
                                                </select>
                                            </div>
                                            <div class="flex">
                                                <input class="kt-input w-60" type="text" name="date2" id="date2" value="{{ $condition['date2'] ?? ''  }}" autocomplete="off" placeholder="{{__('general.range')}}">
                                            </div>
                                            <div class="flex flex-wrap gap-2.5">
                                                <button class="kt-btn kt-btn-primary" type="submit" onclick="check(1)">{{__('general.search')}}</button>
                                                <button class="kt-btn kt-btn-success" type="submit" onclick="check(2)">{{__('general.excel')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="kt-card-content">
                                    <div class="grid">
                                        <div class="kt-scrollable-x-auto">
                                            <table class="kt-table table-auto kt-table-border">
                                                <thead>
                                                <tr>
                                                    <th class="text-start">{{__('huobi.id')}}</th>
                                                    <th class="text-start">{{__('general.create')}}</th>
                                                    <th class="text-start">{{__('huobi.event')}}</th>
                                                    <th class="text-start">{{__('huobi.money')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @isset($lists)
                                                    @foreach($lists as $k => $v)
                                                        @if($v['user_id'] != \Auth::guard('admin')->user()->id && $v['status'] == 1 && $v['type'] == 1)
                                                        @else
                                                            @if($v['money'] > 0)
                                                                <tr>
                                                                    <td>{{ $v['id'] }}</td>
                                                                    <td>{{ $v['created_at'] }}</td>
                                                                    <td>
                                                                        @php
                                                                            $details = App\Repository\Admin\AdminUserRepository::find($v['own_id']);
                                                                            $assort = App\Repository\Admin\AssortRepository::find($v['assort_id']);
                                                                        @endphp
                                                                        @if($v['status'] == 1 && $v['type'] == 2)
                                                                            @if(isset($details->name))
                                                                                {{__('adminUser.by')}} {{ $details->name }} {{__('adminUser.lower')}}
                                                                            @else
                                                                                {{__('adminUser.lower')}}
                                                                            @endif
                                                                        @elseif($v['status'] == 1 && $v['type'] == 1)
                                                                            {{__('adminUser.myself')}}
                                                                        @elseif($v['status'] == 0 && $v['type'] == 1)
                                                                            @if ($details && $details->account == $v['user_account'])
                                                                                {{ $details->name }} {{__('general.generate')}} {{ $assort->assort_name ?? '' }}
                                                                            @else
                                                                                {{ $details->name ?? '' }} {{__('general.as_lower')}} {{ $v['user_account'] }} {{__('general.generate')}} {{ $assort->assort_name ?? '' }}
                                                                            @endif
                                                                        @elseif($v['status'] == 0 && $v['type'] == 2)
                                                                            @if(isset($details->name))
                                                                                {{ $details->name }} {{__('general.generate')}} {{ $assort->assort_name ?? '' }}
                                                                            @else
                                                                                {{__('general.generate')}} {{ $assort->assort_name ?? '' }}
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td class="{{ $v['type'] == 2 ? 'text-danger' : 'text-primary' }}">
                                                                        {{ $v['type'] == 2 ? '-' : '+' }}{{ number_format($v['money'], 2) }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="pages" class="mt-5">
                                        {!! $lists->appends(['status'=>$lists->status, 'date2'=>$lists->date2])->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#date2", {
            mode: "range",
            dateFormat: "Y-m-d",
        });
    });

    function check(id) {
        var url = "{{ route('admin.huobi.index') }}";
        var excel_url = "{{ route('admin.huobi.export') }}";
        if (id == 1) {
            document.getElementById('export').action = url;
        } else {
            document.getElementById('export').action = excel_url;
        }
    }
</script>
@endpush
