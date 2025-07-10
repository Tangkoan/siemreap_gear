@foreach ($purchases as $key => $item)
<tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
    <td class="p-3">{{ $key + 1 }}</td>
    <td class="p-3">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d-m-Y') }}</td>
    <td class="p-3">{{ $item->invoice_no }}</td>
    <td class="p-3">{{ $item->supplier->name ?? 'N/A' }}</td>
    <td class="p-3 text-right font-medium">${{ number_format($item->total, 2) }}</td>
    <td class="p-3 text-center">
        @if ($item->payment_status == 'Paid')
            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Paid</span>
        @else
            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Due</span>
        @endif
    </td>
    <td class="p-3 text-center">
        <button type="button" 
                class="view-details-btn px-3 py-1 bg-blue-500 text-white rounded-md text-xs hover:bg-blue-600"
                data-purchase-id="{{ $item->id }}" 
                data-invoice="{{ $item->invoice_no }}">
            View
        </button>
    </td>
</tr>
@endforeach