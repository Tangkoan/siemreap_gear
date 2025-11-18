{{-- ✅ កូដកែសម្រួលសម្រាប់ _expense_table.blade.php --}}
<table class="w-full text-left text-sm">
    <tbody>
        @php
            // 1. បង្កើត Collection សម្រាប់ Expense ទាំងអស់
            $expenseItems = collect();
            
            // 2. បញ្ចូល Purchases
            if(isset($purchase_details)) {
                foreach ($purchase_details as $item) {
                    $expenseItems->push(['date' => $item->purchase->purchase_date, 'type' => 'Purchase', 'item' => $item]);
                }
            }
            // 3. បញ្ចូល Other Expenses (ទឹកភ្លើង...)
            if(isset($other_expenses)) {
                foreach ($other_expenses as $item) {
                    $expenseItems->push(['date' => $item->expense_date, 'type' => 'Other Expense', 'item' => $item]);
                }
            }
            // 4. ✅ បញ្ចូល Payrolls (ប្រាក់ខែ) - នេះជាส่วนដែលបាត់
            if(isset($payrolls)) {
                foreach ($payrolls as $item) {
                    $expenseItems->push(['date' => $item->payment_date, 'type' => 'Payroll', 'item' => $item]);
                }
            }
            // 5. ✅ បញ្ចូល Stock Adjustments (កាត់ស្តុក/ទិញចូលវិញ)
            if(isset($stock_adjustments)) {
                foreach ($stock_adjustments->where('type', 'clear_stock') as $item) {
                    $expenseItems->push(['date' => $item->created_at, 'type' => 'Clear Stock', 'item' => $item]);
                }
                foreach ($stock_adjustments->where('type', 'purchase_return') as $item) {
                    $expenseItems->push(['date' => $item->created_at, 'type' => 'Purchase Return', 'item' => $item]);
                }
            }
            // 6. ✅ តម្រៀប (Sort) ទាំងអស់តាមកាលបរិច្ឆេទ
            $sortedExpenses = $expenseItems->sortBy('date');
        @endphp

        {{-- 7. ✅ Loop លើ Collection ដែលបានតម្រៀបរួច --}}
        @forelse ($sortedExpenses as $expense)
            
            {{-- Purchase Row --}}
            @if ($expense['type'] == 'Purchase')
                @php $item = $expense['item']; @endphp
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-M-Y') }}</td>
                    <td class="p-2"><span class="font-semibold text-defalut">Purchase</span></td>
                    <td class="p-2 text-defalut">
                        {{ $item->product->product_name ?? 'N/A' }} ({{ $item->quantity }} x ${{ number_format($item->unitcost, 2) }})
                    </td>
                    <td class="p-2 text-right text-red-500">-${{ number_format($item->total, 2) }}</td>
                </tr>

            {{-- Other Expense Row --}}
            @elseif ($expense['type'] == 'Other Expense')
                @php $item = $expense['item']; @endphp
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->expense_date)->format('d-M-Y') }}</td>
                    <td class="p-2">
                        <span class="font-semibold text-primary">{{ $item->category->name ?? 'Other Expense' }}</span>
                    </td>
                    <td class="p-2 text-defalut">{{ $item->description }}</td>
                    <td class="p-2 text-right text-red-500">-${{ number_format($item->amount, 2) }}</td>
                </tr>

            {{-- ✅ PAYROLL ROW (ដែលបាត់) --}}
            @elseif ($expense['type'] == 'Payroll')
                @php $item = $expense['item']; @endphp
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->payment_date)->format('d-M-Y') }}</td>
                    <td class="p-2">
                        <span class="font-semibold text-primary">Payroll</span>
                    </td>
                    <td class="p-2 text-defalut">Payroll for : {{ $item->employee->name ?? '' }} ({{ $item->month_year }})</td>
                    <td class="p-2 text-right text-red-500">-${{ number_format($item->net_salary, 2) }}</td>
                </tr>

            {{-- Stock Loss Row --}}
            @elseif ($expense['type'] == 'Clear Stock')
                @php $item = $expense['item']; $itemCost = $item->quantity * ($item->product->buying_price ?? 0); @endphp
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                    <td class="p-2"><span class="font-semibold text-defalut">កាត់ស្តុក (Stock Loss)</span></td>
                    <td class="p-2 text-defalut">Clear {{ $item->quantity }} {{ $item->product->product_name ?? '' }}</td>
                    <td class="p-2 text-right text-red-500">-${{ number_format($itemCost, 2) }}</td>
                </tr>

            {{-- Purchase Return Row (Credit) --}}
            @elseif ($expense['type'] == 'Purchase Return')
                @php $item = $expense['item']; $itemCost = $item->quantity * ($item->product->buying_price ?? 0); @endphp
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                    <td class="p-2"><span class="font-semibold text-green-500">Purchase Return (Credit)</span></td>
                    <td class="p-2 text-defalut">Return {{ $item->quantity }} {{ $item->product->product_name ?? '' }}</td>
                    <td class="p-2 text-right text-green-500">+${{ number_format($itemCost, 2) }}</td>
                </tr>
            @endif

        @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    No expense data available
                </td>
            </tr>
        @endforelse
    </tbody>
</table>