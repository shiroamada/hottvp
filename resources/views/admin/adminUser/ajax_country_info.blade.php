@php
    $packages = [
        'daysOneList' => '1 Day',
        'daysSevenList' => '7 Days',
        'daysThirtyList' => '30 Days',
        'daysNinetyList' => '90 Days',
        'daysEightyList' => '180 Days',
        'yearsList' => '365 Days',
    ];
    $levels = [
        'Our Cost', 'Diamond', 'Medal', 'Silver', 'Copper', 'Defined'
    ];
@endphp
<div>
    <label class="kt-form-label block font-medium mb-2">{{ __('messages.agent_add.costs_table') }}</label>
    <div class="kt-table-responsive grow">
        <h3 class="kt-card-title text-lg font-medium mb-3">Agent Costs</h3>
        <table class="kt-table">
            <thead>
                <tr>
                    <th>Level</th>
                    @foreach($packages as $pkg_name)
                        <th>{{ $pkg_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($levels as $level_index => $level_name)
                    <tr>
                        <td>{{ $level_name }}</td>
                        @foreach($packages as $pkg_key => $pkg_name)
                            <td>
                                <input class="kt-input w-full" type="text" name="{{ $pkg_key }}[{{ $level_index }}]" value="{{ $data[$level_index][$pkg_key] ?? '' }}">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="kt-card-title text-lg font-medium mt-6 mb-3">Retail Prices</h3>
        <table class="kt-table">
            <thead>
                <tr>
                    @foreach($packages as $pkg_name)
                        <th>{{ $pkg_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($packages as $pkg_key => $pkg_name)
                        <td>
                            <input class="kt-input w-full" type="text" name="retailList[]" value="">
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>