@extends('admin.layouts.master')

@section('content')
<!-- Page -->
  <!-- Base -->
  <div class="flex grow">
    <!-- Header (mobile) -->
    <header class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-muted h-(--header-height)" id="header">
      <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.dashboard') }}">
          <img class="dark:hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray.svg"/>
          <img class="hidden min-h-[30px]" src="/assets/media/app/mini-logo-gray-dark.svg"/>
        </a>
        <button class="kt-btn kt-btn-icon kt-btn-ghost -me-2" data-kt-drawer-toggle="#sidebar">
          <i class="ki-filled ki-menu"></i>
        </button>
      </div>
    </header>
    <!-- End Header -->

    @include('layouts/partials/_sidebar')

    <!-- Wrapper -->
    <div class="flex flex-col lg:flex-row grow pt-(--header-height) lg:pt-0">
      <!-- Main -->
      <div class="flex flex-col grow items-stretch rounded-xl bg-background border border-input lg:ms-(--sidebar-width) mt-0 lg:mt-[15px] m-[15px]">
        <div class="flex flex-col grow kt-scrollable-y-auto [--kt-scrollbar-width:auto] pt-5" id="scrollable_content">
          <main class="grow" role="content">
            <!-- Toolbar -->
            <div class="pb-5">
              <div class="kt-container-fixed flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center flex-wrap gap-1 lg:gap-5">
                  <h1 class="font-medium text-lg text-mono">
                    <i class="ki-filled ki-abstract-28 me-2"></i>
                    {{ __('adminUser.lower_agent') }}
                  </h1>
                </div>
                <div class="flex items-center flex-wrap gap-1.5 lg:gap-3.5">
                  {{-- Optional actions can go here --}}
                </div>
              </div>
            </div>
            <!-- End Toolbar -->

            <!-- Container -->
            <div class="kt-container-fixed">
              <div class="grid gap-5 lg:gap-7.5">
                <div class="kt-card kt-card-grid min-w-full">
                  <!-- Card Header: Filters + Totals -->
                  <div class="kt-card-header flex-wrap gap-4 justify-between">
                    <form
                      name="admin_list_sea"
                      class="form-search flex flex-wrap items-end gap-2"
                      method="get"
                      action="{{ route('admin.users.lower', ['id' => $id]) }}"
                    >
                      @csrf
                      <div class="flex flex-col gap-1">
                        <label for="date2" class="text-sm text-muted-foreground">{{ __('general.range') }}</label>
                        <input
                          class="kt-input w-60"
                          placeholder="{{ __('general.range') }}"
                          type="text"
                          name="date2"
                          id="date2"
                          value="{{ $condition['date2'] ?? '' }}"
                          autocomplete="off"
                        />
                        <input type="hidden" id="startTime" name="startTime"/>
                        <input type="hidden" id="endTime" name="endTime"/>
                      </div>
                      <div class="flex gap-2.5">
                        <button class="kt-btn kt-btn-primary" type="submit" id="submitBtn">
                          {{ __('general.search') }}
                        </button>
                      </div>
                    </form>

                    <!-- Totals -->
                    <div class="flex flex-wrap items-center gap-4">
                      <div class="text-sm">
                        <span class="text-muted-foreground">{{ __('adminUser.all_num') }}:</span>
                        <span class="font-medium">{{ $total_person }}</span>
                      </div>
                      @isset($group)
                        @foreach($group as $item => $value)
                          @if ($item == 4)
                            <div class="text-sm">
                              <span class="text-muted-foreground">{{ __('adminUser.diamond_num') }}:</span>
                              <span class="font-medium">{{ $value }}</span>
                            </div>
                          @elseif ($item == 5)
                            <div class="text-sm">
                              <span class="text-muted-foreground">{{ __('adminUser.gold_num') }}:</span>
                              <span class="font-medium">{{ $value }}</span>
                            </div>
                          @elseif ($item == 6)
                            <div class="text-sm">
                              <span class="text-muted-foreground">{{ __('adminUser.silver_num') }}:</span>
                              <span class="font-medium">{{ $value }}</span>
                            </div>
                          @elseif ($item == 7)
                            <div class="text-sm">
                              <span class="text-muted-foreground">{{ __('adminUser.copper_num') }}:</span>
                              <span class="font-medium">{{ $value }}</span>
                            </div>
                          @elseif ($item == 8)
                            <div class="text-sm">
                              <span class="text-muted-foreground">{{ __('adminUser.defined_num') }}:</span>
                              <span class="font-medium">{{ $value }}</span>
                            </div>
                          @endif
                        @endforeach
                      @endisset
                    </div>
                  <!-- </div> -->
                  <!-- End Card Header -->

                  <!-- Card Content -->
                  <div class="kt-card-content">
                    <div class="grid">
                      <div class="kt-scrollable-x-auto ">
                        <table class="kt-table table-auto kt-table-border">
                          <thead>
                            <tr>
                              <th class="text-start">{{ __('adminUser.id') }}</th>
                              <th class="text-start">{{ __('adminUser.agency_account') }}</th>
                              <th class="text-start">{{ __('adminUser.agency_level') }}</th>
                              <th class="text-start">{{ __('adminUser.balance') }}</th>
                              <th class="text-start">{{ __('adminUser.get_profit') }}</th>
                              <th class="text-start">{{ __('adminUser.recharge') }}</th>
                              <th class="text-start">{{ __('general.create') }}</th>
                            </tr>
                          </thead>
                          <tbody id="list-content">
                            @isset($lists)
                              @foreach($lists as $v)
                                <tr>
                                  <td>{{ $v['id'] }}</td>

                                  @if($v['is_cancel'] != 0)
                                    <td class="text-muted truncate" title="{{ $v['account'] ?? '' }}">
                                      {{ $v['account'] ?? '' }} {{ __('general.is_del') }}
                                    </td>
                                  @else
                                    <td class="truncate" title="{{ $v['account'] ?? '' }}">
                                      {{ $v['account'] ?? '' }}
                                    </td>
                                  @endif

                                  <td>
                                    {{ $v->levels->level_name ?? '' }}
                                    @if(auth('admin')->user()->level_id <= 3 && ($v->type ?? null) == 2)
                                      <span class="badge badge-primary ms-1">Pro</span>
                                    @endif
                                  </td>

                                  <td>{{ number_format($v['balance'], 2) }}</td>
                                  <td>{{ $v['total_balance'] }}</td>
                                  <td>{{ $v['recharge'] }}</td>
                                  <td>{{ $v['created_at'] }}</td>
                                </tr>
                              @endforeach
                            @endisset
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <!-- Pagination -->
                    <div id="pages" class="mt-5">
                      {{ $lists->links() }}
                    </div>
                  </div>
                  <!-- End Card Content -->
                </div>
              </div>
            </div>
            <!-- End Container -->
          </main>
        </div>
      </div>
      <!-- End Main -->
    </div>
    <!-- End Wrapper -->
  </div>
  <!-- End Base -->
<!-- End Page -->
@endsection

@push('styles')
<style>
  :root {
    --background-card: #ffffff;
  }
  .dark {
    --background-card: #1f2937;
  }
</style>
@endpush

@push('scripts')
<script src="{{ asset('public/admin/js/laydate/laydate.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // If you keep laydate, parse range into hidden fields before submit.
    if (window.laydate) {
      laydate.render({
        elem: '#date2',
        range: true,
        trigger: 'click'
      });
    }

    // Split "YYYY-MM-DD - YYYY-MM-DD" into hidden start/end
    (function () {
      const form = document.querySelector('form[name="admin_list_sea"]');
      if (!form) return;

      form.addEventListener('submit', function () {
        const range = document.getElementById('date2')?.value || '';
        const [start, end] = range.split(/\s*-\s*/);
        if (start) document.getElementById('startTime').value = start.trim();
        if (end) document.getElementById('endTime').value = end.trim();
      });
    })();
  });
</script>
@endpush