<table>
    <thead>
        <tr>
            <th colspan="5" style="font-size: 16px; font-weight: bold;">Income & Expense Report ({{ $summary['formattedDate'] }})</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Summary</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Revenue:</td>
            <td>{{ $summary['total_revenue'] }}</td>
        </tr>
        <tr>
            <td>Total Expenses:</td>
            <td>{{ $summary['total_expenses'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Profit / Loss:</td>
            <td style="font-weight: bold;">{{ $summary['profit_or_loss'] }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </tbody>

    {{-- 1. INCOME DETAILS (ត្រឹមត្រូវហើយ) --}}
    <thead>
        <tr>
            <th colspan="5" style="font-size: 14px; font-weight: bold;">Income Details</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: left;">Date</th>
            <th style="font-weight: bold;">Details</th>
            <th style="font-weight: bold; text-align: center;">Qty</th>
            <th style="font-weight: bold; text-align: right;">Price</th>
            <th style="font-weight: bold; text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $incomeItems = collect();
            if(isset($sales_details)) {
                foreach ($sales_details as $item) {
                    $incomeItems->push(['date' => $item->order->order_date, 'type' => 'Sale', 'item' => $item]);
                }
            }
            if(isset($stock_adjustments)) {
                foreach ($stock_adjustments->where('type', 'sale_return') as $item) {
                    $incomeItems->push(['date' => $item->created_at, 'type' => 'Sale Return', 'item' => $item]);
                }
            }
            $sortedIncome = $incomeItems->sortBy('date');
        @endphp
        
        @forelse ($sortedIncome as $income)
            @if ($income['type'] == 'Sale')
                @php $item = $income['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->order->order_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br>(Invoice: {{ $item->order->invoice_no ?? 'N/A' }})</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unitcost, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
                </tr>
            @elseif ($income['type'] == 'Sale Return')
                @php $item = $income['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br>(Sale Return - Credit)</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->product->selling_price ?? 0, 2) }}</td>
                    <td style="text-align: right;">-{{ number_format($item->quantity * ($item->product->selling_price ?? 0), 2) }}</td>
                </tr>
            @endif
        @empty
            <tr><td colspan="5" style="text-align: center;">No income data available</td></tr>
        @endforelse
        <tr>
            <td colspan="5"></td>
        </tr>
    </tbody>

    {{-- 2. EXPENSE DETAILS (‼️ នេះជាផ្នែកដែលបានកែ) --}}
    <thead>
        <tr>
            <th colspan="5" style="font-size: 14px; font-weight: bold;">Expense Details</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: left;">Date</th>
            <th style="font-weight: bold;">Details</th>
            <th style="font-weight: bold; text-align: center;">Qty</th>
            <th style="font-weight: bold; text-align: right;">Price</th>
            <th style="font-weight: bold; text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            // ✅ 1. បង្កើត Collection សម្រាប់ Expense ទាំងអស់
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
            // 4. ✅ បញ្ចូល Payrolls (ប្រាក់ខែ)
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

        {{-- ✅ 7. Loop លើ Collection ដែលបានតម្រៀបរួច --}}
        @forelse ($sortedExpenses as $expense)
            @if ($expense['type'] == 'Purchase')
                @php $item = $expense['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br>(Purchase: {{ $item->purchase->purchase_no ?? 'N/A' }})</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unitcost, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
                </tr>
            
            @elseif ($expense['type'] == 'Other Expense')
                @php $item = $expense['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->expense_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->description }}<br>({{ $item->category->name ?? 'Other Expense' }})</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
                </tr>

            @elseif ($expense['type'] == 'Payroll')
                @php $item = $expense['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->payment_date)->format('d-m-Y') }}</td>
                    <td>Salary {{ $item->employee->name ?? '' }}<br>({{ $item->month_year }})</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">{{ number_format($item->net_salary, 2) }}</td>
                </tr>

            @elseif ($expense['type'] == 'Clear Stock')
                @php $item = $expense['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br>(Cleared Stock - Loss)</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->product->buying_price ?? 0, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->quantity * ($item->product->buying_price ?? 0), 2) }}</td>
                </tr>
            @elseif ($expense['type'] == 'Purchase Return')
                @php $item = $expense['item']; @endphp
                <tr>
                    <td style="text-align: left;">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br>(Purchase Return - Debit)</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->product->buying_price ?? 0, 2) }}</td>
                    <td style="text-align: right;">-{{ number_format($item->quantity * ($item->product->buying_price ?? 0), 2) }}</td>
                </tr>
            @endif
        @empty
            <tr><td colspan="5" style="text-align: center;">No expense data available</td></tr>
        @endforelse
    </tbody>
</table>