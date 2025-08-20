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
                        {{ __('messages.agent_check.title') }}
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
                @if(isset($new_agent_password) && $new_agent_password)
                <!-- New Agent Credentials Card -->
                <div class="kt-card kt-card-grid min-w-full bg-success-light">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title text-success">{{ __('messages.agent_check.new_agent_credentials') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <p class="mb-4">{{ __('messages.agent_check.credentials_notice') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.account') }}:</div>
                                <div class="text-lg font-mono">{{ $new_agent_account }}</div>
                            </div>
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.password') }}:</div>
                                <div class="text-lg font-mono">{{ $new_agent_password }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Agent Basic Info Card -->
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_check.info_title') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.name') }}:</div>
                                <div>{{ $info->name ?? '' }}</div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.account') }}:</div>
                                <div>{{ $info->account ?? '' }}</div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.level') }}:</div>
                                <div>
                                    {{ $info->levels->level_name ?? '' }}
                                    @if($info->type == 2)
                                        <span class="badge badge-primary">Pro</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.current_balance') }}:</div>
                                <div>{{ number_format($info->balance, 2) }}</div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.total_recharge') }}:</div>
                                <div>{{ number_format($info->recharge, 2) }}</div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.created_at') }}:</div>
                                <div>{{ $info->created_at }}</div>
                            </div>
                            
                            <div>
                                <div class="font-medium mb-1">{{ __('messages.agent_check.remark') }}:</div>
                                <div>{{ $info->remark ?? '' }}</div>
                            </div>
                        </div>
                        
                        <!-- Actions Button Group -->
                        <div class="flex flex-wrap gap-2 mt-6">
                            <a href="{{ route('admin.users.recharge', ['id' => $info->id]) }}" class="kt-btn kt-btn-primary">
                                {{ __('messages.agent_check.recharge') }}
                            </a>
                            
                            @if($type == 1)
                            <a href="{{ route('admin.users.level', ['id' => $info->id]) }}" class="kt-btn kt-btn-info">
                                {{ __('messages.agent_check.adjust_level') }}
                            </a>
                            @endif
                            
                            @if($info->level_id == 8)
                            <a href="{{ route('admin.users.cost', ['id' => $info->id]) }}" class="kt-btn kt-btn-warning">
                                {{ __('messages.agent_check.adjust_cost') }}
                            </a>
                            @endif
                            
                            <a href="{{ route('admin.users.lower', ['id' => $info->id]) }}" class="kt-btn kt-btn-secondary">
                                {{ __('messages.agent_check.view_agents') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Agent Profit Info Card -->
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_check.profit_title') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="kt-card kt-card-flush h-100 bg-indigo-50 dark:bg-indigo-950/10">
                                <div class="kt-card-body">
                                    <div class="text-center">
                                        <div class="text-2xl font-semibold mb-2">{{ number_format($profit, 2) }}</div>
                                        <div class="text-muted">{{ __('messages.agent_check.total_profit') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kt-card kt-card-flush h-100 bg-cyan-50 dark:bg-cyan-950/10">
                                <div class="kt-card-body">
                                    <div class="text-center">
                                        <div class="text-2xl font-semibold mb-2">{{ number_format($user_pro, 2) }}</div>
                                        <div class="text-muted">{{ __('messages.agent_check.admin_calculation') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kt-card kt-card-flush h-100 bg-green-50 dark:bg-green-950/10">
                                <div class="kt-card-body">
                                    <div class="text-center">
                                        <div class="text-2xl font-semibold mb-2">{{ number_format($profit_time, 2) }}</div>
                                        <div class="text-muted">{{ __('messages.agent_check.last_month_profit') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction History Tabs -->
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_check.history_title') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <!-- Tab Buttons -->
                        <div class="flex flex-wrap gap-2 mb-6 border-b border-input">
                            <button class="kt-tab-button kt-tab-active" data-tab="profit">
                                {{ __('messages.agent_check.tab_profit') }}
                            </button>
                            <button class="kt-tab-button" data-tab="recharge">
                                {{ __('messages.agent_check.tab_recharge') }}
                            </button>
                        </div>
                        
                        <!-- Profit Tab -->
                        <div class="kt-tab-content" id="profit-content">
                            <div class="kt-table-responsive">
                                <table class="kt-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.agent_check.date') }}</th>
                                            <th>{{ __('messages.agent_check.description') }}</th>
                                            <th>{{ __('messages.agent_check.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user_profit ?? [] as $item)
                                            <tr>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->event }}</td>
                                                <td>{{ number_format($item->money, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">{{ __('messages.agent_check.no_data') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
                                @if(isset($user_profit) && $user_profit->hasPages())
                                    <div class="mt-4">
                                        {{ $user_profit->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Recharge Tab -->
                        <div class="kt-tab-content hidden" id="recharge-content">
                            <div class="kt-table-responsive">
                                <table class="kt-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.agent_check.date') }}</th>
                                            <th>{{ __('messages.agent_check.description') }}</th>
                                            <th>{{ __('messages.agent_check.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user_recharge ?? [] as $item)
                                            <tr>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->event }}</td>
                                                <td>{{ number_format($item->money, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">{{ __('messages.agent_check.no_data') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
                                @if(isset($user_recharge) && $user_recharge->hasPages())
                                    <div class="mt-4">
                                        {{ $user_recharge->links() }}
                                    </div>
                                @endif
                            </div>
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
$(document).ready(function() {
    // Tab switching functionality
    $('.kt-tab-button').click(function() {
        const tabId = $(this).data('tab');
        
        // Remove active class from all buttons and hide all content
        $('.kt-tab-button').removeClass('kt-tab-active');
        $('.kt-tab-content').addClass('hidden');
        
        // Add active class to clicked button and show corresponding content
        $(this).addClass('kt-tab-active');
        $(`#${tabId}-content`).removeClass('hidden');
    });
});
</script>
@endpush
