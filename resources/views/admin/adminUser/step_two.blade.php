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
                                    <i class="ki-filled ki-chart-pie-3 me-2"></i>
                                    {{trans('huobi.managers')}}
                                </h1>
                            </div>
                            <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                                <a href="{{ route('admin.users.visual', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('general.index')}}</a>
                                <a href="{{ route('admin.users.stepOne', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('adminUser.managers')}}</a>
                                <a href="{{ route('admin.users.stepTwo', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light-primary">{{trans('huobi.managers')}}</a>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->

                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 lg:gap-7.5 mb-10">
                            <!-- Huobi Balance -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-wallet text-primary text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($userInfo->balance, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('huobi.huobi_balance')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- This Month Recharge -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-arrow-up-right text-info text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($lower_recharge, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('huobi.this_month_recharge')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Recharge -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-plus-circle text-secondary text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($userInfo->recharge, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('huobi.add_recharge')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Lower Profit -->
                            <div class="kt-card">
                                <div class="kt-card-content p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-none">
                                            <i class="ki-filled ki-chart-line-up text-success text-3xl"></i>
                                        </div>
                                        <div class="grow">
                                            <div class="text-2xl font-semibold">{{ number_format($add_profit, 2) }}</div>
                                            <div class="text-sm text-secondary-foreground">{{trans('huobi.add_lower_profit')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-card kt-card-grid min-w-full">
                            <div class="kt-card-header flex-wrap gap-2">
                                <div class="flex flex-wrap gap-2 lg:gap-5">
                                    <form name="admin_list_sea" class="form-search flex flex-wrap gap-2.5" method="get" action="{{ route('admin.users.stepTwo', ['id' => $id]) }}">
                                        {{ csrf_field() }}
                                        <div class="flex">
                                            <select class="kt-input w-40" name="status">
                                                <option value="0">{{ trans('general.select') }}</option>
                                                <option value="1"
                                                        @if(isset($condition['status']) && $condition['status'] == 1) selected @endif>{{trans('huobi.into_code')}}</option>
                                                <option value="2"
                                                        @if(isset($condition['status']) && $condition['status'] == 2) selected @endif>{{trans('huobi.for_subordinates')}}</option>
                                                <option value="3"
                                                        @if(isset($condition['status']) && $condition['status'] == 3) selected @endif>{{trans('huobi.generate_code')}}</option>
                                                <option value="4"
                                                        @if(isset($condition['status']) && $condition['status'] == 4) selected @endif>{{trans('huobi.lower_generate_code')}}</option>
                                            </select>
                                        </div>

                                        <div class="flex">
                                            <input class="kt-input w-40" type="text" name="date2" id="date2"
                                                   value="{{ $condition['date2'] ?? ''  }}" autocomplete="off"
                                                   placeholder="{{trans('general.range')}}">
                                            <input type="hidden" id="startTime" name="startTime" class="form-control"/>
                                            <input type="hidden" id="endTime" name="endTime" class="form-control"/>
                                        </div>
                                        <div class="flex flex-wrap gap-2.5">
                                            <button class="kt-btn kt-btn-success" type="submit" id="submitBtn">{{trans('general.search')}}</button>
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
                                                <th class="text-start">{{trans('huobi.id')}}</th>
                                                <th class="text-start">{{trans('general.create')}}</th>
                                                <th class="text-start">{{trans('huobi.event')}}</th>
                                                <th class="text-start">{{trans('huobi.money')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @isset($lists)
                                                @foreach($lists as $k => $v)
                                                    @if($v['user_id'] != $id && $v['status'] == 1 && $v['type'] == 1)
                                                    @else
                                                        @if($v['money'] > 0)
                                                            <tr>
                                                                <td>{{ $v['id'] }}</td>
                                                                <td>{{ $v['created_at'] }}</td>
                                                                <td>
                                                                    <?php
                                                                        if (Auth::guard('admin')->user()->id == 1) {
                                                                            $details = App\Repository\Admin\AdminUserRepository::find($v['user_id']);
                                                                        } else {
                                                                            $details = App\Repository\Admin\AdminUserRepository::find($v['own_id']);
                                                                        }
                                                                        $assort = App\Repository\Admin\AssortRepository::find($v['assort_id']);
                                                                    ?>
                                                                    @if($v['status'] == 1 && $v['type'] == 2)
                                                                        @if(isset($details->name))
                                                                            {{ trans('adminUser.by') }} {{ $details->name }} {{ trans('adminUser.lower') }}
                                                                        @else
                                                                            {{ trans('adminUser.lower') }}
                                                                        @endif
                                                                    @elseif($v['status'] == 1 && $v['type'] == 1)
                                                                        {{ trans('adminUser.myself') }}
                                                                    @elseif($v['status'] == 0 && $v['type'] == 1)
                                                                        @if(isset($details->name))
                                                                            {{ $details->name }} {{ trans('general.as_lower') }} {{ $v['user_account'] }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                                        @else
                                                                            {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                                        @endif
                                                                    @elseif($v['status'] == 0 && $v['type'] == 2)
                                                                        @if(isset($details->name))
                                                                            {{ $details->name }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                                        @else
                                                                            {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td
                                                                        @if($v['type'] == 2)
                                                                        class="text-danger"
                                                                        @else
                                                                        class="text-primary"
                                                                        @endif
                                                                >
                                                                    @if($v['type'] == 2)
                                                                        -{{ number_format($v['money'], 2) }}
                                                                    @else
                                                                        +{{ number_format($v['money'], 2) }}
                                                                    @endif
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
                            </div>

                            <div class="kt-card-footer">
                                @isset($lists)
                                    {!! $lists->appends(['status'=>$lists->status, 'date2'=>$lists->date2])->render() !!}
                                @endisset
                            </div>
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

@push('styles')
<style>
  /* Reusable “left stripe + padding shift” for KTUI items */
  .kt-link-stripe{
    position:relative;display:block;padding:.5rem .75rem .5rem 1rem;transition:all .2s ease
  }
  .kt-link-stripe::before{
    content:"";position:absolute;inset-block:0;inset-inline-start:0;width:3px;
    background: var(--kt-primary, rgb(99 102 241));opacity:0;transition:opacity .2s ease
  }
  .kt-link-stripe:hover,.kt-link-stripe:focus-visible{
    background: rgba(0,0,0,.06);padding-inline-start: calc(1rem - 3px);outline:none
  }
  .kt-link-stripe:hover::before,.kt-link-stripe:focus-visible::before{opacity:1}
  .dark .kt-link-stripe:hover,.dark .kt-link-stripe:focus-visible{background: rgba(255,255,255,.10)}
</style>
@endpush

@push('scripts')
  {{-- Ensure KTUI JS is available (remove if your master already loads it) --}}
  <script src="{{ asset('assets/js/ktui.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.KTDropdown && typeof KTDropdown.createInstances === 'function') {
        KTDropdown.createInstances();
      }
    });

    laydate.render({
        elem: '#date2',
        range: true,
        trigger: 'click'
    });
  </script>
@endpush