<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Income & Expense Report</title>
    <style>
        body, table, th, td, h1, p, h3, small {
            font-family: "Khmer OS Battambang", sans-serif; 
            font-size: 11px;
            color: #333;
        }
        /* ... (Style ផ្សេងៗរបស់អ្នកនៅដដែល) ... */
        .container { width: 100%; margin: 0 auto; }
        .report-header { text-align: center; margin-bottom: 20px; }
        .report-header h1 { margin: 0; font-size: 20px; }
        .report-header p { margin: 5px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .summary-table td:first-child { font-weight: bold; width: 70%; }
        .section-header { background-color: #e6e6e6; font-weight: bold; font-size: 14px; padding: 8px; text-align: center; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .income-total { color: #28a745; font-weight: bold; }
        .expense-total { color: #dc3545; font-weight: bold; }
        .credit-total { color: #28a745; font-weight: bold; }
        .profit { color: #28a745; font-weight: bold; }
        .loss { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="report-header">
            <h1>Income & Expense Report</h1>
            <p>{{ $summary['formattedDate'] }}</p>
        </div>

        <h3>Summary</h3>
        <table class="summary-table">
            <tr>
                <td>Total Revenue</td>
                <td class="text-right income-total">${{ $summary['total_revenue'] }}</td>
            </tr>
            <tr>
                <td>Total Expenses</td>
                <td class="text-right expense-total">${{ $summary['total_expenses'] }}</td>
            </tr>
            <tr>
                <td>Profit / Loss</td>
                @php $is_profit = (float)str_replace(',', '', $summary['profit_or_loss']) >= 0; @endphp
                <td class="text-right {{ $is_profit ? 'profit' : 'loss' }}">${{ $summary['profit_or_loss'] }}</td>
            </tr>
        </table>

        {{-- INCOME DETAILS (ត្រឹមត្រូវហើយ) --}}
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
        <table>
             <thead>
                <tr><th colspan="5" class="section-header">Income Details</th></tr>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-center">QTY</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sortedIncome as $income)
                    @if ($income['type'] == 'Sale')
                        @php $item = $income['item']; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->order->order_date)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->product_name ?? 'N/A' }}<br><small>(Invoice: {{ $item->order->invoice_no ?? 'N/A' }})</small></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unitcost, 2) }}</td>
                            <td class="text-right income-total">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @elseif ($income['type'] == 'Sale Return')
                        @php $item = $income['item']; @endphp
                        <tr style="background-color: #fbebeb;">
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->product_name ?? 'N/A' }}<br><small>(Sale Return - Credit)</small></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->product->selling_price ?? 0, 2) }}</td>
                            <td class="text-right expense-total">-${{ number_format($item->quantity * ($item->product->selling_price ?? 0), 2) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="5" class="text-center">No income data available</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- ✅ START: REVISED EXPENSE DETAILS (កូដកែសម្រួល) --}}
        @php
            $expenseItems = collect();
            if(isset($purchase_details)) {
                foreach ($purchase_details as $item) {
                    $expenseItems->push(['date' => $item->purchase->purchase_date, 'type' => 'Purchase', 'item' => $item]);
                }
            }
            if(isset($other_expenses)) {
                foreach ($other_expenses as $item) {
                    $expenseItems->push(['date' => $item->expense_date, 'type' => 'Other Expense', 'item' => $item]);
                }
            }
            if(isset($payrolls)) {
                foreach ($payrolls as $item) {
                    $expenseItems->push(['date' => $item->payment_date, 'type' => 'Payroll', 'item' => $item]);
                }
            }
            if(isset($stock_adjustments)) {
                foreach ($stock_adjustments->where('type', 'clear_stock') as $item) {
                    $expenseItems->push(['date' => $item->created_at, 'type' => 'Clear Stock', 'item' => $item]);
                }
                foreach ($stock_adjustments->where('type', 'purchase_return') as $item) {
                    $expenseItems->push(['date' => $item->created_at, 'type' => 'Purchase Return', 'item' => $item]);
                }
            }
            $sortedExpenses = $expenseItems->sortBy('date');
        @endphp
        <table>
            <thead>
                <tr><th colspan="5" class="section-header">Expense Details</th></tr>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-center">QTY</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sortedExpenses as $expense)
                
                    @if ($expense['type'] == 'Purchase')
                        @php $item = $expense['item']; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->product_name ?? 'N/A' }}<br><small>(Purchase: {{ $item->purchase->purchase_no ?? 'N/A' }})</small></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unitcost, 2) }}</td>
                            <td class="text-right expense-total">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    
                    @elseif ($expense['type'] == 'Other Expense')
                        @php $item = $expense['item']; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->expense_date)->format('d-m-Y') }}</td>
                            <td>{{ $item->description }}<br><small>({{ $item->category->name ?? 'Other Expense' }})</small></td>
                            <td class="text-center">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right expense-total">${{ number_format($item->amount, 2) }}</td>
                        </tr>

                    @elseif ($expense['type'] == 'Payroll')
                        @php $item = $expense['item']; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->payment_date)->format('d-m-Y') }}</td>
                            <td>ប្រាក់ខែ {{ $item->employee->name ?? '' }}<br><small>({{ $item->month_year }})</small></td>
                            <td class="text-center">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right expense-total">${{ number_format($item->net_salary, 2) }}</td>
                        </tr>

                    @elseif ($expense['type'] == 'Clear Stock')
                        @php $item = $expense['item']; @endphp
                        <tr style="background-color: #fbebeb;">
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->product_name ?? 'N/A' }}<br><small>(Cleared Stock - Loss)</small></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->product->buying_price ?? 0, 2) }}</td>
                            <td class="text-right expense-total">${{ number_format($item->quantity * ($item->product->buying_price ?? 0), 2) }}</td>
                        </tr>
                    @elseif ($expense['type'] == 'Purchase Return')
                        @php $item = $expense['item']; @endphp
                        <tr style="background-color: #eafaf1;">
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $item->product->product_name ?? 'N/A' }}<br><small>(Purchase Return - Debit)</small></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->product->buying_price ?? 0, 2) }}</td>
                            <td class="text-right credit-total">-${{ number_format($item->quantity * ($item->product->buying_price ?? 0), 2) }}</td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="5" class="text-center">No expense data available</td></tr>
                @endforelse
            </tbody>
        </table>
        {{-- ✅ END: REVISED EXPENSE DETAILS --}}
    </div>
</body>
</html>