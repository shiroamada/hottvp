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
                                <div x-data="{
                                    costingData: [
                                        {
                                            id: 1,
                                            type: '1-day license code',
                                            retail_price: '1.00',
                                            your_cost: '1.00',
                                            diamond_agent_cost: '1.00',
                                            gold_agent_cost: '1.00',
                                            silver_agent_cost: '1.00',
                                            bronze_agent_cost: '1.00',
                                            customized_minimum_cost: '1.00'
                                        },
                                        {
                                            id: 2,
                                            type: '7-day license code',
                                            retail_price: '10.00',
                                            your_cost: '3.00',
                                            diamond_agent_cost: '4.00',
                                            gold_agent_cost: '5.00',
                                            silver_agent_cost: '6.00',
                                            bronze_agent_cost: '7.00',
                                            customized_minimum_cost: '8.00'
                                        },
                                        {
                                            id: 3,
                                            type: '30-day license code',
                                            retail_price: '25.00',
                                            your_cost: '7.50',
                                            diamond_agent_cost: '11.25',
                                            gold_agent_cost: '12.50',
                                            silver_agent_cost: '15.00',
                                            bronze_agent_cost: '18.00',
                                            customized_minimum_cost: '19.00'
                                        },
                                        {
                                            id: 4,
                                            type: '90-day license code',
                                            retail_price: '50.00',
                                            your_cost: '15.00',
                                            diamond_agent_cost: '22.50',
                                            gold_agent_cost: '25.00',
                                            silver_agent_cost: '30.00',
                                            bronze_agent_cost: '36.00',
                                            customized_minimum_cost: '37.00'
                                        },
                                        {
                                            id: 5,
                                            type: '180-day license code',
                                            retail_price: '95.00',
                                            your_cost: '30.00',
                                            diamond_agent_cost: '45.00',
                                            gold_agent_cost: '50.00',
                                            silver_agent_cost: '60.00',
                                            bronze_agent_cost: '72.00',
                                            customized_minimum_cost: '73.00'
                                        },
                                        {
                                            id: 6,
                                            type: '365-day license code',
                                            retail_price: '180.00',
                                            your_cost: '60.00',
                                            diamond_agent_cost: '81.00',
                                            gold_agent_cost: '90.00',
                                            silver_agent_cost: '108.00',
                                            bronze_agent_cost: '130.00',
                                            customized_minimum_cost: '131.00'
                                        }
                                    ],
                                    editingIndex: null,
                                    editForm: {},
                                    
                                    startEditing(index) {
                                        this.editingIndex = index;
                                        this.editForm = { ...this.costingData[index] };
                                    },
                                    
                                    cancelEditing() {
                                        this.editingIndex = null;
                                        this.editForm = {};
                                    },
                                    
                                    saveChanges(index) {
                                        // Update the data
                                        this.costingData[index] = { ...this.editForm };
                                        
                                        // In a real application, you would send an AJAX request here to update the database
                                        // Example:
                                        // fetch('/api/costing/update/' + this.costingData[index].id, {
                                        //     method: 'POST',
                                        //     headers: {
                                        //         'Content-Type': 'application/json',
                                        //         'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                                        //     },
                                        //     body: JSON.stringify(this.editForm)
                                        // }).then(response => response.json())
                                        //   .then(data => {
                                        //     // Handle response
                                        //   });
                                        
                                        // Reset editing state
                                        this.cancelEditing();
                                    }
                                }">
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
                                            <template x-for="(item, index) in costingData" :key="item.id">
                                                <tr>
                                                    <td x-text="item.type"></td>
                                                    <template x-if="editingIndex !== index">
                                                        <template>
                                                            <td x-text="item.retail_price"></td>
                                                            <td x-text="item.your_cost"></td>
                                                            <td x-text="item.diamond_agent_cost"></td>
                                                            <td x-text="item.gold_agent_cost"></td>
                                                            <td x-text="item.silver_agent_cost"></td>
                                                            <td x-text="item.bronze_agent_cost"></td>
                                                            <td x-text="item.customized_minimum_cost"></td>
                                                            <td class="text-end">
                                                                <button class="kt-btn kt-btn-sm kt-btn-primary" @click="startEditing(index)">EDIT</button>
                                                            </td>
                                                        </template>
                                                    </template>
                                                    <template x-if="editingIndex === index">
                                                        <template>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.retail_price"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.your_cost"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.diamond_agent_cost"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.gold_agent_cost"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.silver_agent_cost"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.bronze_agent_cost"></td>
                                                            <td><input type="text" class="kt-input w-full" x-model="editForm.customized_minimum_cost"></td>
                                                            <td class="text-end">
                                                                <button class="kt-btn kt-btn-sm kt-btn-success" @click="saveChanges(index)">SAVE</button>
                                                                <button class="kt-btn kt-btn-sm kt-btn-danger" @click="cancelEditing()">CANCEL</button>
                                                            </td>
                                                        </template>
                                                    </template>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
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