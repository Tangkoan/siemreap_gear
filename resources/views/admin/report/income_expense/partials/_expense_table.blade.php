@php
    $expenses = collect();
    foreach ($purchase_details as $item) {
        $expenses->push(['date' => $item->purchase->purchase_date, 'type' => 'Purchase', 'item' => $item]);
    }
    foreach ($other_expenses as $item) {
        $expenses->push(['date' => $item->date, 'type' => 'Other Expense', 'item' => $item]);
    }
    // Filter stock adjustments for expenses (clear_stock) and expense reductions (purchase_return)
    foreach ($stock_adjustments as $item) {
        if ($item->type == 'clear_stock' || $item->type == 'purchase_return') {
            $expenses->push(['date' => $item->created_at, 'type' => $item->type, 'item' => $item]);
        }
    }
    $sortedExpenses = $expenses->sortBy('date');
@endphp

@forelse ($sortedExpenses as $expense)
    @if ($expense['type'] == 'Purchase')
        @php $item = $expense['item']; @endphp
        <tr>
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $item->product->product_name }}</div>
                <div class="text-xs text-gray-500">Purchase: {{ $item->purchase->purchase_no }}</div>
            </td>
            <td class="p-3 text-center">{{ $item->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($item->unitcost, 2) }}</td>
            <td class="p-3 text-right font-semibold text-red-500">${{ number_format($item->total, 2) }}</td>
        </tr>
    @elseif ($expense['type'] == 'Other Expense')
        @php $item = $expense['item']; @endphp
        <tr>
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $item->details }}</div>
                <div class="text-xs text-gray-500">Other Expense</div>
            </td>
            <td class="p-3 text-center">-</td>
            <td class="p-3 text-right">-</td>
            <td class="p-3 text-right font-semibold text-red-500">${{ number_format($item->amount, 2) }}</td>
        </tr>
    @elseif ($expense['type'] == 'clear_stock')
        @php $item = $expense['item']; @endphp
        <tr class="bg-red-50 dark:bg-red-500/20">
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $item->product->product_name }}</div>
                <div class="text-xs text-gray-500">Cleared Stock (Loss)</div>
            </td>
            <td class="p-3 text-center">{{ $item->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($item->product->buying_price, 2) }}</td>
            <td class="p-3 text-right font-semibold text-red-500">${{ number_format($item->quantity * $item->product->buying_price, 2) }}</td>
        </tr>
    {{-- ✅ START: ADD THIS BLOCK FOR PURCHASE RETURNS --}}
    @elseif ($expense['type'] == 'purchase_return')
        @php $item = $expense['item']; @endphp
        <tr class="bg-green-50 dark:bg-green-500/20 hover:bg-green-100 dark:hover:bg-green-500/30">
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $item->product->product_name }}</div>
                <div class="text-xs text-gray-500">Purchase Return (Debit)</div>
            </td>
            <td class="p-3 text-center">{{ $item->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($item->product->buying_price, 2) }}</td>
            <td class="p-3 text-right font-semibold text-green-600">-${{ number_format($item->quantity * $item->product->buying_price, 2) }}</td>
        </tr>
    @endif
    {{-- ✅ END: ADD THIS BLOCK FOR PURCHASE RETURNS --}}
@empty
    <tr>
        <td colspan="5" class="text-center p-6 text-gray-500">No expense data available for this period.</td>
    </tr>
@endforelse