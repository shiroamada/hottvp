<div>
    <label class="kt-form-label block font-medium mb-2">{{ __('messages.agent_add.costs_table') }}</label>
    <div class="kt-table-responsive grow">
        <table class="kt-table">
            <thead>
                <tr>
                    <th>{{ __('messages.agent_add.package') }}</th>
                    <th>{{ __('messages.agent_add.retail_price') }}</th>
                    <th>{{ __('messages.agent_add.your_cost') }}</th>
                    <th>{{ __('messages.agent_add.agent_cost') }}</th>
                    <th>{{ __('messages.agent_add.your_profit') }}</th>
                    @if($level_id == 8)
                        <th>{{ __('adminUser.a_cost_limit') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(isset($lists) && count($lists) > 0 && isset($lists[0]['assort']))
                    @foreach($lists[0]['assort'] as $key => $assortName)
                        <tr>
                            <td>
                                {{ $assortName }}
                                <input type="hidden" name="assort[]" value="{{ $assortName }}">
                            </td>
                            <td>{{ number_format($prices[$key] ?? 0, 2) }}</td>
                            <td class="your-cost">{{ number_format($lists[0]['own'][$key] ?? 0, 2) }}</td>
                            <td>
                                @if($level_id == 8)
                                    <input type="text" class="kt-input w-full agent-cost-input" name="agency[]" value="" onkeyup="onlyNumber(this, 2)">
                                    <input type="hidden" name="own[]" value="{{ $lists[0]['own'][$key] ?? 0 }}">
                                @else
                                    <input type="text" class="kt-input w-full" name="agency[]" value="{{ number_format($lists[0]['choice'][$key] ?? 0, 2) }}" readonly>
                                @endif
                            </td>
                            <td class="your-profit">{{ number_format($lists[0]['diff'][$key] ?? 0, 2) }}</td>
                            @if($level_id == 8)
                                <td>
                                    {{ number_format($lists[0]['choice'][$key] ?? 0, 2) }}
                                    <input type="hidden" name="choice[]" value="{{ $lists[0]['choice'][$key] ?? 0 }}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">{{ __('messages.agent_add.no_cost_data') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
