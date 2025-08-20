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
            <div class="grid gap-5   lg:gap-7.5 xl:w-[38.75rem] mx-auto">
                <div class="kt-card kt-card-grid">
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
                        <form method="post" action="{{ isset($id) ? route('admin.users.update', $id) : route('admin.users.save') }}" id="form" onsubmit="return false;">
                            <div class="kt-card-content grid gap-5 m-3">
                                @csrf
                                @if(isset($id))
                                    @method('PUT')
                                @endif
    
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="kt-form-label max-w-56">{{ __('messages.agent_add.name') }}</label>
                                    <input class="kt-input grow" placeholder="{{ __('messages.agent_list.agent_name_placeholder') }}" type="text" name="name" value="{{ $user->name ?? '' }}" maxlength="20" id="agency_name">
                                </div>
                                
                                @if(auth()->guard('admin')->user()->id == 1)
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="kt-form-label max-w-56">{{ __('messages.agent_add.channel') }}</label>
                                    <select class="kt-select grow" id="channel_id" name="channel_id">
                                        <option value="0">{{ __('messages.general.select') }}</option>
                                        @foreach($channels ?? [] as $v)
                                            <option value="{{ $v['channel_id'] }}" {{ isset($user) && $user->channel_id == $v['channel_id'] ? 'selected' : '' }}>
                                                {{ $v['channel_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="kt-form-label max-w-56">{{ __('messages.agent_add.level') }}</label>
                                    <select class="kt-select grow" id="level_id" name="level_id">
                                        <option value="0">{{ __('messages.agent_create.select_level') }}</option>
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
                                
                                <!-- Agent costs table will be loaded here -->
                                <div id="costs-container">
                                    <!-- Content will be loaded by AJAX -->
                                </div>
                                
                                @if(auth()->guard('admin')->user()->id == 1)
                                <!-- Entry barriers for super admin -->
                                <div>
                                    <label class="kt-form-label block font-medium mb-2">{{ __('messages.agent_add.barriers') }}</label>
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
                                
                                <div>
                                    <label class="kt-form-label block font-medium mb-2">{{ __('messages.agent_add.remark') }}</label>
                                    <textarea class="kt-textarea grow" rows="3" name="remark" maxlength="128">{{ $user->remark ?? '' }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="kt-form-label block font-medium mb-2">{{ __('messages.agent_add.recharge') }}</label>
                                    <input class="kt-input" type="text" name="balance" value="{{ $user->balance ?? '' }}" 
                                        id="balance" onkeyup="onlyNumber(this, 2)" maxlength="8">
                                    <p class="text-sm text-muted-foreground mt-2">{{ __('messages.agent_add.available_balance') }}: 
                                        <span class="font-bold text-primary">{{ number_format(auth()->guard('admin')->user()->balance, 2) }}</span>
                                    </p>
                                    <p class="text-sm text-muted-foreground mt-1" id="need"></p>
                                </div>
                                
                                @if(auth()->guard('admin')->user()->id == 1 || 
                                    (auth()->guard('admin')->user()->level_id == 3 && auth()->guard('admin')->user()->type == 2))
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="kt-form-label max-w-56">{{ __('messages.agent_add.type') }}</label>
                                    <div class="flex gap-5 grow">
                                        <div class="flex items-center gap-2">
                                            <input class="kt-radio" type="radio" name="type" value="1" {{ isset($user) && $user->type == 1 ? 'checked' : '' }}>
                                            <label for="permission_normal">{{ __('messages.agent_add.general_type') }}</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input class="kt-radio" type="radio" name="type" value="2" {{ !isset($user) || (isset($user) && $user->type == 2) ? 'checked' : '' }}>
                                            <label for="permission_enhanced">{{ __('messages.agent_add.enhance_type') }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex justify-end gap-3">
                                    <button type="button" class="kt-btn kt-btn-outline" onclick="history.go(-1);">
                                        {{ __('messages.general.return') }}
                                    </button>
                                    <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                                        @if(isset($id))
                                            {{ __('messages.agent_edit.submit') }}
                                        @else
                                            {{ __('messages.agent_add.submit') }}
                                        @endif
                                    </button>
                                </div>
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
window.addEventListener('load', function () {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    jQuery(document).ready(function() {
        jQuery('#level_id').change(function() {
            const levelId = jQuery(this).val();
            if (levelId > 0) {
                jQuery.ajax({
                    url: "{{ route('admin.users.info') }}",
                    type: "GET",
                    data: {level_id: levelId},
                    success: function(response) {
                        jQuery('#costs-container').removeClass('hidden').html(response);
                        const minAmount = jQuery('option:selected', '#level_id').attr('data-min-amount');
                        const levelName = jQuery('option:selected', '#level_id').attr('data-level-name');
                        jQuery('#need').html("{{ __('messages.agent_add.min_amount_message') }}".replace(':amount', minAmount).replace(':level', levelName));
                    }
                });
            } else {
                jQuery('#costs-container').addClass('hidden').html('');
            }
        });
        
        jQuery('#form').submit(function(e) {
            e.preventDefault();
            jQuery('#submitBtn').prop('disabled', true);
            const balance = jQuery('#balance').val().replace(/,/g, '');
            jQuery('#balance').val(balance);
            
            jQuery.ajax({
                url: jQuery(this).attr('action'),
                type: jQuery(this).attr('method'),
                data: jQuery(this).serialize(),
                headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'), 'X-Requested-With': 'XMLHttpRequest'},
                success: function(result) {
                    if (result.code !== 0) {
                        alert(result.msg);
                        jQuery('#submitBtn').prop('disabled', false);
                        return false;
                    }
                    alert(result.msg);
                    if (result.redirect) {
                        location.href = "{{ route('admin.users.detail') }}?id=" + result.id;
                    }
                },
                error: function(xhr) {
                    jQuery('#submitBtn').prop('disabled', false);
                    if (xhr.status === 422) {
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

        // Live profit calculation for custom agent costs
        jQuery('#costs-container').on('keyup', '.agent-cost-input', function() {
            const agentCost = parseFloat(jQuery(this).val().replace(/,/g, '')) || 0;
            const yourCost = parseFloat(jQuery(this).closest('tr').find('.your-cost').text().replace(/,/g, '')) || 0;
            const profit = agentCost - yourCost;
            jQuery(this).closest('tr').find('.your-profit').text(profit.toFixed(2));
        });
    });

    window.onlyNumber = function(obj, type) {
        let value = jQuery(obj).val().replace(/[^\d.]/g, '');
        if (type === 1) {
            value = value.replace(/\./g, '');
        } else if (type === 2) {
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            if (parts.length === 2 && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }
            if (parts[0].length > 3) {
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                value = parts.length === 2 ? parts[0] + '.' + parts[1] : parts[0];
            }
        }
        jQuery(obj).val(value);
    };
});
</script>
@endpush