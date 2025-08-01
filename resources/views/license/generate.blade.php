@extends('layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
     <a href="html/demo6.html">
      <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg"/>
      <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg"/>
     </a>
     <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
      <i class="ki-filled ki-menu">
      </i>
     </button>
    </div>
    <!-- End of Container -->
   </header>
   <!-- End of Header -->
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
                        {{ __('messages.license_generate.title') }}
                    </h1>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5 xl:w-[38.75rem] mx-auto">
                <div class="kt-card pb-2.5">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">{{ __('messages.license_generate.title') }}</h3>
                    </div>
                    <form>
                        <div class="kt-card-content grid gap-5">
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.license_generate.type') }}</label>
                                <select class="kt-select grow" id="code_type">
                                    <option value="">{{ __('messages.license_generate.choose_code_type') }}</option>
                                    <option value="30_day">{{ __('messages.license_generate.license_30_day') }}</option>
                                    <option value="90_day">{{ __('messages.license_generate.license_90_day') }}</option>
                                    <option value="180_day">{{ __('messages.license_generate.license_180_day') }}</option>
                                    <option value="365_day">{{ __('messages.license_generate.license_365_day') }}</option>
                                    <option value="1_day">{{ __('messages.license_generate.license_1_day') }}</option>
                                    <option value="7_day">{{ __('messages.license_generate.license_7_day') }}</option>
                                </select>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.license_generate.quantity') }}</label>
                                <input class="kt-input grow" id="code_number" type="number">
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.license_generate.remarks') }}</label>
                                <textarea class="kt-input grow" id="remarks" rows="3"></textarea>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="kt-form-label max-w-56">{{ __('messages.license_generate.need_hotcoin') }}</label>
                                <div class="grow">
                                    <input class="kt-input" id="need_hotcoin" type="text" value="0" readonly>
                                </div>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 justify-end">
                                <div class="text-right">
                                    <p>HOTCOIN</p>
                                    <p class="font-bold text-primary">710.50</p>
                                    <p>{{ __('messages.license_generate.hotcoin_balance') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-center gap-3 mt-3">
                                <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.license_generate.batch_generate') }}</button>
                                <button class="kt-btn kt-btn-danger" type="reset">{{ __('messages.license_generate.reset') }}</button>
                                <button class="kt-btn kt-btn-warning" type="button">{{ __('messages.license_generate.return') }}</button>
                            </div>
                        </div>
                    </form>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeTypeSelect = document.getElementById('code_type');
        const codeNumberInput = document.getElementById('code_number');
        const needHotcoinInput = document.getElementById('need_hotcoin');
        
        const prices = {
            '30_day': 7.50,
            '90_day': 15.00,
            '180_day': 30.00,
            '365_day': 60.00,
            '1_day': 1.00,
            '7_day': 3.00
        };
        
        function calculateHotcoin() {
            const selectedType = codeTypeSelect.value;
            const number = parseInt(codeNumberInput.value) || 0;
            
            if (selectedType && prices[selectedType]) {
                const totalPrice = prices[selectedType] * number;
                needHotcoinInput.value = totalPrice.toFixed(2);
            } else {
                needHotcoinInput.value = '0';
            }
        }
        
        codeTypeSelect.addEventListener('change', calculateHotcoin);
        codeNumberInput.addEventListener('input', calculateHotcoin);
    });
</script>
@endsection