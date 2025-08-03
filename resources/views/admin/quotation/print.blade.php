<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $quotation->quotation_no }}</title>
    <style>
        body {
            font-family: 'Khmer OS Siemreap', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
        }
        .quotation-container {
            width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        /* ... CSS ផ្សេងទៀតរបស់អ្នកអាចនៅទីនេះ ... */
        .header, .info-section, .footer-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .header .logo-container { display: flex; align-items: center; }
        .header .logo { width: 80px; height: auto; margin-right: 15px; }
        .header .company-name-kh { font-family: 'Khmer OS Muol Light', sans-serif; font-size: 24px; color: #000; font-weight: bold; }
        .header .company-name-en { font-size: 18px; font-weight: bold; }
        .header .title-box { text-align: right; }
        .header .title { font-size: 32px; font-weight: bold; padding: 5px 15px; border: 2px solid #000; display: inline-block; }
        .info-box { width: 48%; }
        .info-box p { margin: 0 0 8px 0; }
        .info-box .info-line { display: flex; margin-bottom: 12px; }
        .info-box .info-label { min-width: 120px; font-weight: bold; }
        .info-box .info-value { border-bottom: 1px dotted #888; width: 100%; padding-left: 5px; }
        .product-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .product-table th, .product-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .product-table th { background-color: #f2f2f2; font-weight: bold; }
        .product-table td.center { text-align: center; }
        .product-table td.right { text-align: right; }
        .product-table .dollar-sign { float: left; margin-right: 10px; }
        .note { font-size: 12px; font-style: italic; margin-bottom: 20px; }
        .footer-box { width: 48%; }
        .terms-conditions ul { padding-left: 20px; margin: 0; font-size: 12px; }
        .summary-table { width: 100%; }
        .summary-table td { padding: 8px 0; }
        .summary-table .summary-label { font-weight: bold; }
        .summary-table .summary-value { text-align: right; border: 1px solid #000; padding: 8px; }
        .summary-table .balance { font-weight: bold; background-color: #e0e0e0; }
        .signature-area { margin-top: 60px; border-top: 1px solid #000; padding-top: 5px; text-align: center; font-weight: bold; }
        .print-message { text-align: center; margin-top: 20px; font-style: italic; color: #777; }

        @media print {
            .print-message { display: none; }
        }
    </style>
</head>
<body>

    <div class="quotation-container">
        {{-- កូដ HTML សម្រាប់បង្ហាញ Quotation របស់អ្នកទាំងអស់គឺនៅទីនេះ --}}
        <div class="header">
            <div class="logo-container">
                 <img src="{{ asset('image/logo.jpg') }}" alt="SR Gears Logo" class="logo">
                <div>
                    <p class="company-name-kh">សៀមរាប ហ្គៀរ</p>
                    <p class="company-name-en">Siem Reap Gears</p>
                    <small>Upgrade Your Professional</small>
                </div>
            </div>
            <div class="title-box">
                <span class="title">QUOTATION</span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <p><strong>Company:</strong> SR Gears</p>
                <p><strong>Address:</strong> #C02, St.Kompea Mother, Mondul I Village, Svay DongKom Commune, SiemReap Town</p>
                <p><strong>Tell:</strong> 098 222 500 / 017 3000 31</p>
            </div>
            <div class="info-box">
                <div class="info-line">
                    <span class="info-label">Quotation No:</span>
                    <span class="info-value">{{ $quotation->quotation_no }}</span>
                </div>
                <div class="info-line">
                    <span class="info-label">Quotation Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-M-Y') }}</span>
                </div>
                 <div class="info-line">
                    <span class="info-label">Customer Name:</span>
                    <span class="info-value">{{ $quotation->customer->name }}</span>
                </div>
                <div class="info-line">
                    <span class="info-label">Validity:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($quotation->validity_date)->format('d-M-Y') }}</span>
                </div>
                <div class="info-line">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $quotation->customer->phone }}</span>
                </div>
            </div>
        </div>
        
        <table class="product-table">
            <thead>
                <tr>
                    <th style="width:5%;">N°</th>
                    <th style="width:55%;">Product & Description</th>
                    <th style="width:10%;" class="center">Quantity</th>
                    <th style="width:15%;" class="right">Price</th>
                    <th style="width:15%;" class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $rowCount = 15; @endphp
                @foreach($quotationDetails as $key => $item)
                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td>{{ optional($item->product)->product_name ?? 'Product not found' }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">
                        <span class="dollar-sign">$</span>{{ number_format($item->unitcost, 2) }}
                    </td>
                    <td class="right">
                        <span class="dollar-sign">$</span>{{ number_format($item->total, 2) }}
                    </td>
                </tr>
                @endforeach
                {{-- @for ($i = count($quotationDetails); $i < $rowCount; $i++)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
                @endfor --}}
            </tbody>
        </table>
        <p class="note">Note: Before receiving the goods, you must check the quality and quantity that cannot be returned.</p>
        <div class="footer-section">
            <div class="footer-box terms-conditions">
                <strong>Terms and Condition</strong>
                <p style="font-size: 12px;">{!! nl2br(e($quotation->terms_and_conditions)) !!}</p>
                <div class="signature-area">Customer Signature</div>
            </div>
            <div class="footer-box">
                <table class="summary-table">
                    <tr><td class="summary-label">Total</td><td class="summary-value">${{ number_format($quotation->sub_total, 2) }}</td></tr>
                    <tr><td class="summary-label">Deposit</td><td class="summary-value"></td></tr>
                    <tr><td class="summary-label">Discount</td><td class="summary-value">${{ number_format($quotation->discount, 2) }}</td></tr>
                    <tr><td class="summary-label balance">Balance</td><td class="summary-value balance">${{ number_format($quotation->total, 2) }}</td></tr>
                </table>
                 <div class="signature-area">Seller Signature</div>
            </div>
        </div>

    </div>

    <p class="print-message">Redirecting back to POS after printing...</p>

    {{-- ✅ START: JAVASCRIPT ថ្មីសម្រាប់จัดการ PRINT និង REDIRECT --}}
    <script>
        // នៅពេលที่ Browser បង្ហាញเนื้อหาทั้งหมดរួចរាល់
        window.onload = function() {
            // បង្ហាញផ្ទាំង Print ដោយស្វ័យប្រវត្តិ
            window.print();
        };

        // នៅពេលដែលផ្ទាំង Print ត្រូវបានបិទ (មិនថាចុច Print ឬ Cancel)
        window.onafterprint = function() {
            // បញ្ជូនទៅកាន់ Route ដែលจะ Clear Cart ហើយ Redirect ទៅ POS
            window.location.href = "{{ route('clear.cart.pos') }}";
        };
    </script>
    {{-- ✅ END: JAVASCRIPT --}}

</body>
</html>