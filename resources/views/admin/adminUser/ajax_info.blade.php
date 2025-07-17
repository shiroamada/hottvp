@if(isset($lists) && count($lists) > 0)
    @foreach($lists as $key => $item)
        <tr>
            <td>{{ $item['assort'][0] }}</td>
            <td>{{ number_format($prices[$key], 2) }}</td>
            <td>{{ number_format($item['own'][0], 2) }}</td>
            <td>
                <input type="text" class="kt-input w-full" name="agency[]" value="{{ $item['choice'][0] }}" readonly>
                <input type="hidden" name="assort[]" value="{{ $item['assort'][0] }}">
                <input type="hidden" name="own[]" value="{{ $item['own'][0] }}">
                <input type="hidden" name="choice[]" value="{{ $item['choice'][0] }}">
            </td>
            <td>{{ number_format($item['diff'][0], 2) }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">{{ __('messages.agent_add.no_cost_data') }}</td>
    </tr>
@endif 