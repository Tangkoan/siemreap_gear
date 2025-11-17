{{-- 
    កូដនេះ នឹងបូកសរុបការទិញទំនិញ (Purchase) + ការចំណាយផ្សេងៗ (Expenses) + ការកាត់ស្តុក (Stock Loss) 
--}}

<h3 class="text-xl font-semibold mb-2 text-red-600">របាយការណ៍ចំណាយ (Expenses)</h3>
<table class="w-full text-left text-sm">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="p-2">កាលបរិច្ឆេទ</th>
            <th class="p-2">ប្រភេទ</th>
            <th class="p-2">ការពិពណ៌នា</th>
            <th class="p-2 text-right">ទឹកប្រាក់</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalExpenses = 0;
        @endphp

        {{-- 1. បង្ហាញការទិញទំនិញ (Purchases) --}}
        @foreach($purchase_details as $item)
            @php $totalExpenses += $item->total; @endphp
            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="p-2">{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-M-Y') }}</td>
                <td class="p-2"><span class="font-semibold text-defalut">ការទិញទំនិញ (Purchase)</span></td>
                <td class="p-2 text-defalut">
                    {{ $item->product->product_name ?? 'N/A' }} ({{ $item->quantity }} x ${{ $item->unitcost }})
                </td>
                <td class="p-2 text-right text-red-500">-${{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach

        {{-- 2. បង្ហាញការចំណាយផ្សេងៗ (Other Expenses - ឥឡូវរួមទាំង Salary) --}}
        @foreach($other_expenses as $item)
            @php $totalExpenses += $item->amount; @endphp
            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="p-2">{{ \Carbon\Carbon::parse($item->expense_date)->format('d-M-Y') }}</td>
                <td class="p-2">
                    {{-- 🟢 UPGRADE: បង្ហាញឈ្មោះ Category (Salary, ថ្លៃភ្លើង...) --}}
                    <span class="font-semibold text-primary">{{ $item->category->name ?? 'Other Expense' }}</span>
                </td>
                <td class="p-2 text-defalut">{{ $item->description }}</td>
                <td class="p-2 text-right text-red-500">-${{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach

        {{-- 3. បង្ហាញ Stock Adjustments (Loss / Return) --}}
        @foreach($stock_adjustments as $item)
            @php
                $itemCost = $item->quantity * ($item->product->buying_price ?? 0);
                if ($item->type === 'clear_stock') {
                    $totalExpenses += $itemCost;
                } elseif ($item->type === 'purchase_return') {
                    $totalExpenses -= $itemCost;
                }
            @endphp

            @if($item->type === 'clear_stock')
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                    <td class="p-2"><span class="font-semibold text-defalut">កាត់ស្តុក (Stock Loss)</span></td>
                    <td class="p-2 text-defalut">Clear {{ $item->quantity }} {{ $item->product->product_name ?? '' }}</td>
                    <td class="p-2 text-right text-red-500">-${{ number_format($itemCost, 2) }}</td>
                </tr>
            @elseif($item->type === 'purchase_return')
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                    <td class="p-2"><span class="font-semibold text-green-500">ទិញចូលវិញ (Purchase Return)</span></td>
                    <td class="p-2 text-defalut">Return {{ $item->quantity }} {{ $item->product->product_name ?? '' }}</td>
                    <td class="p-2 text-right text-green-500">+${{ number_format($itemCost, 2) }}</td>
                </tr>
            @endif
        @endforeach

        @if($totalExpenses == 0)
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">មិនមានទិន្នន័យចំណាយសម្រាប់រយៈពេលនេះទេ។</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="font-bold text-defalut text-lg">
            <td colspan="3" class="p-2 text-right">ចំណាយសរុប (Net Expenses):</td>
            <td class="p-2 text-right text-red-600">-${{ number_format($totalExpenses, 2) }}</td>
        </tr>
    </tfoot>
</table>