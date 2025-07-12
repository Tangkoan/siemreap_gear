<tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50">
    <td class="px-6 py-4">{{ $orders->firstItem() + $key }}</td>
    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->order_date)->format('d M Y') }}</td>
    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $item->invoice_no }}</td>
    <td class="px-6 py-4">{{ $item->customer->name ?? 'N/A' }}</td>
    <td class="px-6 py-4 text-right font-medium">${{ number_format($item->total, 2) }}</td>
    <td class="px-6 py-4 text-center">
        @if($item->order_status == 'complete')
            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">complete</span>
        @else
            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-red-900 dark:text-red-300">pending</span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        <button type="button" class="view-details-btn font-semibold text-blue-600 dark:text-blue-400 hover:underline" data-order-id="{{ $item->id }}">
            View
        </button>
    </td>
</tr>