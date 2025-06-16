@extends('admin/admin_dashboard')
@section('admin')
                <style>
                    /* Style នេះនឹងដំណើរការតែពេលបញ្ជាឱ្យបោះពុម្ពប៉ុណ្ណោះ */
                    @media print {

                        /* * ជំហានទី១៖ លាក់អ្វីៗទាំងអស់នៅលើទំព័រជាមុនសិន
                                                                                                                                                 * នេះរាប់បញ្ចូលទាំង Header, Sidebar, Footer របស់เว็บអ្នក
                                                                                                                                                */
                        body * {
                            visibility: hidden;
                        }

                        /* * ជំហានទី២៖ បង្ហាញតែส่วนវិក្កយបត្រ និងអ្វីៗទាំងអស់ដែលនៅក្នុងនោះ
                                                                                                                                                */
                        #invoice-box,
                        #invoice-box * {
                            visibility: visible;
                        }

                        /* * ជំហានទី៣៖ កំណត់ទីតាំងវិក្កយបត្រឲ្យពេញក្រដាសតែម្ដង
                                                                                                                                                 * ដើម្បីចៀសវាងបញ្ហាទំព័រទទេ
                                                                                                                                                */
                        #invoice-box {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                        }

                        /* * ជំហានទី៤៖ លុប Header/Footer របស់ Browser និងកំណត់ទំហំក្រដាស
                                                                                                                                                */
                        @page {
                            size: A5;
                            margin: 0;
                            /* ការដាក់ margin: 0 ជួយលុប Header/Footer របស់ Browser */
                        }
                    }
                </style>

                <div class="page-content w-full h-full m-4">

                    {{-- <div class="mb-4 text-center">
                                                                                                                                        <button onclick="window.print()" class="btn btn-primary">
                                                                                                                                            <i class="fa-solid fa-print"></i> 🖨️ បោះពុម្ពវិក្កយបត្រ
                                                                                                                                        </button>
                                                                                                                                    </div> --}}

                    <!-- Button Print -->
                    <div class="mb-4 no-print text-right">
                        <button onclick="openPrintPopup()" class="button-add text-white font-bold py-2 px-4 rounded shadow-lg">
                            🖨️ Print
                        </button>
                    </div>


                    <!-- Modal Popup with Animation -->
                    <div id="printPopup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                        <div id="popupContent"
                            class="bg-white p-6 rounded shadow-lg w-full max-w-md transform scale-95 opacity-0 transition-all duration-300">
                            <h2 class="text-xl font-bold mb-4">
                                Invoice Of {{ $customer->name }}
                                <h3>Total Amount ${{ Cart::subtotal() }}</h3>
                            </h2>

                            @php
                                $sub_total = (float) str_replace(',', '', Cart::subtotal()); // ចម្លង subtotal ពី backend
                            @endphp

                            <form class="px-3" method="post" action="{{ url('/final-invoice') }}">
                                @csrf

                                <label class="block mb-2">Payment :</label>
                                <select name="payment_status" id="example-select" class="w-full p-2 border rounded mb-4">
                                    <option selected disabled>Select Payment </option>
                                    <option value="HandCash">HandCash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Due">Due</option>
                                </select>

                                <label class="block mb-2">Pay Now:</label>
                                <input type="number" name="pay" id="payNow" class="w-full p-2 border rounded mb-4"
                                    placeholder="Pay Now" oninput="calculateDue()" min="0">

                                <label class="block mb-2">Due Amount:</label>
                                <input type="text" name="due" id="dueAmount" class="w-full p-2 border rounded mb-4"
                                    placeholder="Due amount" readonly>

                                <label class="block mb-2">Change:</label>
                                <input type="text" name="change" id="changeAmount" class="w-full p-2 border rounded mb-4" placeholder="Change" readonly>

                                <!-- Hidden Fields -->
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="order_date" value="{{ date('d-F-Y') }}">
                                <input type="hidden" name="order_status" value="pending">
                                <input type="hidden" name="total_products" value="{{ Cart::count() }}">
                                <input type="hidden" name="sub_total" id="subTotal" value="{{ $sub_total }}">
                                <input type="hidden" name="vat" value="{{ Cart::tax() }}">
                                <input type="hidden" name="total" value="{{ Cart::total() }}">

                                <div class="flex justify-end">
                                    <button onclick="closePrintPopup()" type="button"
                                        class="bg-gray-300 text-black px-4 py-2 rounded mr-2">Cancel</button>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Print</button>
                                </div>
                            </form>
                        </div>

                    </div>









































                    <div id="invoice-box" class="w-full h-full px-4 bg-white p-4 mx-auto box-border text-xs border">

                        <div class="flex items-center">
                            <div class="w-1/3">
                                <img src="" class="w-full h-auto">
                            </div>
                            <div class="mb-4 no-print text-right">
                                <h1 class="text-2xl font-bold text-center mb-2">សៀមរាប ហ្គៀ</h1>
                                <h3 class="text-lg text-center mb-4">SIEM REAP GEARS</h3>
                            </div>
                        </div>

                        <div class="flex justify-between mb-4">
                            <div class="w-1/2 bg-gray-50 p-2 rounded shadow-sm mr-2">
                                <h4 class="font-semibold mb-1">Company: SR Gears</h4>
                                <h4 class="font-semibold mb-1 mt-2">Address:</h4>
                                <p>#C02, St.Kompea Mother, MonduI I Village, Svay Dongkom Commune, SiemReap Town</p>
                                <h4 class="font-semibold mb-1 mt-2">Tel: 098 222 500, 017 3000 31</h4>
                            </div>
                            <div class="w-1/2 bg-gray-50 p-2 rounded shadow-sm ml-2">
                                <h4 class="font-semibold mb-1">Quotation</h4>
                                <p><span class="font-semibold">Invoice Date:</span> {{ now()->format('d-M-Y') }}</p>
                                <p><span class="font-semibold">Customer Name:</span> {{ $customer->name }} </p>
                                <p><span class="font-semibold">Validity:</span> </p>
                                <p><span class="font-semibold">Phone:</span> {{ $customer->phone }} </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto mb-4">
                            <table class="w-full border-collapse border border-gray-300 text-xs shadow-sm">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border border-gray-300 px-2 py-1">No</th>
                                        <th class="border border-gray-300 px-2 py-1">Product & Description</th>
                                        <th class="border border-gray-300 px-2 py-1">Price</th>
                                        <th class="border border-gray-300 px-2 py-1">QTY</th>
                                        <th class="border border-gray-300 px-2 py-1">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
    $sl = 1;
                                    @endphp
                                    @foreach ($contents as $key => $item)
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $loop->iteration }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->name }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->price }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->qty }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->price * $item->qty }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


                <script>
                    function openPrintPopup() {
                        const popup = document.getElementById("printPopup");
                        const content = document.getElementById("popupContent");

                        popup.classList.remove("hidden");

                        // បន្ថែម animation class
                        setTimeout(() => {
                            content.classList.remove("scale-95", "opacity-0");
                            content.classList.add("scale-100", "opacity-100");
                        }, 10);
                    }

                    function closePrintPopup() {
                        const popup = document.getElementById("printPopup");
                        const content = document.getElementById("popupContent");

                        // Animation close effect
                        content.classList.remove("scale-100", "opacity-100");
                        content.classList.add("scale-95", "opacity-0");

                        // បិទ popup បន្ទាប់ពី animation (delay 300ms)
                        setTimeout(() => {
                            popup.classList.add("hidden");
                        }, 300);
                    }

                    function submitPrint() {
                        const name = document.getElementById("printName").value;
                        const quantity = document.getElementById("printQuantity").value;
                        const type = document.getElementById("printType").value;

                        if (!name || !quantity || !type) {
                            alert("សូមបំពេញទិន្នន័យទាំងអស់!");
                            return;
                        }

                        console.log("Printing with:", name, quantity, type);

                        closePrintPopup(); // បិទ popup

                        // Print
                        window.print();
                    }


                    // function calculateDue() {
                    //     let subTotal = parseFloat(document.getElementById("subTotal").value) || 0;
                    //     let payNow = parseFloat(document.getElementById("payNow").value) || 0;

                    //     let due = subTotal - payNow;
                    //     if (due < 0) {
                    //         due = 0;
                    //     }

                    //     document.getElementById("dueAmount").value = due.toFixed(2);
                    // }

                    function calculateDue() {
                        let payNow = parseFloat(document.getElementById("payNow").value) || 0;
                        let subTotal = parseFloat(document.getElementById("subTotal").value) || 0;

                        let due = subTotal - payNow;
                        let change = 0;

                        if (due < 0) {
                            change = Math.abs(due); // ប្រាក់អាប់
                            due = 0; // បើបង់លើស ប្រាក់ជំពាក់គ្មានទេ
                        }

                        document.getElementById("dueAmount").value = due.toFixed(2);
                        document.getElementById("changeAmount").value = change.toFixed(2);
                    }

                </script>
@endsection
