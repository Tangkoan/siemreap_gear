<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->invoice_no }}</title>

    <style>
        :root {
            --text-dark: #000000;
            --text-light: #333333;
            --border-light: #cccccc;
        }

        /* --- 1. Base Styles (Optimized for 80mm Print) --- */
        
        @page {
            size: 80mm; /* Set the paper width */
            margin: 2mm 1mm; /* Minimal margins (top/bottom, left/right) */
        }

        body {
            font-family: 'Kantumruy Pro', 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 8pt; /* Smaller font size for receipts */
            color: var(--text-dark);
            line-height: 1.3;
            word-break: break-word; 
        }

        .page {
            width: 100%; /* Fill the 80mm width defined by @page */
            padding: 0;
            background: #ffffff;
        }

        /* --- 2. Element Styles (Adjusted for 80mm) --- */

        .header {
            display: flex;
            flex-direction: column; /* Stack logo and titles */
            align-items: center; /* Center everything */
            text-align: center;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-bottom: 1px solid var(--text-dark);
        }

        .logo-container img {
            max-width: 50px; /* Make logo small */
            border-radius: 50%;
            margin-bottom: 5px;
        }

        .header-titles {
            text-align: center;
        }

        .title-kh {
            font-family: 'Kantumruy Pro', 'Moul', sans-serif;
            font-size: 14pt; /* Smaller title */
            font-weight: 700;
            line-height: 1.2;
        }

        .title-en {
            font-size: 10pt; /* Smaller subtitle */
            font-weight: 500;
        }

        .info-section {
            display: block; /* Stack company and invoice info */
            margin-bottom: 10px;
        }

        .company-info-box,
        .invoice-info-box {
            width: 100%; /* Make them full width */
            padding: 0;
            background-color: transparent;
            border: none;
            margin-bottom: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 80px; /* Fixed width for labels */
            font-weight: 500;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8pt;
        }

        .main-table thead th {
            padding: 4px 2px;
            text-align: center;
            font-weight: bold;
            color: var(--text-dark);
            text-transform: uppercase;
            border-bottom: 1px solid #333;
        }

        .main-table tbody td {
            padding: 4px 2px;
            border-bottom: 1px dotted var(--border-light);
            text-align: center;
        }
        
        .main-table tbody tr:last-child td {
             border-bottom: 1px solid #333;
        }

        .main-table td.description {
            text-align: left;
            word-break: break-all; /* Allow long product names to wrap */
        }

        .footer-section {
            display: block; /* Stack terms and totals */
            margin-top: 10px;
        }

        .terms-box {
            width: 100%;
            padding-right: 0;
            margin-bottom: 10px;
            text-align: center; /* Center terms for receipt */
        }

        .totals-box {
            width: 100%;
            border: none;
        }

        .note {
            margin-top: 10px;
            padding: 5px;
            font-size: 7pt;
            border-radius: 0;
            text-align: center;
        }

        .terms-box h4 {
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .terms-box p {
            margin: 0;
            font-size: 7pt;
            line-height: 1.4;
            color: var(--text-light);
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 4px 2px;
            border-bottom: 1px dotted var(--border-light);
            font-size: 9pt; /* Make totals slightly bigger */
        }

        .totals-table tr:last-child td {
            border-bottom: none;
        }

        .totals-table td:first-child {
            font-weight: 500;
            background-color: transparent;
        }

        .totals-table td:last-child {
            text-align: right;
            font-weight: 700;
        }
        
        .totals-table tr.grand-total td {
            font-size: 11pt;
            font-weight: 700;
            color: var(--text-dark); 
            background-color: transparent;
            border-top: 1px solid var(--text-dark);
        }

        .signatures {
            margin-top: 20px;
            padding-top: 10px;
            display: flex;
            flex-direction: column; /* Stack signatures */
            align-items: center;
            text-align: center;
            border-top: 1px dotted var(--border-light);
        }

        .signature-box {
            text-align: center;
            width: 100%;
            color: var(--text-light);
            font-size: 8pt;
            margin-top: 20px;
        }

        .preserve-format {
            white-space: pre-wrap;
        }

        @media print {
            body {
                /* No color adjust needed for B&W thermal */
            }
            .page {
                box-shadow: none;
                border: none;
                margin: 0;
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
            <div class="header-titles">
                <div class="title-kh">{{ $shopInfo->name_kh ?? 'សៀមរាបហ្គៀ' }}</div>
                <div class="title-en">{{ $shopInfo->name_en ?? 'Siem Reap Gear' }}</div>
            </div>
        </div>
        <div class="info-section">
            <div class="company-info-box">
                <table class="info-table">
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
                        <td>Date</td>
                        <td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>: {{ $order->customer->name }}</td>
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
                    <th style="width:45%;">Product</th>
                    <th style="width:10%;">Qty</th>
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
                    {{-- <tr class="grand-total">
                        <td>Balance</td>
                        <td>${{ number_format($order->due, 2) }} /
                            {{ number_format(round($order->due * $order->exchange_rate_khr, -2), 0) }}៛</td>
                    </tr> --}}
                </table>
            </div>
            
            <div class="terms-box">
                <div class="note">
                    <strong>Note:</strong> {{ $shopInfo->note ?? 'N/A' }}
                </div>
                
                <div>
                    <h4>Terms and Condition</h4>
                    <p class="preserve-format">{{ $shopInfo->terms_and_condition ?? 'N/A' }}</p> 
                </div>
            </div>
        </div>

        {{-- Signatures are often not needed for small receipts --}}
        {{-- <div class="signatures">
            <div class="signature-box">Customer Signature</div>
            <div class="signature-box">Seller Signature</div>
        </div> --}}
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