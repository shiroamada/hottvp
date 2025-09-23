@extends('layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">

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
                        {{ __('messages.pre_generated_codes.create.title') }}
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
                        <h3 class="kt-card-title">{{ __('messages.pre_generated_codes.create.title') }}</h3>
                    </div>

                    @if (session('success') || session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                @if (session('success'))
                                    toastr.success("{{ session('success') }}");
                                @endif
                                @if (session('error'))
                                    toastr.error("{{ session('error') }}");
                                @endif
                            });
                        </script>
                    @endif

                    <form action="{{ route('admin.pre_generated_codes.store') }}" method="POST">
                        @csrf
                        <div class="kt-card-content grid gap-5">
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label for="codes" class="kt-form-label max-w-56">{{ __('messages.pre_generated_codes.create.codes_label') }}</label>
                                <textarea name="codes" id="codes" class="kt-textarea grow @error('codes') is-invalid @enderror" rows="10" required placeholder="{{ __('messages.pre_generated_codes.create.codes_placeholder') }}" style="height: 15rem;">{{ old('codes') }}</textarea>
                                @error('codes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label for="type" class="kt-form-label max-w-56">{{ __('messages.pre_generated_codes.create.type_label') }}</label>
                                <select name="type" id="type" class="kt-select grow @error('type') is-invalid @enderror" required>
                                    @foreach ($types as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label for="vendor" class="kt-form-label max-w-56">{{ __('messages.pre_generated_codes.create.vendor_label') }}</label>
                                <select name="vendor" id="vendor" class="kt-select grow @error('vendor') is-invalid @enderror" required>
                                    @foreach ($vendors as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label for="remark" class="kt-form-label max-w-56">{{ __('messages.pre_generated_codes.create.remark_label') }}</label>
                                <textarea name="remark" id="remark" class="kt-textarea grow @error('remark') is-invalid @enderror" rows="3">{{ old('remark') }}</textarea>
                                @error('remark')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="flex justify-end gap-3">
                                <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.pre_generated_codes.create.submit') }}</button>
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
