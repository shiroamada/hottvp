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
                        {{ __('messages.agent_detail.title') }}
                    </h1>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5 xl:w-[38.75rem] mx-auto">
                <div class="kt-card">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_detail.new_agent_credentials') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <p class="mb-4">{{ __('messages.agent_detail.credentials_notice') }}</p>
                        <div id="credentials-text" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="kt-form-label">{{ __('messages.agent_detail.name') }}</label>
                                    <div class="text-lg font-mono">{{ $info->name ?? '' }}</div>
                                </div>
                                <div>
                                    <label class="kt-form-label">{{ __('messages.agent_detail.remark') }}</label>
                                    <div class="text-lg font-mono">{{ $info->remark ?? '' }}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="kt-form-label">{{ __('messages.agent_detail.account') }}</label>
                                    <div class="text-lg font-mono">{{ $new_agent_account ?? '' }}</div>
                                </div>
                                <div>
                                    <label class="kt-form-label">{{ __('messages.agent_detail.password') }}</label>
                                    <div class="text-lg font-mono">{{ $new_agent_password ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-card-footer flex justify-end gap-3">
                        <button class="kt-btn kt-btn-outline" id="copyBtn">{{ __('messages.agent_detail.copy_credentials') }}</button>
                        <a href="{{ route('admin.users.index') }}" class="kt-btn kt-btn-primary">{{ __('messages.agent_detail.done') }}</a>
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
  <!-- End of Base -->
<!-- End of Page -->
@endsection

@push('scripts')
<script>
window.addEventListener('load', function () {
    if (typeof jQuery === 'undefined') {
        return;
    }
    (function($) {
        $(document).ready(function() {
            $('#copyBtn').click(function() {
                const account = "{{ $new_agent_account ?? '' }}";
                const password = "{{ $new_agent_password ?? '' }}";
                const textToCopy = `Account: ${account}\nPassword: ${password}`;
                
                navigator.clipboard.writeText(textToCopy).then(function() {
                    alert('Credentials copied to clipboard!');
                }, function(err) {
                    alert('Could not copy text: ', err);
                });
            });
        });
    })(jQuery);
});
</script>
@endpush
