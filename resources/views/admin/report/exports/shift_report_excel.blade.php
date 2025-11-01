<style>
    body {
        font-family: 'Khmer OS', sans-serif; /* សម្រាប់ PDF */
        font-size: 11px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px; /* ដកឃ្លារវាងតារាង */
    }
    th, td {
        border: 1px solid #999;
        padding: 6px;
        text-align: left;
    }
    th {
        font-weight: bold;
    }
    h2, h3, h4 {
        margin-bottom: 5px;
        margin-top: 10px;
    }
    .text-right { text-align: right; }
    .text-danger { color: red; }
    .text-success { color: green; }
    
    /* ពណ៌សម្រាប់ក្បាលតារាង */
    .summary-header { background-color: #d9edf7; } /* ពណ៌ខៀវខ្ចី */
    .details-header { background-color: #fcf8e3; } /* ពណ៌លឿងខ្ចី */
    .total-row td {
        font-weight: bold;
        background-color: #f5f5f5;
    }
    hr {
        border: 0;
        border-top: 1px solid #ccc;
        margin: 20px 0; /* ដកឃ្លាធំរវាងវេននីមួយៗ */
    }
</style>

<h2>Shift Report (Detailed)</h2>
<p>Date Generated: {{ \Carbon\Carbon::now()->format('d-M-Y H:i A') }}</p>

{{-- រង្វិលជុំ (Loop) សម្រាប់វេននីមួយៗ --}}
@forelse($shifts as $shift)
    
    {{-- =================================== --}}
    {{-- តារាងទី១៖ របាយការណ៍សង្ខេប (Summary) --}}
    {{-- =================================== --}}
    <h3>Shift ID: {{ $shift->id }} ({{ $shift->user->name ?? 'Unknown' }})</h3>
    <table>
        <thead>
            <tr class="summary-header">
                <th>Cashier</th>
                <th>Shift Duration</th>
                <th class="text-right">Expected Cash</th>
                <th class="text-right">Actual Cash</th>
                <th class="text-right">Difference</th>
                <th class="text-right">Total QR Sales</th>
                <th class="text-right">Total Card Sales</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $shift->user->name ?? 'Unknown' }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($shift->start_time)->format('d/m/Y H:i') }}
                    @if($shift->end_time)
                        to {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                    @endif
                </td>
                <td class="text-right">$ {{ number_format($shift->starting_cash + $shift->total_sales_cash, 2) }}</td>
                <td class="text-right">$ {{ number_format($shift->ending_cash, 2) }}</td>
                <td class="text-right 
                    @if($shift->difference < 0) text-danger @elseif($shift->difference > 0) @else text-success @endif">
                    $ {{ number_format($shift->difference, 2) }}
                </td>
                <td class="text-right">$ {{ number_format($shift->total_sales_qr, 2) }}</td>
                <td class="text-right">$ {{ number_format($shift->total_sales_card, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- =================================== --}}
    {{-- តារាងទី២៖ របាយការណ៍លម្អិត (Details) --}}
    {{-- =================================== --}}
    @if($shift->orders->count() > 0)
        <h4>Orders for this shift:</h4>
        <table>
            <thead>
                <tr class="details-header">
                    <th>Invoice No.</th>
                    <th>Payment Method</th>
                    <th>Product Name</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->orders as $order)
                    @foreach($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $order->invoice_no }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td>{{ $detail->product->product_name ?? 'N/A' }}</td>
                        <td class="text-right">{{ $detail->quantity }}</td>
                        <td class="text-right">$ {{ number_format($detail->unitcost, 2) }}</td>
                        <td class="text-right">$ {{ number_format($detail->total, 2) }}</td>
                    </tr>
                    @endforeach
                @endforeach
                {{-- បន្ទាត់សរុប (Total Row) --}}
                <tr class="total-row">
                    <td colspan="5" class="text-right">Total Sales (All Methods):</td>
                    <td class="text-right">$ {{ number_format($shift->orders->sum('total'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="font-style: italic; margin-left: 10px;">No completed orders found for this shift.</p>
    @endif

    {{-- បន្ទាត់ដាច់ (Separator) --}}
    <hr>
@empty
    <p>No shifts found matching the selected criteria.</p>
@endforelse