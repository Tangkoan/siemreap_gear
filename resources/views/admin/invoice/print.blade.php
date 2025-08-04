<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->invoice_no }}</title>

    <style>
        :root {
            --accent-color: #960000;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --border-light: #e0e0e0;
            --bg-light: #f9fafb;
        }

        body {
            font-family: 'Roboto', 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f0f2f5;
            font-size: 10pt;
            color: var(--text-dark);
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        /* --- ✅ START: HEADER SECTION កែសម្រួលថ្មី --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--text-dark); /* ប្ដូរឱ្យដូចរូបភាព */
        }

        .logo-container img {
            max-width: 150px; /* កែទំហំឱ្យសមរម្យ */
            border-radius: 100%; 
        }

        .header-titles {
            text-align: right;
        }

        .title-kh {
            font-family: 'Kantumruy Pro', 'Moul', sans-serif;
            font-size: 26pt;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .title-en {
            font-size: 12pt;
            color: var(--text-dark);
            font-weight: 500;
        }
        /* --- ✅ END: HEADER SECTION កែសម្រួលថ្មី --- */


        /* --- Info Boxes Section --- */
        .info-section {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            margin-bottom: 30px;
            gap: 20px;
        }

        .company-info-box,
        .invoice-info-box {
            width: 48%;
            padding: 15px;
            background-color: var(--bg-light);
            border-radius: 5px;
            border-left: 4px solid var(--accent-color);
            border: 1px solid var(--border-light);
            border-left-width: 4px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 110px;
            font-weight: 500;
            color: #555;
        }

        /* --- Main Items Table --- */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10pt;
        }

        .main-table thead th {
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            color: var(--accent-color);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #ccc;
        }

        .main-table tbody td {
            padding: 12px 8px;
            border-bottom: 1px solid var(--border-light);
            text-align: center;
        }

        .main-table tbody tr:last-child td {
            border-bottom: none;
        }

        .main-table td.description {
            text-align: left;
        }

        .main-table th,
        .main-table td {
            border-left: none;
            border-right: none;
            border-top: none;
        }

        .main-table tbody tr:hover {
            background-color: var(--bg-light);
        }

        /* --- Footer: Terms & Totals --- */
        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 30px;
        }

        .terms-box {
            flex-basis: 65%;
            padding-right: 30px;
        }

        .totals-box {
            flex-basis: 35%;
            border-radius: 6px;
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .note {
            margin-top: 20px;
            padding: 12px;
            background: #f0f9f8;
            border-left: 4px solid var(--accent-color);
            font-size: 9pt;
            border-radius: 4px;
        }

        .terms-box h4 {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: var(--text-dark);
        }

        .terms-box p {
            margin: 0 0 5px 0;
            font-size: 9pt;
            line-height: 1.6;
            color: var(--text-light);
        }

        /* --- Totals Table --- */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-light);
        }

        .totals-table tr:last-child td {
            border-bottom: none;
        }

        .totals-table td:first-child {
            font-weight: 500;
            color: #555;
            background-color: #fafafa;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 700;
        }

        .totals-table tr:last-child td {
            font-size: 14pt;
            font-weight: 700;
            color: #ffffff;
            background-color: var(--accent-color);
        }

        /* --- Signatures --- */
        .signatures {
            margin-top: 80px;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--border-light);
        }

        .signature-box {
            text-align: center;
            width: 220px;
            color: var(--text-light);
            font-size: 10pt;
        }

        /* --- Print Settings --- */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .page {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 15mm;
            }
        }
        .preserve-format {
            white-space: pre-wrap;
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
            <div class="header-titles">
                <div class="title-kh">{{ $shopInfo->name_kh ?? 'សៀមរាបហ្គៀ' }}</div>
                <div class="title-en">{{ $shopInfo->name_en ?? 'Siem Reap Gear' }}</div>
            </div>
        </div>
        <div class="info-section">
            <div class="company-info-box">
                <table class="info-table">
                    <tr>
                        <td>Company</td>
                        <td>: <strong>{{ $shopInfo->name_en ?? 'Siem Reap Gear' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>: {{ $shopInfo->address ?? 'Siem Reap' }}</td>
                    </tr>
                    <tr>
                        <td>Tell</td>
                        <td>: <strong>{{ $shopInfo->phone ?? 'N/A' }}</strong></td>
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
                @foreach ($orderDetails as $key => $item)
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

        <div class="footer-section">
            <div class="terms-box">
                <div>
                    <h4>Terms and Condition</h4>
                    <p class="preserve-format">{{ $shopInfo->terms_and_condition ?? 'N/A' }}</p> 
                </div>

                <div>
                    <div class="note">
                        <strong>Note:</strong> {{ $shopInfo->note ?? 'N/A' }}
                    </div>

                    
                </div>
            </div>
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td>Total</td>
                        <td>
                            ${{ number_format($order->sub_total, 2) }} /
                            {{ number_format(round($order->sub_total * $order->exchange_rate_khr, -2), 0) }}៛
                        </td>
                    </tr>
                    <tr>
                        <td>Deposit</td>
                        <td>${{ number_format($order->pay, 2) }} /
                            {{ number_format(round($order->pay * $order->exchange_rate_khr, -2), 0) }}៛</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>${{ number_format($order->discount, 2) }} /
                            {{ number_format(round($order->discount * $order->exchange_rate_khr, -2), 0) }}៛</td>
                    </tr>
                    <tr>
                        <td>Balance</td>
                        <td>${{ number_format($order->due, 2) }} /
                            {{ number_format(round($order->due * $order->exchange_rate_khr, -2), 0) }}៛</td>
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
                window.location.href = "{{ route('pos') }}";
            };
        };
    </script>
</body>
</html>