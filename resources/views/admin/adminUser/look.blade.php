@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
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
                        <i class="ki-filled ki-abstract-28 me-2"></i>
                        {{ __('messages.agent_list.check_cost') }}
                    </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                    <button class="kt-btn kt-btn-secondary" onclick="history.go(-1);">
                        {{ __('messages.general.return') }}
                    </button>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5">
                <!-- Equipment Money Card -->
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.equipment.money') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <div class="kt-table-responsive">
                            <table class="kt-table">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.adminUser.assort') }}</th>
                                    <th>{{ __('messages.equipment.retail_price') }}</th>
                                    <th>{{ __('messages.equipment.country') }}</th>
                                    <th>{{ __('messages.equipment.diamond') }}</th>
                                    <th>{{ __('messages.equipment.medal') }}</th>
                                    <th>{{ __('messages.equipment.silver') }}</th>
                                    <th>{{ __('messages.equipment.copper') }}</th>
                                    <th>{{ __('messages.equipment.defined') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($lists)
                                    @foreach($lists as $k => $v)
                                        <tr>
                                            <td>{{ $v ?? 0 }}</td>
                                            <td>{{ $retail[$k] ?? 0 }}</td>
                                            <td>{{ $data[3][$k] ?? 0 }}</td>
                                            <td>{{ $data[4][$k] ?? 0 }}</td>
                                            <td>{{ $data[5][$k] ?? 0 }}</td>
                                            <td>{{ $data[6][$k] ?? 0 }}</td>
                                            <td>{{ $data[7][$k] ?? 0 }}</td>
                                            <td>{{ $data[8][$k] ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Entry Barriers Card -->
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.adminUser.entry_barriers') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <div class="kt-table-responsive">
                            <table class="kt-table">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.equipment.medal_sill') }}</th>
                                    <th>{{ __('messages.equipment.silver_sill') }}</th>
                                    <th>{{ __('messages.equipment.copper_sill') }}</th>
                                    <th>{{ __('messages.equipment.defined_sill') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @isset($cost)
                                        @foreach($cost as $k => $v)
                                            <td>{{ $v ?? 0 }}</td>
                                        @endforeach
                                    @endisset
                                </tr>
                                </tbody>
                            </table>
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
<script>
    // Any additional scripts can be added here
</script>
@endpush 