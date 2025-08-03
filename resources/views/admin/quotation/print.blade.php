<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $quotation->quotation_no }}</title>
    
    <!-- Include Tailwind CSS for modern styling -->
    <!-- បញ្ចូល Tailwind CSS សម្រាប់​ការ​រចនា​បែប​ទំនើប -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Include Google Fonts for professional typography -->
    <!-- បញ្ចូល Google Fonts សម្រាប់​អក្សរ​បែប​អាជីព -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Khmer+OS+Siemreap:wght@400;700&family=Khmer+OS+Muol+Light&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A5;
            margin: 0; 
        }

        body {
            background-color: #f3f4f6; 
            font-family: 'Inter', 'Khmer OS Siemreap', sans-serif;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .quotation-paper {
            width: 148mm; 
            /* ✅ ADJUSTED: Reduced min-height to better fit content */
            /* ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ min-height ដើម្បីឲ្យសមនឹងเนื้อหา */
            min-height: 190mm; 
            margin: 2rem auto;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border-radius: 0.25rem;
            border-top: 6px solid red; /* Teal-600 color */
        }
        .font-khmer-muol {
            font-family: 'Khmer OS Muol Light', sans-serif;
        }
        .font-khmer-siemreap {
            font-family: 'Khmer OS Siemreap', sans-serif;
        }

        @media print {
            body {
                background-color: #fff;
            }
            .quotation-paper {
                margin: 0;
                padding: 1cm; 
                box-shadow: none;
                border-radius: 0;
                width: 100%;
                height: 100%;
            }
            .print-hidden {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="quotation-paper">
        <!-- ✅ ADJUSTED: Reduced padding from p-5 to p-4 -->
        <!-- ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ Padding ពី p-5 ទៅ p-4 -->
        <div class="p-4">
            <!-- Header Section -->
            <!-- ផ្នែក Header -->
            <!-- ✅ ADJUSTED: Reduced bottom margin from mb-6 to mb-4 -->
            <!-- ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ Margin ខាងក្រោមពី mb-6 ទៅ mb-4 -->
            <header class="flex justify-between items-start pb-4 mb-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('image/logo.jpg') }}" alt="SR Gears Logo" class="w-14 h-14 rounded-full">
                    <div>
                        <h1 class="font-khmer-muol text-xl font-bold text-gray-900">សៀមរាប ហ្គៀរ</h1>
                        <p class="text-base font-semibold text-gray-700">Siem Reap Gears</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-red-600 tracking-wider">QUOTATION</h2>
                    <p class="text-xs text-gray-500 mt-1">No: <span class="font-medium text-gray-700">{{ $quotation->quotation_no }}</span></p>
                </div>
            </header>

            <!-- Information Section -->
            <!-- ផ្នែកព័ត៌មាន -->
            <section class="flex flex-col text-xs">
                <div class="flex justify-between gap-6">
                    <div class="flex-1">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">To Customer</h3>
                        <div class="space-y-1 text-gray-800">
                            <p class="font-bold text-sm">{{ $quotation->customer->name }}</p>
                            <p>{{ $quotation->customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex-1 text-right">
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                            <span class="font-semibold text-gray-600">Date:</span>
                            <span class="text-gray-800">{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d-M-Y') }}</span>
                            
                        </div>
                    </div>
                </div>
            </section>

            <!-- Products Table -->
            <!-- តារាងផលិតផល -->
            <!-- ✅ ADJUSTED: Reduced top margin from mt-8 to mt-6 -->
            <!-- ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ Margin ខាងលើពី mt-8 ទៅ mt-6 -->
            <section class="mt-6">
                <table class="w-full text-left">
                    <thead class="bg-red-50 text-red-800 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-2 w-8 text-center rounded-l-md">#</th>
                            <th class="p-2">Product</th>
                            <th class="p-2 w-16 text-center">Qty</th>
                            <th class="p-2 w-24 text-right">Price</th>
                            <th class="p-2 w-24 text-right rounded-r-md">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($quotationDetails as $key => $item)
                        <tr class="border-b border-gray-100">
                            <td class="p-2 text-center text-gray-500">{{ $key + 1 }}</td>
                            <td class="p-2 font-medium text-gray-800">{{ optional($item->product)->product_name ?? 'Product not found' }}</td>
                            <td class="p-2 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="p-2 text-right text-gray-600">${{ number_format($item->unitcost, 2) }}</td>
                            <td class="p-2 text-right font-semibold text-gray-800">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- Footer Section (Terms & Summary) -->
            <!-- ផ្នែក Footer (លក្ខខណ្ឌ និងការសរុប) -->
            <!-- ✅ ADJUSTED: Reduced top margin from mt-6 to mt-4 -->
            <!-- ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ Margin ខាងលើពី mt-6 ទៅ mt-4 -->
            <footer class="mt-4 flex flex-col">
                <div class="flex justify-end">
                    <div class="w-full max-w-xs space-y-2 text-sm">
                        <div class="flex justify-between items-center text-blue-700">
                            <span class="font-medium">Subtotal</span>
                            <span class="font-medium">${{ number_format($quotation->sub_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">Discount</span>
                            <span class="font-medium text-blue-600">-${{ number_format($quotation->discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-base font-bold text-gray-900 border-t-2 border-red-500 pt-2 mt-2">
                            <span class="text-red-600">Total</span>
                            <span class="text-red-600">${{ number_format($quotation->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Terms & Conditions</h3>
                    <div class="mt-2 text-xs text-gray-600 prose prose-sm max-w-none">
                        {!! nl2br(e($quotation->terms_and_conditions)) !!}
                    </div>
                </div>
            </footer>

            <!-- Signature Section -->
            <!-- ផ្នែកហត្ថលេខា -->
            <!-- ✅ ADJUSTED: Significantly reduced top margin from mt-20 to mt-10 -->
            <!-- ✅ បានកែសម្រួល៖ បានកាត់បន្ថយ Margin ខាងលើយ៉ាងច្រើនពី mt-20 ទៅ mt-10 -->
            <section class="grid grid-cols-2 gap-12 mt-10">
                <div class="text-center border-t pt-2">
                    <p class="text-xs text-gray-600">Customer Signature</p>
                </div>
                <div class="text-center border-t pt-2">
                    <p class="text-xs text-gray-600">Seller Signature</p>
                </div>
            </section>
            
            <p class="print-hidden text-center text-xs text-gray-400 mt-8">
                This is a preview. Redirecting back to POS after printing...
            </p>
        </div>
    </div>

    <!-- JavaScript for printing and redirecting -->
    <!-- JavaScript សម្រាប់ Print និង Redirect -->
    <script>
        window.onload = function() {
            window.print();
        };
        window.onafterprint = function() {
            window.location.href = "{{ route('clear.cart.pos') }}";
        };
    </script>

</body>
</html>
