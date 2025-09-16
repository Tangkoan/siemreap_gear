<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Expense Report</title>
    <style>
        
        body {
            font-family: 'Khmer OS Siemreap', sans-serif; /* ប្រើ Font ខ្មែរជា Default */
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h1 {
            margin: 0;
            font-size: 20px;
        }
        .report-header p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .summary-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .section-header {
            background-color: #e6e6e6;
            font-weight: bold;
            font-size: 14px;
            padding: 10px;
            text-align: center;
        }
        .income-total {
            color: green;
            font-weight: bold;
        }
        .expense-total {
            color: red;
            font-weight: bold;
        }
        .profit {
             color: green;
             font-weight: bold;
        }
        .loss {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="report-header">
            <h1>Report Income & Outcome</h1>
            <p>{{ $summary['formattedDate'] }}</p>
        </div>

        <h3>Summary</h3>
        <table class="summary-table">
            <tr>
                <td>Total Revenue</td>
                <td class="income-total">${{ $summary['total_revenue'] }}</td>
            </tr>
            <tr>
                <td>Total Expenses</td>
                <td class="expense-total">${{ $summary['total_expenses'] }}</td>
            </tr>
            <tr>
                <td>Profit / Loss</td>
                <td class="{{ $summary['is_profit'] ? 'profit' : 'loss' }}">${{ $summary['profit_or_loss'] }}</td>
            </tr>
        </table>

        {{-- Income Details --}}
        <table>
            <thead>
                <tr>
                    <th colspan="5" class="section-header">
                        Income Details
                    </th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Disciption</th>
                    <th style="text-align:center;">QTY</th>
                    <th style="text-align:right;">Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales_details as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->order->order_date)->format('d-m-Y') }}</td>
                        <td>{{ $item->product->product_name ?? 'N/A' }} (Invoice: {{ $item->order->invoice_no ?? 'N/A' }})</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">${{ number_format($item->unitcost, 2) }}</td>
                        <td style="text-align:right;" class="income-total">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">dont's have Income</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Expense Details --}}
        <table>
             <thead>
                <tr>
                    <th colspan="5" class="section-header">
                       Expense Details
                    </th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Disciption</th>
                    <th style="text-align:center;">QTY</th>
                    <th style="text-align:right;">Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase_details as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-m-Y') }}</td>
                        <td>{{ $item->product->product_name ?? 'N/A' }} (Purchase: {{ $item->purchase->invoice_no ?? 'N/A' }})</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">${{ number_format($item->purchase_price, 2) }}</td>
                        <td style="text-align:right;" class="expense-total">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
                @foreach ($other_expenses as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                        <td>{{ $item->details }}</td>
                        <td style="text-align:center;">-</td>
                        <td style="text-align:right;">-</td>
                        <td style="text-align:right;" class="expense-total">${{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach

                @if($purchase_details->isEmpty() && $other_expenses->isEmpty())
                     <tr>
                        <td colspan="5" style="text-align:center;">don't have Expense</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>