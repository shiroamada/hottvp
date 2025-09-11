<div class="kt-table-responsive grow">
    @php
        // Safely get the first item from $lists whether it's indexed or associative
        $first = null;
        if (isset($lists) && is_array($lists) && count($lists) > 0) {
            $first = array_values($lists)[0]; // works for both numeric and associative
        }

        $hasAssort = isset($first['assort']) && is_array($first['assort']) && count($first['assort']) > 0;
        $isEditable = (int)($level_id ?? 0) === 8;
        $colCount = $isEditable ? 6 : 5; // header & colspan must match
    @endphp

    <table class="kt-table">
        <thead>
            <tr>
                <th>{{ __('messages.agent_add.package') }}</th>
                <th>{{ __('messages.agent_add.retail_price') }}</th>
                <th>{{ __('messages.agent_add.your_cost') }}</th>
                <th>{{ __('messages.agent_add.agent_cost') }}</th>
                <th>{{ __('messages.agent_add.your_profit') }}</th>
                @if($isEditable)
                    <th>{{ __('adminUser.a_cost_limit') }}</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @if($hasAssort)
                @foreach($first['assort'] as $key => $assortName)
                    <tr>
                        <td>
                            {{ $assortName }}
                            <input type="hidden" name="assort[]" value="{{ $assortName }}">
                        </td>

                        <td>{{ number_format($prices[$key] ?? 0, 2) }}</td>

                        <td class="your-cost">{{ number_format($first['own'][$key] ?? 0, 2) }}</td>

                        <td>
                            @if($isEditable)
                                <input
                                    type="text"
                                    class="kt-input w-full agent-cost-input"
                                    name="agency[]"
                                    value=""
                                    onkeyup="onlyNumber(this, 2)"
                                >
                                <input type="hidden" name="own[]" value="{{ $first['own'][$key] ?? 0 }}">
                            @else
                                <input
                                    type="text"
                                    class="kt-input w-full"
                                    name="agency[]"
                                    value="{{ number_format($first['choice'][$key] ?? 0, 2) }}"
                                    readonly
                                >
                            @endif
                        </td>

                        <td class="your-profit">{{ number_format($first['diff'][$key] ?? 0, 2) }}</td>

                        @if($isEditable)
                            <td>
                                {{ number_format($first['choice'][$key] ?? 0, 2) }}
                                <input type="hidden" name="choice[]" value="{{ $first['choice'][$key] ?? 0 }}">
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ $colCount }}" class="text-center">
                        {{ __('messages.agent_add.no_cost_data') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
