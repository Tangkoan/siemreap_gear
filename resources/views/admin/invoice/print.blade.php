<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->invoice_no }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #212529;
        }

        .invoice-container {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        /* --- Header --- */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
        }

        .company-details p {
            margin: 0;
            line-height: 1.5;
            color: #6c757d;
        }

        .company-details .company-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #212529;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #0d6efd;
            font-weight: 300;
            text-transform: uppercase;
        }

        .invoice-title p {
            margin: 0;
            color: #6c757d;
        }

        /* --- Billing Info --- */
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .billing-info p {
            margin: 0;
            line-height: 1.6;
        }

        .billing-info .billed-to {
            font-weight: bold;
        }

        /* --- Table --- */
        .invoice-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .invoice-table thead {
            background-color: #f8f9fa;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .invoice-table th {
            font-weight: 600;
        }

        .invoice-table .text-right {
            text-align: right;
        }

        /* --- Totals --- */
        .invoice-totals {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 100%;
            max-width: 300px;
        }

        .totals-table td {
            padding: 8px 0;
        }

        .totals-table .label {
            color: #6c757d;
        }

        .totals-table .value {
            text-align: right;
            font-weight: 600;
        }

        .totals-table .grand-total .label,
        .totals-table .grand-total .value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #0d6efd;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
        }

        /* --- Footer --- */
        .invoice-footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        /* --- Print Styles --- */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
            }

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

            @page {
                size: auto;
                margin: 20mm;
            }
        }
    </style>
</head>

<body>
    <div id="invoice" class="invoice-container">
        <header class="invoice-header">
            <div class="company-details">
                <p class="company-name">Siem Reap GEAR</p>
                <p>123 Business Rd, Suite 100</p>
                <p>City, State 12345, Cambodia</p>
                <p>contact@yourcompany.com</p>
            </div>
            <div class="invoice-title">
                <h1>Invoice</h1>
                <p>#{{ $order->invoice_no }}</p>
            </div>
        </header>

        <section class="billing-info">
            <div>
                <p class="billed-to">Billed To:</p>
                <p>{{ $order->customer->name }}</p>
                <p>{{ $order->customer->address ?? 'N/A' }}</p>
                <p>{{ $order->customer->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
                <p><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
            </div>
        </section>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Cost</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->product_name }}</td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">${{ number_format($detail->unitcost, 2) }}</td>
                    <td class="text-right">${{ number_format($detail->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <section class="invoice-totals">
            <table class="totals-table">
                <tbody>
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">${{ number_format($order->sub_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Discount</td>
                        <td class="value">-${{ number_format($order->sub_total - $order->total, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="label">Total</td>
                        <td class="value">${{ number_format($order->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Paid</td>
                        <td class="value">${{ number_format($order->pay, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label" style="color: #dc3545; font-weight: bold;">Amount Due</td>
                        <td class="value" style="color: #dc3545; font-weight: bold;">${{ number_format($order->due, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <footer class="invoice-footer">
            <p>Thank you for your business!</p>
        </footer>
    </div>

    <script>
        window.onload = function() {
            window.print();

            window.onafterprint = function() {
                // Redirect back to POS page with a success message after printing
                window.location.href = "{{ route('pos') }}?message={{ urlencode('Order completed successfully') }}&alert-type=success";
            };
        };
    </script>
</body>

</html>