<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Order #{{ $order->invoice_no }}</title>
   
    <link
        href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        /* === ការកំណត់ទូទៅ (General Settings) === */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            /* ✅ Best practice for easier layout management */
        }

        body {
            font-family: 'Roboto', 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f4f6f9;
            font-size: 11pt;
            color: #333333;
        }

        

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* === ក្បាលវិក្កយបត្រ (Header) === */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* ✅ Align items vertically center */
            margin-bottom: 25px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 20px;
        }

        .logo-container {
            flex: 1;
        }

        .logo-container img {
            max-width: 140px;
            display: block;
            border-radius: 100%; 
        }

        .invoice-title {
            flex: 2;
            text-align: center;
        }

        .invoice-title h1 {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 24pt;
            margin: 0;
            font-weight: 700;
            color: #d9534f;
        }

        .invoice-title h2 {
            font-size: 18pt;
            margin: 5px 0 0 0;
            font-weight: 700;
            color: #d9534f;
        }

        /* === ផ្នែកព័ត៌មាន (Info Section) === */
        .info-section {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            /* ✅ Make boxes same height */
        }

        .company-info-box,
        .invoice-info-box {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            width: 49%;
            /* ✅ Adjust width slightly */
            font-size: 10pt;
            border-radius: 4px;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            padding: 2.5px 2px;
            /* ✅ Adjust padding */
            vertical-align: top;
        }

        .info-table td.label {
            width: 1%;
            white-space: nowrap;
            /* ✅ Prevent labels from wrapping */
            padding-right: 8px;
            color: #6c757d;
            font-weight: bold;
        }

        .info-table td.colon {
            width: 1%;
        }

        .info-table td.value {
            word-break: break-word;
            /* ✅ Crucial for breaking long text like address */
        }

        .info-table td.value strong {
            color: #333333;
            /* Ensure strong text is dark */
        }

        /* === តារាងផលិតផល (Main Table) === */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            /* ✅ Vertically align content */
        }

        .main-table th {
            background-color: #f8f9fa;
            color: #495057;
            font-size: 10pt;
            text-transform: uppercase;
        }

        .main-table td.description {
            text-align: left;
            word-break: break-word;
            /* Allow description to wrap nicely */
        }

        /* === ផ្នែកខាងក្រោម (Footer) === */
        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            align-items: flex-end;
        }

        .terms-box {
            flex-basis: 65%;
            font-size: 9pt;
            color: #6c757d;
        }

        .terms-box h4 {
            margin: 0 0 8px 0;
            text-decoration: underline;
            color: #495057;
        }

        .terms-box p {
            margin: 0 0 5px 0;
            line-height: 1.5;
            /* ✅ Increase line height for readability */
            word-break: break-word;
            /* ✅ Allow terms to wrap nicely */
        }

        .totals-box {
            flex-basis: 33%;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            border: 1px solid #dee2e6;
            padding: 9px 12px;
            /* ✅ Adjust padding */
        }

        .totals-table td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .due-amount,
        .due-amount td {
            /* ✅ Target the row and its cells */
            color: #ffffff !important;
            background-color: #d9534f !important;
        }

        /* === ផ្នែកផ្សេងៗ (Misc) === */
        .note {
            margin-top: 25px;
            font-size: 10pt;
            padding: 12px;
            border-left: 3px solid #ffc107;
            background-color: #fffbeb;
        }

        .signatures {
            margin-top: 80px;
            display: flex;
            justify-content: space-around;
        }

        .signature-box {
            text-align: center;
            width: 220px;
            padding-top: 10px;
            border-top: 1px solid #6c757d;
            color: #6c757d;
        }
        .preserve-format {
            white-space: pre-wrap;
        }

        /* === ការកំណត់សម្រាប់ Print === */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                background-color: #fff;
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

            .due-amount,
            .due-amount td {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    @php
        $shopInfo = \App\Models\InformationShop::first();
    @endphp

    <div class="page">
        <div class="header">
            <div class="logo-container">
                <img src="{{ ($shopInfo && $shopInfo->logo) ? asset('upload/shop_info/' . $shopInfo->logo) : asset('upload/no_image.jpg') }}"
                    alt="Shop Logo">
            </div>
            <div class="invoice-title">
                <h1>ការកម៉្មង់ទុករួច</h1>
                <h2>PENDING ORDER</h2>
            </div>
            <div class="logo-container">
                {{-- This right side can be empty or have other info --}}
            </div>
        </div>

        <div class="info-section">
            <div class="company-info-box">
                <table class="info-table">
                    <tr>
                        <td class="label">Company</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $shopInfo->name_en ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Address</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $shopInfo->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tell</td>
                        <td class="colon">:</td>
                        <td class="value"><strong>{{ $shopInfo->phone ?? 'N/A' }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="invoice-info-box">
                <table class="info-table">
                    <tr>
                        <td class="label">Order No</td>
                        <td class="colon">:</td>
                        <td class="value"><strong>{{ $order->invoice_no }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Order Date</td>
                        <td class="colon">:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Customer</td>
                        <td class="colon">:</td>
                        <td class="value"><strong>{{ $order->customer->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $order->customer->phone ?? 'N/A' }}</td>
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
                @foreach ($order->orderdetails as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="description">{{ $item->product->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unitcost, 2) }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="note">
            <strong>Note:</strong> {{ $shopInfo->note ?? 'N/A' }}
        </div>

        <div class="footer-section">
            <div class="terms-box">
                <h4>Terms and Condition</h4>
                <p class="preserve-format">{{ $shopInfo->terms_and_condition ?? 'N/A' }}</p> 
            </div>
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td>Total / សរុប</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount / បញ្ចុះតម្លៃ</td>
                        <td>${{ number_format($order->discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Deposit / ប្រាក់កក់</td>
                        <td>${{ number_format($order->pay, 2) }}</td>
                    </tr>
                    <tr class="due-amount">
                        <td>Due / ប្រាក់ជំពាក់</td>
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
            // window.print();
            // window.onafterprint = function() {
            //     window.location.href = "{{ route('pos') }}";
            // };
        };
    </script>

</body>

</html>
