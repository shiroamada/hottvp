@extends('layouts.master')

@section('content')
<div class="flex grow">
    @include('layouts.partials._sidebar')
    <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
        <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
            <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
                <main class="grow" role="content">
                    <div class="pb-5">
                        <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                                <h1 class="font-medium text-lg text-mono">
                                    {{ __('messages.sidebar.my_profile') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="kt-container-fixed">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-7.5">
                            <div class="lg:col-span-2">
                                <div class="flex flex-col gap-5 lg:gap-7.5">
                                    @include('profile.partials.update-profile-information-form')
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                            <div class="lg:col-span-1">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
@endsection
