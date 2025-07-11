{{-- ត្រឹមត្រូវ ✅ - អ្វីៗទាំងអស់ស្ថិតនៅក្រោម <tbody> តែមួយ --}}
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
    
        <thead>
            <tr>
                <th colspan="5" style="font-size: 14px; font-weight: bold;">Income Details (Sales)</th>
            </tr>
            <tr>
                <th style="font-weight: bold; text-align: center;">Date</th>
                <th style="font-weight: bold;">Details</th>
                <th style="font-weight: bold; text-align: center;">Qty</th>
                <th style="font-weight: bold; text-align: right;">Price</th>
                <th style="font-weight: bold; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($sales_details as $item)
            <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->order->order_date)->format('d-m-Y') }}</td>
                <td>{{ $item->product->product_name ?? 'N/A' }} (Invoice: {{ $item->order->invoice_no ?? 'N/A' }})</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->unitcost, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center;">No sales data available.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="5"></td>
        </tr>
        </tbody>
        
        <thead>
            <tr>
                <th colspan="5" style="font-size: 14px; font-weight: bold;">Expense Details</th>
            </tr>
            <tr>
                <th style="font-weight: bold; text-align: center;">Date</th>
                <th style="font-weight: bold;">Details</th>
                <th style="font-weight: bold; text-align: center;">Qty</th>
                <th style="font-weight: bold; text-align: right;">Price</th>
                <th style="font-weight: bold; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
        {{-- Purchase Expenses --}}
        @foreach ($purchase_details as $item)
            <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->purchase->purchase_date)->format('d-m-Y') }}</td>
                <td>{{ $item->product->product_name ?? 'N/A' }} (Purchase Invoice: {{ $item->purchase->invoice_no ?? 'N/A' }})</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->purchase_price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    
        {{-- Other Expenses --}}
        @foreach ($other_expenses as $item)
            <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                <td>{{ $item->details }} (Other Expense)</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach
    
        @if($purchase_details->isEmpty() && $other_expenses->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center;">No expense data available.</td>
            </tr>
        @endif
        </tbody>
    </table>