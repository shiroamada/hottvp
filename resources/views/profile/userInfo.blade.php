@extends('admin.layouts.master')

@section('content')
<div class="flex grow">
    @include('layouts/partials/_sidebar')
    <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
        <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
            <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
                <main class="grow" role="content">
                    <div class="pb-5">
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ trans('general.set_user_info') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="kt-container-fixed">
                        <div class="grid gap-5 lg:gap-7.5">
                            <div class="kt-card kt-card-grid">
                                <div class="kt-card-header">
                                    <h4 class="kt-card-title">{{ trans('general.user_info') }}</h4>
                                </div>
                                <div class="kt-card-content p-5">
                                    <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                                        <div class="shrink-0">
                                            <img class="rounded-xl object-cover" width="165" height="165"
                                                 src="{{ !empty($user['photo']) ? $user['photo'] : asset('public/images/users/user-09-247x247.png') }}"
                                                 alt="{{ $user['name'] ?? '' }}">
                                        </div>
                                        <div class="grow grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.name') }}:</span>
                                                <span class="font-medium">{{ $user['name'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.email') }}:</span>
                                                <span class="font-medium">{{ $user['email'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.phone') }}:</span>
                                                <span class="font-medium">{{ $user['phone'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.level') }}:</span>
                                                <span class="font-medium">{{ isset($user->levels->level_name) ? $user->levels->level_name : trans('adminUser.admin') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.account') }}:</span>
                                                <span class="font-medium">{{ $user['account'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted-foreground">{{ trans('adminUser.remark') }}:</span>
                                                <span class="font-medium">{{ $user['remark'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 mt-5">
                                        <a href="{{ route('profile.userEdit') }}" class="kt-btn kt-btn-primary">
                                            {{ trans('general.update_user_info') }}
                                        </a>
                                        <button type="button" class="kt-btn kt-btn-warning" onclick="history.go(-1);">
                                            {{ trans('general.return') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
@endsection