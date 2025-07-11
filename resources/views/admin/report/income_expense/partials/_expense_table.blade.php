{{-- Purchase Expenses --}}
@foreach ($purchase_details as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3">{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-m-Y') }}</td>
        <td class="p-3">
            <div class="font-medium">{{ $item->product->product_name ?? 'N/A' }}</div>
            <div class="text-xs text-gray-500">Purchase (Invoice: {{ $item->purchase->invoice_no ?? 'N/A' }})</div>
        </td>
        <td class="p-3 text-center">{{ $item->quantity }}</td>
        <td class="p-3 text-right">${{ number_format($item->purchase_price, 2) }}</td>
        <td class="p-3 text-right font-semibold text-red-600">${{ number_format($item->total, 2) }}</td>
    </tr>
@endforeach

{{-- Other Expenses --}}
@foreach ($other_expenses as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
        <td class="p-3">
            <div class="font-medium">{{ $item->details }}</div>
            <div class="text-xs text-gray-500">Other Expense</div>
        </td>
        <td class="p-3 text-center">-</td>
        <td class="p-3 text-right">-</td>
        <td class="p-3 text-right font-semibold text-red-600">${{ number_format($item->amount, 2) }}</td>
    </tr>
@endforeach

@if($purchase_details->isEmpty() && $other_expenses->isEmpty())
    <tr>
        <td colspan="5" class="text-center p-6 text-gray-500">No expense data available for this period.</td>
    </tr>
@endif