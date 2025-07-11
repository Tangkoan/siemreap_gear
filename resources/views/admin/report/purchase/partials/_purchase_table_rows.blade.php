@forelse ($purchases as $key => $item)
    <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50">
        <td class="px-6 py-4">{{ $purchases->firstItem() + $key }}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</td>
        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $item->invoice_no }}</td>
        <td class="px-6 py-4">{{ $item->supplier->name ?? 'N/A' }}</td>
        <td class="px-6 py-4 text-right font-medium">${{ number_format($item->total, 2) }}</td>
        <td class="px-6 py-4 text-center">
            @if($item->payment_status == 'Paid')
                <span class="badge-success">Paid</span>
            @else
                <span class="badge-danger">Due</span>
            @endif
        </td>
        <td class="px-6 py-4 text-center">
            <button type="button" class="view-details-btn font-semibold text-cyan-600 dark:text-cyan-400 hover:underline" data-purchase-id="{{ $item->id }}">
                View
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center p-8 text-slate-500">No purchases found for this period.</td>
    </tr>
@endforelse