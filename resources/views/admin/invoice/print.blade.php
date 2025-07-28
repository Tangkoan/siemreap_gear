<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->invoice_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f0f0f0;
            font-size: 11pt;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .header, .info-section, .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header {
            margin-bottom: 20px;
        }
        .logo-container {
            flex-basis: 30%;
        }
        .logo-container img {
            max-width: 150px;
        }
        .invoice-title {
            flex-basis: 40%;
            text-align: center;
        }
        .invoice-title h1 {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 24pt;
            margin: 0;
            font-weight: 700;
        }
        .invoice-title h2 {
            font-size: 18pt;
            margin: 0;
            font-weight: 700;
        }
        .company-info-box, .invoice-info-box {
            border: 1px solid #000;
            padding: 5px 10px;
            width: 48%;
            font-size: 10pt;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 1px 2px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 100px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .main-table th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .main-table td.description {
            text-align: left;
        }
        .footer-section {
            margin-top: 10px;
        }
        .terms-box {
            flex-basis: 65%;
        }
        .totals-box {
            flex-basis: 33%;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            border: 1px solid #000;
            padding: 6px 10px;
        }
        .totals-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .totals-table td:last-child {
            text-align: right;
        }
        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
        .note {
            margin-top: 15px;
            font-size: 10pt;
        }
        .terms-box h4 {
            margin: 0 0 5px 0;
            text-decoration: underline;
        }
        .terms-box p {
            margin: 0 0 5px 0;
            line-height: 1.4;
        }

        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
                background: white;
            }
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('image/logo.jpg') }}" alt="SR Gears Logo">
        </div>
        <div class="invoice-title">
            <h1>វិក្កយបត្រ</h1>
            <h2>INVOICE</h2>
        </div>
        <div class="logo-container" style="text-align: right;">
            {{-- This space can be used for another logo or can be removed --}}
        </div>
    </div>

    <div class="info-section">
        <div class="company-info-box">
            <table class="info-table">
                <tr>
                    <td>Company</td>
                    <td>: <strong>SR Gears</strong></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>: #C02, St.Kompea Motter, Mondul I Village, Svay Dongkom Commune, Siem Reap Town</td>
                </tr>
                <tr>
                    <td>Tell</td>
                    <td>: <strong>098 222 600 / 017 3000 31</strong></td>
                </tr>
            </table>
        </div>
        <div class="invoice-info-box">
             <table class="info-table">
                <tr>
                    <td>Invoice No</td>
                    <td>: <strong>{{ $order->invoice_no }}</strong></td>
                </tr>
                <tr>
                    <td>Invoice Date</td>
                    <td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y') }}</td>
                </tr>
                 <tr>
                    <td>Customer Name</td>
                    <td>: {{ $order->customer->name }}</td>
                </tr>
                <tr>
                    <td>Validity</td>
                    <td>: {{-- You can add validity days if needed --}}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>: {{ $order->customer->phone ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width:5%;">N°</th>
                <th style="width:45%;">Product & Description</th>
                <th style="width:10%;">Quantity</th>
                <th style="width:20%;">Price</th>
                <th style="width:20%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderDetails as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td class="description">{{ $item->product->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unitcost, 2) }}</td>
                <td>${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
            
            {{-- Add empty rows to make it 16 rows total --}}
            {{-- @for ($i = $orderDetails->count(); $i < 16; $i++)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor --}}
        </tbody>
    </table>
    
    <div class="note">
        <strong>Note:</strong> Before receiving the goods, you must check the quality and quantity that cannot be returned.
    </div>

    <div class="footer-section">
        <div class="terms-box">
            <h4>Terms and Condition</h4>
            <p><strong>A.</strong> Laptop 2years Warranty. 1Year service warranty</p>
            <p><strong>B.</strong> Warranty void if seal broken, electric shock, misuse, system or modification by anyone other than SR Gears.</p>
            <p><strong>C.</strong> CPU(1year), MB(3year), RAM(1year), GPU(3year), HDD(1year), SSD(1year), Monitor(3year).</p>
            <p><strong>D.</strong> Goods sold are not refundable or returnable.</p>
        </div>
        <div class="totals-box">
            <table class="totals-table">
                <tr>
                    <td>Total</td>
                    <td>${{ number_format($order->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td>Deposit</td>
                    <td>${{ number_format($order->pay, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td>${{ number_format($order->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Balance</td>
                    <td>${{ number_format($order->due, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-box">Customer Signature</div>
        <div class="signature-box">Seller Signature</div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
        window.onafterprint = function() {
            // Redirect back to POS page
            window.location.href = "{{ route('pos') }}";
        };
    };
</script>

</body>
</html>