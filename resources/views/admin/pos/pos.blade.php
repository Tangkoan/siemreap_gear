@extends('admin/admin_dashboard')
@section('admin')
    <!-- MAIN POS WRAPPER -->
    <div class="flex flex-col md:flex-row gap-4 p-4 bg-gray-50 font-sans no-print">
        <div class="flex-2 bg-white p-4 rounded shadow flex flex-col max-h-[88vh]">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold mb-2">POS</h2>
                    <!-- Product Section -->
                    <div class="w-64">
                        <input type="text" placeholder="Scan/Search Product by Name" 
                            class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                            id="searchBox" />
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm" id="allCategoryBtn">
                        All Category
                    </button>
                </div>
            </div>

                <!-- Product Grid -->
                <div class="flex-1 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-2">
                        <!-- Example Product Cards -->
                        @foreach($product as $key => $item)
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg cursor-pointer transform transition duration-200 hover:scale-105">

                                <div class="p-3 " style="width:140px; height: 50px;">
                                    <img class="w-full h-24 rounded-md" src="{{ asset($item->product_image) }}">
                                </div>

                                <br>
                                <br><br>

                                <div class="p-4 px-3">
                                    <h3 style="text-align: center;" class="font-semibold mb-2">{{ $item->product_name }}</h3>
                                    <p style="text-align: center;" class="text-blue-600 font-bold text-lg">${{ $item->selling_price }}</p>
                                </div>
                            </div>

                        @endforeach

                        <!-- Example Product Cards -->

                    </div>
                </div>

        </div>
        <!-- Order Summary Section -->
        <div class="flex-1 bg-white p-4 rounded shadow overflow-hidden max-h-[88vh]" id="detailSection">

            {{-- customer --}}
            <div class="form-group">
                <label for="customer" class="text-2xl block text-black  font-medium mb-1">
                    customer 
                </label>
                <select name="customer_id"
                    class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    id="example-select">
                    <option selected disabled>Select Customer </option>
                    @foreach($customer as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <br>

            <h2 class="text-2xl font-bold mb-4">Product Items</h2>
            <div class="mt-4 overflow-auto max-h-64 border rounded-lg shadow-sm">
                <table class="w-full text-auto border-collapse">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr class="text-left">
                            <th class="p-2">Product</th>
                            <th class="p-2">Price</th>
                            <th class="p-2">Qty</th>
                            <th class="p-2">Subtotal</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50 transition duration-200 blcock">
                            {{-- <td colspan="5" class="py-4 text-gray-500 text-center">No data Available</td> --}}

                            <td class="px-4">
                                <div class="flex flex-row space-x-4">
                                    <p style="bg-red-200 p-4 px-4"> 3434 </p>  
                                </div>
                            </td>

                            <td class="px-4">
                                <div class="flex flex-row space-x-4">
                                    <p style="bg-red-200 p-4 px-4"> 3434 </p>
                                </div>
                            </td>

                            <td class="px-4">
                                <div class="flex flex-row space-x-4">
                                    <p style="bg-red-200 p-4 px-4"> 3434 </p>
                                </div>
                            </td>

                            <td class="px-4">
                                <div class="flex flex-row space-x-4">
                                    <p style="bg-red-200 p-4 px-4"> 3434 </p>
                                </div>
                            </td>

                            <td class="px-4">
                                <div class="flex flex-row space-x-4">
                                    <p style="bg-red-200 p-4 px-4"> 3434 </p>
                                </div>
                            </td>

                        </tr>


                        

                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center text-lg font-semibold bg-teal-300 py-2 rounded" id="totalPayable">
                Total Payable : $ 0.00
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
                <input type="number" placeholder="Tax %" class="p-2 border rounded w-full" id="taxInput" value="" />
                <input type="number" placeholder="Discount $" class="p-2 border rounded w-full" id="discountInput"
                    value="" />
                <input type="number" placeholder="Shipping $" class="p-2 border rounded w-full" id="shippingInput"
                    value="" />
            </div>
            <div class="mt-4 flex gap-2 text-sm">
                <button id="payNowBtn" class="bg-green-500 text-white px-3 py-2 rounded w-full">Pay Now</button>
                <button class="bg-amber-700 text-white px-3 py-2 rounded w-full" id="cancelBtn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="paymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 no-print">
        <div class="bg-white rounded-lg max-w-2xl w-full p-4 relative shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Create Payment</h2>
                <button id="closePaymentModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/2">
                    <div class="mb-2">
                        <label class="block mb-1 font-medium" for="receivedAmount">Received Amount</label>
                        <input type="number" id="receivedAmount" class="w-full border border-gray-300 p-2 rounded"
                            placeholder="Enter amount" />
                    </div>
                    <div class="mt-4">
                        <label class="block mb-1 font-medium" for="customerName">Customer Name</label>
                        <input type="text" id="customerName" class="w-full border border-gray-300 p-2 rounded"
                            placeholder="Enter customer name" />
                    </div>
                    <div class="mt-4">
                        <label class="block mb-1 font-medium" for="customerPhone">Customer Phone</label>
                        <input type="text" id="customerPhone" class="w-full border border-gray-300 p-2 rounded"
                            placeholder="Enter customer phone" />
                    </div>
                    <div class="mt-4">
                        <label class="block mb-1 font-medium" for="invoiceValidity">Invoice Validity</label>
                        <input type="text" id="invoiceValidity" class="w-full border border-gray-300 p-2 rounded"
                            placeholder="e.g., 30 days" />
                    </div>
                </div>
                <div class="w-full md:w-1/2 bg-gray-100 p-4 rounded-lg shadow-inner">
                    <div class="mb-2 flex justify-between">Total Products: <span id="modalTotalProducts"></span></div>
                    <div class="mb-2 flex justify-between">Order Tax: <span id="modalOrderTax"></span></div>
                    <div class="mb-2 flex justify-between">Discount: <span id="modalDiscount"></span></div>
                    <div class="mb-2 flex justify-between">Shipping: <span id="modalShipping"></span></div>
                    <div class="mb-2 font-semibold flex justify-between">Total Payable: <span id="modalTotalPayable"></span>
                    </div>
                    <div class="mb-2 font-semibold flex justify-between">Change Due: <span id="modalChangeDue">$ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 w-full md:w-1/2">
                <label class="block mb-1 font-medium" for="paymentMethod">Payment choice</label>
                <select id="paymentMethod" class="w-full border border-gray-300 p-2 rounded">
                    <option value="" disabled selected>Select a payment</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="mt-4">
                <label class="block mb-1 font-medium" for="paymentNotes">Payment Notes</label>
                <textarea id="paymentNotes" class="w-full border border-gray-300 p-2 rounded" rows="3"
                    placeholder="Notes..."></textarea>
            </div>
            <div class="mt-4 flex gap-2">
                <button id="previewInvoiceBtn"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded">Preview
                    Invoice</button>
                <button id="submitPayment"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Submit Payment</button>
            </div>
        </div>
    </div>
    <!-- Invoice Modal -->
    <div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg max-w-3xl w-full p-6 relative shadow-lg overflow-y-auto max-h-[88vh]">
            <div class="flex justify-between items-center mb-4 no-print">
                <h2 class="text-2xl font-semibold">Invoice</h2>
                <button id="closeInvoice" class="text-gray-500 hover:text-gray-700">×</button>
            </div>
            <div id="invoiceContent" class="bg-white p-4 rounded shadow print-area">
                <!-- Your invoice content here -->
            </div>
            <div class="mt-4 flex justify-end gap-2 no-print">
                <button id="printInvoice"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Print</button>
            </div>
        </div>
    </div>






    <script src="{{ asset('backend/assets/js/pos.js') }}"></script> {{-- Correct way to include static JS --}}
@endsection