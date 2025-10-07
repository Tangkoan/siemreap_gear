@php
    // Combine sales and sale returns into a single collection
    $incomeItems = collect();

    foreach ($sales_details as $item) {
        $incomeItems->push([
            'date' => $item->order->order_date,
            'type' => 'Sale',
            'item' => $item
        ]);
    }

    foreach ($stock_adjustments->where('type', 'sale_return') as $item) {
        $incomeItems->push([
            'date' => $item->created_at,
            'type' => 'Sale Return',
            'item' => $item
        ]);
    }

    // Sort the combined items by date
    $sortedIncome = $incomeItems->sortBy('date');
@endphp

@forelse ($sortedIncome as $income)
    @if ($income['type'] == 'Sale')
        @php $item = $income['item']; @endphp
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="p-3">{{ \Carbon\Carbon::parse($income['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-medium">{{ $item->product->product_name ?? 'N/A' }}</div>
                <div class="text-xs text-gray-500">Invoice: {{ $item->order->invoice_no ?? 'N/A' }}</div>
            </td>
            <td class="p-3 text-center">{{ $item->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($item->unitcost, 2) }}</td>
            <td class="p-3 text-right font-semibold text-green-600">${{ number_format($item->total, 2) }}</td>
        </tr>
    {{-- ✅ START: ADD THIS BLOCK FOR SALE RETURNS --}}
    @elseif ($income['type'] == 'Sale Return')
        @php $item = $income['item']; @endphp
        <tr class="bg-red-50 dark:bg-red-500/20 hover:bg-red-100 dark:hover:bg-red-500/30">
            <td class="p-3">{{ \Carbon\Carbon::parse($income['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-medium">{{ $item->product->product_name ?? 'N/A' }}</div>
                <div class="text-xs text-gray-500">Sale Return (Credit)</div>
            </td>
            <td class="p-3 text-center">{{ $item->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($item->product->selling_price ?? 0, 2) }}</td>
            <td class="p-3 text-right font-semibold text-red-500">-${{ number_format($item->quantity * ($item->product->selling_price ?? 0), 2) }}</td>
        </tr>
    @endif
    {{-- ✅ END: ADD THIS BLOCK FOR SALE RETURNS --}}
@empty
    <tr>
        <td colspan="5" class="text-center p-6 text-gray-500">{{ __('messages.no_sales_data_abailable') }}</td>
    </tr>
@endforelse