{{-- @extends('admin/admin_dashboard')
@section('admin')

<style>
    /* ថ្នាក់ CSS នេះនឹងលាក់ធាតុនៅពេលបោះពុម្ព */
    .no-print {
        display: block;
    }

    /* Style នេះនឹងដំណើរការតែពេលបញ្ជាឱ្យបោះពុម្ពប៉ុណ្ណោះ */
    @media print {

        /* ជំហានទី១៖ លាក់អ្វីៗដែលមិនត្រូវបោះពុម្ព (រួមទាំង Sidebar, Header និង Action Panel) */
        body * {
            visibility: hidden;
        }

        .no-print {
            display: none;
        }

        /* ជំហានទី២៖ បង្ហាញតែส่วนវិក្កយបត្រ និងអ្វីៗទាំងអស់ដែលនៅក្នុងនោះ */
        #invoice-box,
        #invoice-box * {
            visibility: visible;
        }

        /* ជំហានទី៣៖ កំណត់ទីតាំងវិក្កយបត្រឲ្យពេញក្រដាស */
        #invoice-box {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* ជំហានទី៤៖ កំណត់ទំហំក្រដាស និងលុប Header/Footer របស់ Browser */
        @page {
            size: A5;
            margin: 0;
        }
    }
</style>

<div class="page-content">
    <div class="flex flex-col lg:flex-row gap-8">

        <div id="invoice-box" class="lg:w-2/3 w-full bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-start pb-4 border-b">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="logo" class="w-24 h-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">សៀមរាប ហ្គៀ</h1>
                        <p class="text-sm text-gray-500">SIEM REAP GEARS</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-blue-600">INVOICE</h2>
                    <p class="text-sm text-gray-600">Invoice Date: {{ now()->format('d-M-Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mt-6">
                <div class="text-sm">
                    <h3 class="font-bold text-gray-700 mb-2">From:</h3>
                    <p class="">SR Gears</p>
                    <p class="text-gray-600">#C02, St.Kompea Mother, MonduI I Village, Svay Dongkom Commune, SiemReap Town</p>
                    <p class="text-gray-600">Tel: 098 222 500 / 017 3000 31</p>
                </div>
                <div class="text-sm bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-gray-700 mb-2">To:</h3>
                    <p class="">{{ $customer->name }}</p>
                    <p class="text-gray-600">{{ $customer->address ?? 'N/A' }}</p>
                    <p class="text-gray-600">Phone: {{ $customer->phone }}</p>
                </div>
            </div>

            <div class="mt-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-4 py-3">No.</th>
                                <th scope="col" class="px-4 py-3">Product & Description</th>
                                <th scope="col" class="px-4 py-3 text-right">Qty</th>
                                <th scope="col" class="px-4 py-3 text-right">Price</th>
                                <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents as $key => $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $item->name }}</th>
                                    <td class="px-4 py-3 text-right">{{ $item->qty }}</td>
                                    <td class="px-4 py-3 text-right">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-3 text-right ">${{ number_format($item->price * $item->qty, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <div class="w-full max-w-xs text-sm">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">${{ Cart::subtotal() }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">VAT (Tax):</span>
                        <span class="font-medium">${{ Cart::tax() }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-gray-200 mt-2">
                        <span class="font-bold text-lg text-gray-800">Total:</span>
                        <span class="font-bold text-lg text-blue-600">${{ Cart::total() }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-4 border-t text-center text-xs text-gray-500">
                <p>Thank you for your business!</p>
            </div>
        </div>

        <div class="lg:w-1/3 w-full no-print">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-4 border-b pb-2">ប្រតិបត្តិការ & ការទូទាត់</h3>
                
                @php
                    $sub_total = (float) str_replace(',', '', Cart::total()); // ប្រើ Total ព្រោះវាជាតម្លៃសរុបពិត
                @endphp

                <form method="post" action="{{ url('/final-invoice') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="payment_status" class="block mb-2 text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_status" id="payment_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option selected disabled>Select Payment</option>
                            <option value="HandCash">HandCash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Due">Due</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="payNow" class="block mb-2 text-sm font-medium text-gray-700">Pay Now ($)</label>
                        <input type="number" name="pay" id="payNow" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="0.00" oninput="calculateChange()" min="0" step="0.01" required>
                    </div>

                    <div class="mb-4">
                        <label for="changeAmount" class="block mb-2 text-sm font-medium text-gray-700">Change ($)</label>
                        <input type="text" name="change" id="changeAmount" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="0.00" readonly>
                    </div>

                     <div class="mb-4">
                        <label for="dueAmount" class="block mb-2 text-sm font-medium text-gray-700">Due Amount ($)</label>
                        <input type="text" name="due" id="dueAmount" class="bg-gray-100 border border-gray-300 text-red-600 font-bold text-sm rounded-lg block w-full p-2.5" placeholder="0.00" readonly>
                    </div>

                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="order_date" value="{{ date('d-F-Y') }}">
                    <input type="hidden" name="order_status" value="pending">
                    <input type="hidden" name="total_products" value="{{ Cart::count() }}">
                    <input type="hidden" name="sub_total" value="{{ Cart::subtotal() }}">
                    <input type="hidden" name="vat" value="{{ Cart::tax() }}">
                    <input type="hidden" name="total" id="grandTotal" value="{{ $sub_total }}">

                    <div class="mt-6 border-t pt-4">
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center mb-3">
                            <i class="fa-solid fa-floppy-disk"></i> បង្កើតវិក្កយបត្រ (Create Invoice)
                        </button>
                        <button type="button" onclick="window.print()" class="w-full text-gray-900 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-3 text-center">
                            <i class="fa-solid fa-print"></i> បោះពុម្ព (Print)
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function calculateChange() {
        let grandTotal = parseFloat(document.getElementById("grandTotal").value) || 0;
        let payNow = parseFloat(document.getElementById("payNow").value) || 0;

        let difference = payNow - grandTotal;
        let due = 0;
        let change = 0;

        if (difference < 0) {
            // បើបង់មិនគ្រប់ ចំនួនជំពាក់គឺតម្លៃដាច់ខាតនៃผลต่าง
            due = Math.abs(difference);
        } else {
            // បើបង់គ្រប់ ឬលើស ប្រាក់អាប់គឺผลต่าง
            change = difference;
        }

        document.getElementById("dueAmount").value = due.toFixed(2);
        document.getElementById("changeAmount").value = change.toFixed(2);
    }
</script>

@endsection --}}