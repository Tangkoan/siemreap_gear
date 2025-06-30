<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->invoice_no }}</title>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice,
            #invoice * {
                visibility: visible;
            }

            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div id="invoice">
        <h2>Invoice: {{ $order->invoice_no }}</h2>
        <p>Customer: {{ $order->customer->name }}</p>
        <p>Date: {{ $order->order_date }}</p>
        <hr>
        <table width="100%" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Cost</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>${{ $detail->unitcost }}</td>
                        <td>${{ $detail->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <p><strong>Subtotal:</strong> ${{ $order->sub_total }}</p>
        <p><strong>Discount:</strong> ${{ $order->sub_total - $order->total }}</p>
        <p><strong>Total:</strong> ${{ $order->total }}</p>
        <p><strong>Paid:</strong> ${{ $order->pay }}</p>
        <p><strong>Due:</strong> ${{ $order->due }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->order_status) }}</p>
    </div>

    <script>
        window.onload = function () {
            window.print();

            window.onafterprint = function () {
                // ✅ Pass message via query string
                window.location.href = "{{ route('pos') }}?message={{ urlencode('Order completed successfully') }}&alert-type=success";
            };
        };
    </script>
</body>

</html>