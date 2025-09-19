@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
   <!-- Header -->
   <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
    <!-- Container -->
    <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
    
     <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
      <i class="ki-filled ki-menu"></i>
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
                                                        <div x-data="{ open: false }" class="relative">
                                                            <span @mouseover="open = true" @mouseout="open = false" class="cursor-pointer">
                                                                {{ mb_substr($v['remark'], 0, 10) }}...
                                                            </span>
                                                            <div x-show="open"
                                                                 x-transition
                                                                 class="absolute z-10 w-64 p-2 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-gray-800 rounded-lg shadow-lg">
                                                                {{ $v['remark'] }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{ $v['remark'] }}
                                                    @endif
                                                </td>

                                                <td>{{ $v['created_at'] }}</td>

                                                <!-- Actions dropdown (details/summary with purple left stripe highlight) -->
                                                <td class="text-end">
                                                  <details class="relative group" data-row="{{ $v['id'] }}">
                                                    <summary
                                                      class="kt-btn kt-btn-sm kt-btn-primary select-none cursor-pointer list-none"
                                                      aria-haspopup="menu"
                                                      aria-expanded="false"
                                                    >
                                                      {{ __('messages.agent_list.table.action') }}
                                                    </summary>

                                                    <div
                                                      role="menu"
                                                      class="absolute right-0 mt-2 rounded-md shadow-md border border-border bg-card text-card-foreground py-2 z-50 min-w-[150px]"
                                                      style="background-color: var(--background-card);"
                                                    >
                                                      @if(auth()->guard('admin')->user()->id != 2)
                                                        <a class="block px-4 py-2 text-sm" role="menuitem"
                                                           href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                          {{ __('messages.agent_list.check') }}
                                                        </a>

                                                        @if($v['is_cancel'] == 0)
                                                          <a class="block px-4 py-2 text-sm" role="menuitem"
                                                             href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                            {{ __('messages.agent_list.recharge') }}
                                                          </a>
                                                        @endif

                                                        @if($v['is_cancel'] != 2)
                                                          

                                                          <!-- <form action="{{ route('admin.users.delete', $v->id) }}" method="POST"
                                                                onsubmit="return confirm('{{ __('messages.general.delete_confirm') }}');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm" role="menuitem">
                                                              {{ __('messages.agent_list.delete') }}
                                                            </button>
                                                          </form> -->

                                                          <a class="block px-4 py-2 text-sm" role="menuitem"
                                                             href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                            {{ __('messages.agent_list.lower_agent') }}
                                                          </a>
                                                        @endif
                                                      @else
                                                        <a class="block px-4 py-2 text-sm" role="menuitem"
                                                           href="{{ route('admin.users.look', ['id' => $v['id']]) }}">
                                                          {{ __('messages.agent_list.check_cost') }}
                                                        </a>
                                                        <a class="block px-4 py-2 text-sm" role="menuitem"
                                                           href="{{ route('admin.users.lower', ['id' => $v['id']]) }}">
                                                          {{ __('messages.agent_list.lower') }}
                                                        </a>
                                                        <a class="block px-4 py-2 text-sm" role="menuitem"
                                                           href="{{ route('admin.users.check', ['id' => $v['id']]) }}">
                                                          {{ __('messages.agent_list.check') }}
                                                        </a>
                                                        <a class="block px-4 py-2 text-sm" role="menuitem"
                                                           href="{{ route('admin.users.recharge', ['id' => $v['id']]) }}">
                                                          {{ __('messages.agent_list.recharge') }}
                                                        </a>
                                                      @endif
                                                    </div>
                                                  </details>
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
        <!-- End Container -->
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
    --hover-background: #f3f4f6;      /* light hover bg */
    --hover-text: #111827;            /* near-black text */
    --active-color: #6366f1;          /* indigo-500 (purple-ish) */
    --active-color-dark: #818cf8;     /* indigo-400 (for dark mode stripe) */
  }
  .dark {
    --background-card: #1f2937;       /* gray-800 */
    --hover-background: #374151;      /* gray-700 */
    --hover-text: #f9fafb;            /* gray-50 */
    --active-color: #818cf8;          /* lighter for contrast in dark */
    --active-color-dark: #a5b4fc;     /* even lighter stripe in dark */
  }

  /* Hide default ▶ marker on <summary> */
  details > summary::-webkit-details-marker { display: none; }
  details > summary { outline: none; }

  /* Base item style in the dropdown */
  [role="menu"] a,
  [role="menu"] button {
    position: relative;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
    overflow: hidden; /* keep the stripe snug */
  }

  /* Hover/Focus states + purple left stripe */
  [role="menu"] a:hover,
  [role="menu"] a:focus,
  [role="menu"] button:hover,
  [role="menu"] button:focus {
    background-color: var(--hover-background);
    color: var(--hover-text);
    border-left-color: var(--active-color);
    padding-left: calc(1rem - 3px); /* compensate for border-left */
    outline: none;
  }

  /* The visual stripe itself */
  [role="menu"] a:hover::before,
  [role="menu"] a:focus::before,
  [role="menu"] button:hover::before,
  [role="menu"] button:focus::before {
    content: "";
    position: absolute;
    left: 0; top: 0;
    height: 100%; width: 3px;
    background-color: var(--active-color);
  }

  /* Active press feedback */
  [role="menu"] a:active,
  [role="menu"] button:active {
    background-color: var(--active-color);
    color: #fff;
  }

  /* Dark-mode: use lighter purple for the stripe & border */
  .dark [role="menu"] a:hover,
  .dark [role="menu"] a:focus,
  .dark [role="menu"] button:hover,
  .dark [role="menu"] button:focus {
    border-left-color: var(--active-color-dark);
  }
  .dark [role="menu"] a:hover::before,
  .dark [role="menu"] a:focus::before,
  .dark [role="menu"] button:hover::before,
  .dark [role="menu"] button:focus::before {
    background-color: var(--active-color-dark);
  }
</style>
@endpush

@push('scripts')
<script>
  // Ensure only one dropdown (details) is open at a time
  document.addEventListener('toggle', function (e) {
    if (e.target.tagName.toLowerCase() !== 'details') return;
    const isOpen = e.target.hasAttribute('open');
    if (isOpen) {
      document.querySelectorAll('td details[open]').forEach(d => {
        if (d !== e.target) d.removeAttribute('open');
      });
    }
    const sum = e.target.querySelector('summary');
    if (sum) sum.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  }, true);

  // Close open dropdowns when clicking outside
  document.addEventListener('click', function (e) {
    document.querySelectorAll('td details[open]').forEach(d => {
      if (!d.contains(e.target)) {
        d.removeAttribute('open');
        const sum = d.querySelector('summary');
        if (sum) sum.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Initialize aria-expanded = false
  document.querySelectorAll('td details > summary').forEach(s => s.setAttribute('aria-expanded', 'false'));
</script>
@endpush
