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
                        @if(isset($id))
                            {{ __('messages.agent_edit.title') }}
                        @else
                            {{ __('messages.agent_add.title') }}
                        @endif
                    </h1>
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
                        <h3 class="kt-card-title">
                            @if(isset($id))
                                {{ __('messages.agent_edit.form_title') }}
                            @else
                                {{ __('messages.agent_add.form_title') }}
                            @endif
                        </h3>
                    </div>
                    <div class="kt-card-content">
                        <form method="post" action="{{ isset($id) ? route('admin.users.update', $id) : route('admin.users.save') }}" id="form" class="space-y-6">
                            @csrf
                            @if(isset($id))
                                @method('PUT')
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="kt-form-input">
                                    <label class="kt-form-label">{{ __('messages.agent_add.name') }}</label>
                                    <input class="kt-input" type="text" name="name" value="{{ $user->name ?? '' }}" maxlength="20" id="agency_name">
                                </div>
                                
                                @if(auth()->guard('admin')->user()->id == 1)
                                <div class="kt-form-input">
                                    <label class="kt-form-label">{{ __('messages.agent_add.channel') }}</label>
                                    <select class="kt-select" id="channel_id" name="channel_id">
                                        <option value="0">{{ __('messages.general.select') }}</option>
                                        @foreach($channels ?? [] as $v)
                                            <option value="{{ $v['channel_id'] }}" {{ isset($user) && $user->channel_id == $v['channel_id'] ? 'selected' : '' }}>
                                                {{ $v['channel_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                
                                <div class="kt-form-input">
                                    <label class="kt-form-label">{{ __('messages.agent_add.level') }}</label>
                                    <select class="kt-select" id="level_id" name="level_id">
                                        <option value="0">{{ __('messages.general.select') }}</option>
                                        @foreach($level ?? [] as $v)
                                            @if($v['id'] != 4)
                                                <option value="{{ $v['id'] }}" 
                                                    data-level-name="{{ $v['level_name'] ?? '' }}"
                                                    data-min-amount="{{ $v['mini_amount'] ?? 0 }}"
                                                    {{ isset($user) && $user->level_id == $v['id'] ? 'selected' : '' }}>
                                                    {{ $v['level_name'] ?? '' }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Agent costs table will be loaded here -->
                            <div id="costs-container" class="hidden">
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.agent_add.package') }}</th>
                                                <th>{{ __('messages.agent_add.retail_price') }}</th>
                                                <th>{{ __('messages.agent_add.your_cost') }}</th>
                                                <th>{{ __('messages.agent_add.agent_cost') }}</th>
                                                <th>{{ __('messages.agent_add.your_profit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="costs-table-body">
                                            <!-- Will be populated by AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if(auth()->guard('admin')->user()->id == 1)
                            <!-- Entry barriers for super admin -->
                            <div class="kt-form-group">
                                <label class="kt-form-label">{{ __('messages.agent_add.barriers') }}</label>
                                <div class="kt-table-responsive">
                                    <table class="kt-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.agent_add.gold_threshold') }}</th>
                                                <th>{{ __('messages.agent_add.silver_threshold') }}</th>
                                                <th>{{ __('messages.agent_add.bronze_threshold') }}</th>
                                                <th>{{ __('messages.agent_add.custom_threshold') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="kt-input w-full" type="text" name="barriersList[0]" value="" maxlength="6" 
                                                        onkeyup="onlyNumber(this, 1)">
                                                </td>
                                                <td>
                                                    <input class="kt-input w-full" type="text" name="barriersList[1]" value="" maxlength="6" 
                                                        onkeyup="onlyNumber(this, 1)">
                                                </td>
                                                <td>
                                                    <input class="kt-input w-full" type="text" name="barriersList[2]" value="" maxlength="6" 
                                                        onkeyup="onlyNumber(this, 1)">
                                                </td>
                                                <td>
                                                    <input class="kt-input w-full" type="text" name="barriersList[3]" value="" maxlength="6" 
                                                        onkeyup="onlyNumber(this, 1)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            
                            <div class="kt-form-group">
                                <label class="kt-form-label">{{ __('messages.agent_add.remark') }}</label>
                                <textarea class="kt-textarea" rows="3" name="remark" maxlength="128">{{ $user->remark ?? '' }}</textarea>
                            </div>
                            
                            <div class="kt-form-group">
                                <div class="font-medium mb-2">{{ __('messages.agent_add.available_balance') }}: 
                                    <span class="text-warning">{{ number_format(auth()->guard('admin')->user()->balance, 2) }}</span>
                                </div>
                                
                                <label class="kt-form-label">{{ __('messages.agent_add.recharge') }}</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <input class="kt-input" type="text" name="balance" value="{{ $user->balance ?? '' }}" 
                                            id="balance" onkeyup="onlyNumber(this, 2)" maxlength="8">
                                    </div>
                                    <div>
                                        <span class="inline-block py-2" id="need"></span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(auth()->guard('admin')->user()->id == 1 || 
                                (auth()->guard('admin')->user()->level_id == 3 && auth()->guard('admin')->user()->type == 2))
                            <div class="kt-form-group">
                                <label class="kt-form-label">{{ __('messages.agent_add.type') }}</label>
                                <div class="flex gap-4">
                                    <label class="kt-radio-input">
                                        <input type="radio" name="type" value="1" {{ isset($user) && $user->type == 1 ? 'checked' : '' }}>
                                        <span>{{ __('messages.agent_add.general_type') }}</span>
                                    </label>
                                    <label class="kt-radio-input">
                                        <input type="radio" name="type" value="2" {{ !isset($user) || (isset($user) && $user->type == 2) ? 'checked' : '' }}>
                                        <span>{{ __('messages.agent_add.enhance_type') }}</span>
                                    </label>
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex justify-center gap-4">
                                <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                                    @if(isset($id))
                                        {{ __('messages.agent_edit.submit') }}
                                    @else
                                        {{ __('messages.agent_add.submit') }}
                                    @endif
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
// Show/hide costs table based on level selection
$(document).ready(function() {
    $('#level_id').change(function() {
        const levelId = $(this).val();
        if (levelId > 0) {
            // Load costs via AJAX
            $.ajax({
                url: "{{ route('admin.users.info') }}",
                type: "GET",
                data: {level_id: levelId},
                success: function(response) {
                    $('#costs-container').removeClass('hidden');
                    $('#costs-table-body').html(response);
                    
                    // Update minimum amount message
                    const minAmount = $('option:selected', '#level_id').attr('data-min-amount');
                    const levelName = $('option:selected', '#level_id').attr('data-level-name');
                    $('#need').html("{{ __('messages.agent_add.min_amount_message') }}".replace(':amount', minAmount).replace(':level', levelName));
                }
            });
        } else {
            $('#costs-container').addClass('hidden');
            $('#need').html('');
        }
    });
    
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