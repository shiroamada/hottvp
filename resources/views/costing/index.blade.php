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
                        <i class="ki-duotone ki-file-document me-2"></i>
                        {{ __('messages.costing.title') }}
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
                    <div class="kt-card-content">
                        <div class="grid">
                            <div class="kt-scrollable-x-auto">
                                <table class="kt-table table-auto kt-table-border">
                                    <thead>
                                    <tr>
                                        <th class="text-start">{{ __('messages.costing.table.license_code_type') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.retail_price') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.your_cost') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.diamond_agent_cost') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.gold_agent_cost') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.silver_agent_cost') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.bronze_agent_cost') }}</th>
                                        <th class="text-start">{{ __('messages.costing.table.customized_minimum_cost') }}</th>
                                        <th class="text-end">{{ __('messages.costing.table.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($costingData as $item)
                                            <tr data-id="{{ $item['id'] }}">
                                                <td>{{ $item['type'] }}</td>
                                                <td><input type="number" step="0.01" name="retail_price" value="{{ $item['retail_price'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="your_cost" value="{{ $item['your_cost'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="diamond_agent_cost" value="{{ $item['diamond_agent_cost'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="gold_agent_cost" value="{{ $item['gold_agent_cost'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="silver_agent_cost" value="{{ $item['silver_agent_cost'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="bronze_agent_cost" value="{{ $item['bronze_agent_cost'] }}" class="kt-input" disabled></td>
                                                <td><input type="number" step="0.01" name="customized_minimum_cost" value="{{ $item['customized_minimum_cost'] }}" class="kt-input" disabled></td>
                                                <td class="text-end">
                                                    <button type="button" class="kt-btn kt-btn-sm kt-btn-primary edit-btn">EDIT</button>
                                                    <button type="submit" class="kt-btn kt-btn-sm kt-btn-success save-btn" style="display:none;">SAVE</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                row.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });
                this.style.display = 'none';
                row.querySelector('.save-btn').style.display = 'inline-block';
            });
        });

        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.dataset.id;
                const data = {
                    _token: '{{ csrf_token() }}',
                    assort_id: id,
                    retail_price: row.querySelector('input[name="retail_price"]').value,
                    your_cost: row.querySelector('input[name="your_cost"]').value,
                    diamond_agent_cost: row.querySelector('input[name="diamond_agent_cost"]').value,
                    gold_agent_cost: row.querySelector('input[name="gold_agent_cost"]').value,
                    silver_agent_cost: row.querySelector('input[name="silver_agent_cost"]').value,
                    bronze_agent_cost: row.querySelector('input[name="bronze_agent_cost"]').value,
                    customized_minimum_cost: row.querySelector('input[name="customized_minimum_cost"]').value,
                };

                fetch('{{ route('costing.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message);
                        row.querySelectorAll('input').forEach(input => {
                            input.disabled = true;
                        });
                        this.style.display = 'none';
                        row.querySelector('.edit-btn').style.display = 'inline-block';
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving.');
                });
            });
        });
    });
</script>
@endpush