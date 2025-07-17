@extends('admin.layouts.master')

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
                        <i class="ki-filled ki-abstract-28 me-2"></i>
                        {{ __('messages.agent_list.title') }}
                    </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                    <a href="{{ route('admin.users.create') }}" class="kt-btn kt-btn-primary">{{ __('messages.agent_list.add_new_agent') }}</a>
                </div>
            </div>
            <!-- End of Container -->
        </div>
        <!-- End of Toolbar -->
        <!-- Container -->
        <div class="kt-container-fixed">
            <div class="grid gap-5 lg:gap-7.5">
                <div class="kt-card kt-card-grid min-w-full">
                    <div class="kt-card-header flex-wrap gap-2">
                        <div class="flex flex-wrap gap-2 lg:gap-5">
                            <form name="admin_list_sea" class="form-search flex flex-wrap gap-2" method="get"
                                  action="{{ route('admin.users.index') }}">
                                @csrf
                                <div class="flex">
                                    <input class="kt-input w-40" placeholder="{{ __('messages.agent_list.agent_name_placeholder') }}" 
                                           type="text" name="name" value="{{ $condition['name'] ?? '' }}"/>
                                </div>
                                <div class="flex flex-wrap gap-2.5">
                                    <button class="kt-btn kt-btn-primary" type="submit">{{ __('messages.agent_list.search') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="kt-card-content">
                        <div class="grid">
                            <div class="kt-scrollable-x-auto">
                                <table class="kt-table table-auto kt-table-border">
                                    <thead>
                                    <tr>
                                        <th class="text-start">{{ __('messages.agent_list.table.id') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.agent_name') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.agent_level') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.balance') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.accumulated_profit') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.remark') }}</th>
                                        <th class="text-start">{{ __('messages.agent_list.table.created_time') }}</th>
                                        <th class="text-end">{{ __('messages.agent_list.table.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @isset($lists)
                                        @foreach($lists as $v)
                                            <tr>
                                                <td>{{ $v['id'] }}</td>
                                                @if($v['is_cancel'] != 0)
                                                    <td class="text-muted">{{ $v['name'] ?? "" }} {{ __('messages.general.is_del') }}</td>
                                                @else
                                                    <td>{{ $v['name'] ?? "" }}</td>
                                                @endif
                                                <td>
                                                    {{ $v->levels->level_name ?? "" }}
                                                    @if(auth()->guard('admin')->user()->level_id <= 3)
                                                        @if($v->type == 2)
                                                            <span class="badge badge-primary">Pro</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ number_format($v['balance'], 2) }}</td>
                                                <td>
                                                    <?php
                                                    if (auth()->guard('admin')->user()->id == 1) {
                                                        $where = ["user_id" => $v['id'], 'status' => 0];
                                                    } else {
                                                        $where = ["user_id" => auth()->guard('admin')->user()->id, 'status' => 0, 'type' => 1, 'create_id' => $v['id']];
                                                    }
                                                    $user_lirun = App\Models\Admin\Huobi::query()->where($where)->get();
                                                    $user_pro = 0;
                                                    foreach ($user_lirun as $value) {
                                                        $assort_where = ['user_id' => $parent_id, 'assort_id' => $value['assort_id'], 'level_id' => 3];
                                                        $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                                        $total = isset($assort_level->money) ? bcmul($assort_level->money, $value['number'], 2) : 0;
                                                        $user_pro += $total;
                                                    }
                                                    $profit = App\Repository\Admin\HuobiRepository::levelByRecord($where);
                                                    ?>
                                                    @if(auth()->guard('admin')->user()->id == 1)
                                                        {{ number_format($user_pro, 2) }}
                                                    @else
                                                        {{ number_format($profit, 2) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(mb_strlen($v['remark']) > 10)
                                                        {{ mb_substr($v['remark'], 0, 10) }}...
                                                    @else
                                                        {{ $v['remark'] }}
                                                    @endif
                                                </td>
                                                <td>{{ $v['created_at'] }}</td>
                                                <td class="text-end">
                                                    <div class="relative">
                                                        <button type="button" class="kt-btn kt-btn-sm kt-btn-primary dropdown-toggle" 
                                                                onclick="toggleDropdown({{ $v['id'] }})">
                                                            {{ __('messages.agent_list.table.action') }}
                                                        </button>
                                                        <div id="dropdown-{{ $v['id'] }}" class="dropdown-menu absolute right-0 mt-2 hidden rounded-md shadow-md shadow-[rgba(0,0,0,0.05)] border border-border bg-card text-card-foreground py-2 z-50" style="min-width: 150px; background-color: var(--background-card);">
                                                            @if(auth()->guard('admin')->user()->id != 2)
                                                                <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                                    {{ __('messages.agent_list.check') }}
                                                                </a>
                                                                @if($v['is_cancel'] == 0)
                                                                    <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                                        {{ __('messages.agent_list.recharge') }}
                                                                    </a>
                                                                @endif
                                                                @if($v['is_cancel'] != 2)
                                                                    <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                                        {{ __('messages.agent_list.lower_agent') }}
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.look', ['id' => $v['id']]) }}">
                                                                    {{ __('messages.agent_list.check_cost') }}
                                                                </a>
                                                                <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                                    {{ __('messages.agent_list.lower') }}
                                                                </a>
                                                                <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                                    {{ __('messages.agent_list.check') }}
                                                                </a>
                                                                <a class="block px-4 py-2 text-sm dropdown-item" href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                                    {{ __('messages.agent_list.recharge') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="pages" class="mt-5">
                            {{ $lists->appends(['name' => $lists->name ?? null])->links() }}
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

@push('styles')
<style>
    :root {
        --background-card: #ffffff;
        --hover-background: #f3f4f6;
        --hover-text: #111827;
        --active-color: #6366f1;
        --active-color-dark: #818cf8;
    }
    
    .dark {
        --background-card: #1f2937;
        --hover-background: #374151;
        --hover-text: #f9fafb;
        --active-color: #818cf8;
        --active-color-dark: #a5b4fc;
    }
    
    .dropdown-menu {
        background-color: var(--background-card) !important;
    }
    
    .dropdown-menu a {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .dropdown-menu a:hover,
    .dropdown-menu a:focus,
    .dropdown-menu a.dropdown-item-active {
        background-color: var(--hover-background) !important;
        color: var(--hover-text) !important;
        border-left: 3px solid var(--active-color);
        padding-left: calc(1rem - 3px);
        outline: none;
    }
    
    .dropdown-menu a:active {
        background-color: var(--active-color) !important;
        color: white !important;
    }
    
    /* Visual indicator for the currently hovered/focused item */
    .dropdown-menu a:hover::before,
    .dropdown-menu a:focus::before,
    .dropdown-menu a.dropdown-item-active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background-color: var(--active-color);
    }
    
    /* Make sure the dropdown menu itself has a clear focus outline */
    .dropdown-menu:focus-within {
        outline: 2px solid var(--active-color);
        outline-offset: 2px;
    }
    
    .dark .dropdown-menu a:hover::before,
    .dark .dropdown-menu a:focus::before,
    .dark .dropdown-menu a.dropdown-item-active::before {
        background-color: var(--active-color-dark);
    }
    
    .dark .dropdown-menu a:hover,
    .dark .dropdown-menu a:focus,
    .dark .dropdown-menu a.dropdown-item-active {
        border-left-color: var(--active-color-dark);
    }
</style>
@endpush

@push('scripts')
<script>
    // Delete user functionality if needed
    function deleteUser(url) {
        if (confirm("{{ __('messages.general.delete_confirm') }}")) {
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if (result.code !== 0) {
                        alert(result.msg);
                        return false;
                    }
                    alert(result.msg);
                    if (result.reload) {
                        location.reload();
                    }
                    if (result.redirect) {
                        location.href = '{!! url()->current() !!}';
                    }
                }
            });
        }
    }
    
    // Show/hide dropdown
    function toggleDropdown(id) {
        const dropdown = document.getElementById('dropdown-' + id);
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        
        // Hide all other dropdowns
        allDropdowns.forEach(menu => {
            if (menu.id !== 'dropdown-' + id) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
        
        // Ensure dropdown has proper background
        if (!dropdown.classList.contains('hidden')) {
            // Force solid background color based on current theme
            if (document.documentElement.classList.contains('dark')) {
                dropdown.style.backgroundColor = '#1f2937'; // Dark mode background
            } else {
                dropdown.style.backgroundColor = '#ffffff'; // Light mode background
            }
        }
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
    
    // Initialize dropdowns with proper background colors when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Set CSS variable for dropdown backgrounds
        const root = document.documentElement;
        if (root.classList.contains('dark')) {
            root.style.setProperty('--background-card', '#1f2937');
        } else {
            root.style.setProperty('--background-card', '#ffffff');
        }
        
        // Apply to all existing dropdowns
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            if (root.classList.contains('dark')) {
                dropdown.style.backgroundColor = '#1f2937';
            } else {
                dropdown.style.backgroundColor = '#ffffff';
            }
            
            // Add event listeners for dropdown menu items
            dropdown.querySelectorAll('a').forEach(item => {
                // On hover, add active class
                item.addEventListener('mouseenter', function() {
                    dropdown.querySelectorAll('a').forEach(el => el.classList.remove('dropdown-item-active'));
                    this.classList.add('dropdown-item-active');
                });
                
                // On focus
                item.addEventListener('focus', function() {
                    dropdown.querySelectorAll('a').forEach(el => el.classList.remove('dropdown-item-active'));
                    this.classList.add('dropdown-item-active');
                });
            });
        });
    });
</script>
@endpush
