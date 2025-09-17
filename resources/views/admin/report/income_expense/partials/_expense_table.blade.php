@php
    
    $expenses = collect();
    foreach ($purchase_details as $item) {
        $expenses->push(['date' => $item->purchase->purchase_date, 'type' => 'Purchase', 'item' => $item]);
    }
    foreach ($other_expenses as $item) {
        $expenses->push(['date' => $item->date, 'type' => 'Other Expense', 'item' => $item]);
    }
    foreach ($stock_adjustments as $item) {
        if ($item->type == 'clear_stock' || $item->type == 'purchase_return') {
            $expenses->push(['date' => $item->created_at, 'type' => $item->type, 'item' => $item]);
        }
    }
    $sortedExpenses = $expenses->sortBy('date');
@endphp

@forelse ($sortedExpenses as $expense)
    @if ($expense['type'] == 'Purchase')
        <tr>
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $expense['item']->product->product_name }}</div>
                <div class="text-xs text-gray-500">Purchase: {{ $expense['item']->purchase->purchase_no }}</div>
            </td>
            <td class="p-3 text-center">{{ $expense['item']->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($expense['item']->unitcost, 2) }}</td>
            <td class="p-3 text-right text-red-500">${{ number_format($expense['item']->total, 2) }}</td>
        </tr>
    @elseif ($expense['type'] == 'Other Expense')
        <tr>
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $expense['item']->details }}</div>
                <div class="text-xs text-gray-500">Other Expense</div>
            </td>
            <td class="p-3 text-center">-</td>
            <td class="p-3 text-right">-</td>
            <td class="p-3 text-right text-red-500">${{ number_format($expense['item']->amount, 2) }}</td>
        </tr>
    @elseif ($expense['type'] == 'clear_stock')
        <tr class="bg-red-50 dark:bg-red-500/20 ">
            <td class="p-3">{{ \Carbon\Carbon::parse($expense['date'])->format('d-m-Y') }}</td>
            <td class="p-3">
                <div class="font-semibold">{{ $expense['item']->product->product_name }}</div>
                <div class="text-xs text-gray-500">Cleared Stock (Loss)</div>
            </td>
            <td class="p-3 text-center">{{ $expense['item']->quantity }}</td>
            <td class="p-3 text-right">${{ number_format($expense['item']->product->buying_price, 2) }}</td>
            <td class="p-3 text-right text-red-500">${{ number_format($expense['item']->quantity * $expense['item']->product->buying_price, 2) }}</td>
        </tr>
    
    @endif
@empty
    <tr>
        <td colspan="5" class="text-center p-6 text-gray-500">No expense data available for this period.</td>
    </tr>
@endforelse