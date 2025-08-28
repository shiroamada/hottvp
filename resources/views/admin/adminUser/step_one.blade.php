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
                                    <i class="ki-filled ki-user-square me-2"></i>
                                    {{ trans('adminUser.managers') }}
                                </h1>
                            </div>
                            <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                                <a href="{{ route('admin.users.visual', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('general.index')}}</a>
                                <a href="{{ route('admin.users.stepOne', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light-primary">{{trans('adminUser.managers')}}</a>
                                <a href="{{ route('admin.users.stepTwo', ['id' => $id]) }}" class="kt-btn kt-btn-sm kt-btn-light">{{trans('huobi.managers')}}</a>
                            </div>
                        </div>
                        <!-- End of Container -->
                    </div>
                    <!-- End of Toolbar -->

                    <!-- Container -->
                    <div class="kt-container-fixed">
                        <div class="grid gap-5 lg:gap-7.5">
                            <div class="kt-card kt-card-grid min-w-full">
                                <div class="kt-card-header flex-wrap gap-2">
                                    <div class="flex flex-wrap gap-2 lg:gap-5">
                                        <form name="admin_list_sea" class="form-search flex flex-wrap gap-2.5" method="get"
                                              action="{{ route('admin.users.stepOne', ['id' => $id]) }}">
                                            {{ csrf_field() }}
                                            <div class="flex">
                                                <input class="kt-input w-40"
                                                       type="text"
                                                       placeholder="{{trans('adminUser.name')}}"
                                                       name="name"
                                                       value="{{ $condition['name'] ?? '' }}"
                                                       autocomplete="off">
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
                                                    <th class="text-start">{{trans('adminUser.id')}}</th>
                                                    <th class="text-start">{{trans('adminUser.agency_name')}}</th>
                                                    <th class="text-start">{{trans('adminUser.agency_level')}}</th>
                                                    <th class="text-start">{{trans('adminUser.balance')}}</th>
                                                    <th class="text-start">{{trans('huobi.add_lower_profit')}}</th>
                                                    <th class="text-start">{{trans('adminUser.remark')}}</th>
                                                    <th class="text-start">{{trans('general.create')}}</th>
                                                    <th class="text-end">{{trans('general.action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @isset($lists)
                                                    @foreach($lists as $k => $v)
                                                        <tr>
                                                            <td>{{ $v['id'] }}</td>
                                                            @if($v['is_cancel'] != 0)
                                                                <td class="text-muted" title="{{ $v['name'] ?? ""}}">{{ $v['name'] ?? "" }} {{trans('general.is_del')}}</td>
                                                            @else
                                                                <td title="{{ $v['name'] ?? "" }}">{{ $v['name'] ?? "" }}</td>
                                                            @endif
                                                            <td>
                                                                {{ isset($v->levels->level_name) ? $v->levels->level_name : "" }}
                                                                @if(Auth::guard('admin')->user()->level_id <= 3)
                                                                    @if($v->type == 2)
                                                                        <span class="badge badge-primary">Pro</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ number_format($v['balance'], 2) }}</td>
                                                            <td>
                                                                <?php
                                                                if ($id == 1) {
                                                                    $where = ["user_id" => $v['id'], 'status' => 0];
                                                                } else {
                                                                    $where = ["user_id" => $id, 'status' => 0, 'type' => 1, 'create_id' => $v['id']];
                                                                }
                                                                $user_lirun = App\Models\Admin\Huobi::query()->where($where)->get();
                                                                $user_pro = 0;
                                                                foreach ($user_lirun as $value) {
                                                                    $assort_where = ['user_id' => $parent_id, 'assort_id' => $value['assort_id'], 'level_id' => 3];
                                                                    $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                                                    $total = isset($assort_level->money) ? bcmul($assort_level->money, $value['number'], 2) : 0;
                                                                    $user_pro += $total;
                                                                }
                                                                $profit = App\Repository\Admin\HuobiRepository::levelByRecord($where);
                                                                ?>
                                                                @if(Auth::guard('admin')->user()->id == 1)
                                                                    {{ number_format($user_pro, 2) }}
                                                                @else
                                                                    {{ number_format($profit, 2) }}
                                                                @endif
                                                            </td>
                                                            <td title="{{ $v['remark'] }}">
                                                                @if(mb_strlen($v['remark']) > 10)
                                                                    <div x-data="{ open: false }" class="relative">
                                                                        <span @mouseover="open = true" @mouseout="open = false" class="cursor-pointer">
                                                                            {{ mb_substr($v['remark'], 0, 10) }}...
                                                                        </span>
                                                                        <div x-show="open"
                                                                             x-transition
                                                                             class="absolute z-10 w-64 p-2 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-gray-800 rounded-lg shadow-lg">
                                                                            {{ $v['remark'] }}
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    {{ $v['remark'] }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $v['created_at'] }}</td>
                                                            <td class="text-end">
                                                                <div class="inline-flex"
                                                                     data-kt-dropdown="true"
                                                                     data-kt-dropdown-trigger="click"
                                                                     data-kt-dropdown-placement="bottom-end">
                                                                    <button type="button" class="kt-btn kt-btn-sm kt-btn-primary"
                                                                            data-kt-dropdown-toggle="true">
                                                                        {{trans('general.action')}}
                                                                    </button>
                                                                    <div class="kt-dropdown-menu w-56" data-kt-dropdown-menu="true">
                                                                        <ul class="kt-dropdown-menu-sub">
                                                                            @if($v['is_cancel'] != 2)
                                                                                <li>
                                                                                    <a class="kt-dropdown-menu-link kt-link-stripe"
                                                                                       href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">{{trans('adminUser.lower_agent')}}</a>
                                                                                </li>
                                                                            @endif
                                                                            <li>
                                                                                <a class="kt-dropdown-menu-link kt-link-stripe"
                                                                                   href="{{ route('admin.users.examine', ['id' => $v['id']]) }}">{{trans('adminUser.check')}}</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="kt-card-footer">
                                    @isset($lists)
                                        {!! $lists->appends(['name'=>$lists->name])->render() !!}
                                    @endisset
                                </div>
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
  </script>
@endpush
