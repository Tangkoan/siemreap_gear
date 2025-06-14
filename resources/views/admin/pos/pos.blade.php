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
                            @foreach ($product as $key => $item)
                                <form method="POST" action="{{ url('/add-cart') }}" id="form-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <input type="hidden" name="name" value="{{ $item->product_name }}">
                                    <input type="hidden" name="qty" value="1">
                                    <input type="hidden" name="price" value="{{ $item->selling_price }}">

                                    <div class="bg-white rounded-lg overflow-hidden shadow-lg cursor-pointer transform transition duration-200 hover:scale-105"
                                        onclick="document.getElementById('form-{{ $item->id }}').submit();"
                                        title="Click to add to cart">

                                        <div class="p-3" style="width:140px; height: 50px;">
                                            <img class="w-full h-24 rounded-md" src="{{ asset($item->product_image) }}"
                                                alt="{{ $item->product_name }}">
                                        </div>

                                        <br><br><br>

                                        <div class="p-4 px-3">
                                            <h3 style="text-align: center;" class="font-semibold mb-2">{{ $item->product_name }}
                                            </h3>
                                            <p style="text-align: center;" class="text-blue-600 font-bold text-lg">
                                                ${{ $item->selling_price }}
                                            </p>
                                        </div>
                                    </div>
                                </form>
                            @endforeach
                            <!-- Example Product Cards -->
                        </div>
                    </div>


                </div>
                <!-- Order Summary Section -->
                <div class="flex-1 bg-white p-4 rounded shadow overflow-hidden max-h-[88vh]" id="detailSection">




                    <h2 class="text-2xl font-bold mb-4">Product Items</h2>
                    <div class="mt-4 overflow-auto max-h-64 border rounded-lg shadow-sm">
                        <table class="w-full text-auto border-collapse">
                            <thead class="bg-gray-100 sticky top-0 z-10">
                                <tr class="text-left">


                                    <th class="p-4">Product</th>
                                    <th class="p-4">Price</th>
                                    <th class="p-4">Qty</th>
                                    <th class="p-4">Subtotal</th>
                                    <th class="p-4">Action</th>


                                </tr>
                            </thead>

                            @php
    $allcart = Cart::content();
                            @endphp
                            <tbody>
                                @foreach ($allcart as $cart)
                                    <tr class="hover:bg-gray-50 transition duration-200 blcock">
                                        {{-- <td colspan="5" class="py-4 text-gray-500 text-center">No data Available</td> --}}

                                        <td class="px-4">
                                            {{ $cart->name }}
                                        </td>

                                        <td class="px-4">
                                            {{ $cart->price }}
                                        </td>

                                        <td class="px-2">
                                            <form method="post" action="{{ url('/cart-update/' . $cart->rowId) }}">
                                                @csrf
                                                <div class="flex items-center space-x-2">
                                                    <input name="qty" type="number" min="1" step="1"
                                                        value="{{ $cart->qty }}"
                                                        class="input-field-custom w-16 py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                        onchange="this.form.submit()"> <!-- បញ្ជូន Form នៅពេលប្តូរតម្លៃ -->
                                                </div>
                                            </form>

                                        </td>




                                        <td class="px-4">
                                            <div class="flex flex-row space-x-4">
                                                <p style="bg-red-200 p-4 px-4"> {{ $cart->price * $cart->qty }} </p>
                                            </div>
                                        </td>

                                        <td class="px-4">
                                            <button type="button"
                                                class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                                <a href="{{ url('/cart-remove/' . $cart->rowId) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </a>
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center text-lg font-semibold bg-teal-300 py-2 rounded" id="totalPayable">
                        Total Payable : $ {{ Cart::subtotal() }}
                    </div>
                    <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
                        <input type="number" placeholder="Tax %" class="p-2 border rounded w-full" id="taxInput" value="" />
                        <input type="number" placeholder="Discount $" class="p-2 border rounded w-full" id="discountInput"
                            value="" />
                        <input type="number" placeholder="Shipping $" class="p-2 border rounded w-full" id="shippingInput"
                            value="" />
                    </div>

                    <form id="myForm" method="post" action="{{ url('/create-invoice') }}">
                        @csrf
                        {{-- customer --}}
                        <div class="form-group">
                            <label for="customer" class="text-2xl block text-black  font-medium mb-1">
                                customer
                            </label>
                            <select name="customer_id"
                                class=" input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                id="example-select">
                                <option selected disabled>Select Customer </option>
                                @foreach ($customer as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <button onclick="openPrintPopup()" id="payNowBtn" class="bg-green-500 text-white px-3 py-2 rounded w-full">Pay Now</button>
                    </form>

                    {{-- <div class="mt-4 flex gap-2 text-sm">

                                                                                                                <button class="bg-amber-700 text-white px-3 py-2 rounded w-full" id="cancelBtn">Cancel</button>
                                                                                                            </div> --}}
                </div>









                

            </div>

            






            <!-- Include Local jQuery -->
            <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
            <!-- Include Local jQuery Validation -->
            <script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>


            <script src="{{ asset('backend/assets/js/pos.js') }}"></script> {{-- Correct way to include static JS --}}





            <script type="text/javascript">
                $(document).ready(function() {
                    $('#myForm').validate({
                        rules: {
                            customer_id: {
                                required: true,
                            },

                        },
                        messages: {
                            customer_id: {
                                required: 'Please Select Customer',
                            },


                        },
                        errorElement: 'span',
                        errorPlacement: function(error, element) {
                            error.addClass('invalid-feedback');
                            element.closest('.form-group').append(error);
                        },
                        highlight: function(element, errorClass, validClass) {
                            $(element).addClass('is-invalid');
                        },
                        unhighlight: function(element, errorClass, validClass) {
                            $(element).removeClass('is-invalid');
                        },
                    });
                });
            </script>
@endsection
