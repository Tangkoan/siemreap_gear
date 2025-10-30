@forelse ($purchases as $key => $item)
    <tr class="border-b border-primary ">
        <td class="px-6 py-4">{{ $purchases->firstItem() + $key }}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</td>
        <td class="px-6 py-4 font-medium text-defalut">{{ $item->invoice_no }}</td>
        <td class="px-6 py-4">{{ $item->supplier->name ?? 'N/A' }}</td>
        <td class="px-6 py-4 text-right font-medium">${{ number_format($item->total, 2) }}</td>
        

        <td class="px-6 py-4 text-center">
            @if($item->purchase_status == 'complete')
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">complete</span>
            @else
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-red-900 dark:text-red-300">pending</span>
            @endif
        </td>

        <td class="px-6 py-4 text-center">
            <button type="button" class="view-details-btn  text-cyan-600 dark:text-cyan-400" data-purchase-id="{{ $item->id }}">
                {{ __('messages.details') }}
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center p-8 text-slate-500">{{ __(key: 'messages.no_purchases') }}</td>
    </tr>
@endforelse