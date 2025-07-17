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
                        {{ __('messages.agent_recharge.title') }}
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
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.agent_recharge.form_title') }}</h3>
                    </div>
                    <div class="kt-card-content">
                        <form method="post" action="{{ route('admin.users.pay') }}" id="form" class="space-y-6">
                            @csrf
                            <input type="hidden" name="id" value="{{ $info->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="font-medium mb-1">{{ __('messages.agent_recharge.name') }}:</div>
                                    <div>{{ $info->name ?? '' }}</div>
                                </div>
                                
                                <div>
                                    <div class="font-medium mb-1">{{ __('messages.agent_recharge.account') }}:</div>
                                    <div>{{ $info->account ?? '' }}</div>
                                </div>
                                
                                <div>
                                    <div class="font-medium mb-1">{{ __('messages.agent_recharge.level') }}:</div>
                                    <div>
                                        {{ $info->levels->level_name ?? '' }}
                                        @if($info->type == 2)
                                            <span class="badge badge-primary">Pro</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="font-medium mb-1">{{ __('messages.agent_recharge.current_balance') }}:</div>
                                    <div>{{ number_format($info->balance, 2) }}</div>
                                </div>
                            </div>
                            
                            <div class="kt-form-group">
                                <div class="font-medium mb-2">{{ __('messages.agent_recharge.your_balance') }}: 
                                    <span class="text-warning">{{ number_format(auth()->guard('admin')->user()->balance, 2) }}</span>
                                </div>
                                
                                <label class="kt-form-label">{{ __('messages.agent_recharge.amount') }}</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <input class="kt-input" type="text" name="balance" value="" 
                                            id="balance" onkeyup="onlyNumber(this, 2)" maxlength="8">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kt-form-group">
                                <label class="kt-form-label">{{ __('messages.agent_recharge.remark') }}</label>
                                <textarea class="kt-textarea" rows="3" name="remark" maxlength="128"></textarea>
                            </div>
                            
                            <div class="flex justify-center gap-4">
                                <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                                    {{ __('messages.agent_recharge.submit') }}
                                </button>
                                <button type="button" class="kt-btn kt-btn-secondary" onclick="history.go(-1);">
                                    {{ __('messages.general.return') }}
                                </button>
                            </div>
                        </form>
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
    // Number validation function
    window.onlyNumber = function(obj, type) {
        // Remove non-numeric characters
        let value = $(obj).val().replace(/[^\d.]/g, '');
        
        if (type === 1) {
            // Integer only
            value = value.replace(/\./g, '');
        } else if (type === 2) {
            // Allow decimals (up to 2 decimal places)
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            if (parts.length === 2 && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }
            
            // Format with commas for thousands
            if (parts[0].length > 3) {
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                value = parts.length === 2 ? parts[0] + '.' + parts[1] : parts[0];
            }
        }
        
        $(obj).val(value);
    };
    
    // Form submission
    $('#form').submit(function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true);
        
        // Remove commas from numbers before submitting
        const balance = $('#balance').val().replace(/,/g, '');
        $('#balance').val(balance);
        
        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result) {
                if (result.code !== 0) {
                    alert(result.msg);
                    $('#submitBtn').prop('disabled', false);
                    return false;
                }
                
                alert(result.msg);
                if (result.redirect) {
                    location.href = "{{ route('admin.users.index') }}";
                }
            },
            error: function(xhr) {
                $('#submitBtn').prop('disabled', false);
                if (xhr.status === 422) {
                    // Validation error
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (const key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                    alert(errorMessage);
                } else {
                    alert("{{ __('messages.general.error') }}");
                }
            }
        });
    });
});
</script>
@endpush
