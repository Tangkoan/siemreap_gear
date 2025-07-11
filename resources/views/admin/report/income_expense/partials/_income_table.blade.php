@forelse ($sales_details as $item)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="p-3">{{ \Carbon\Carbon::parse($item->order->order_date)->format('d-m-Y') }}</td>
        <td class="p-3">
            <div class="font-medium">{{ $item->product->product_name ?? 'N/A' }}</div>
            <div class="text-xs text-gray-500">Invoice: {{ $item->order->invoice_no ?? 'N/A' }}</div>
        </td>
        <td class="p-3 text-center">{{ $item->quantity }}</td>
        <td class="p-3 text-right">${{ number_format($item->unitcost, 2) }}</td>
        <td class="p-3 text-right font-semibold text-green-600">${{ number_format($item->total, 2) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center p-6 text-gray-500">No sales data available for this period.</td>
    </tr>
@endforelse