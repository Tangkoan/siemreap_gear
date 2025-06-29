@extends('admin/admin_dashboard')
@section('admin')

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-box,
            #invoice-box * {
                visibility: visible;
            }

            #invoice-box {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            @page {
                size: A5;
                margin: 0;
            }
        }
    </style>

    <div class="flex flex-col md:flex-row gap-4 p-4 bg-gray-50 font-sans no-print w-full dark:bg-gray-800">
        <!-- LEFT SIDE - PRODUCT BROWSER -->
        <div class="flex-2 bg-white p-4 rounded shadow flex flex-col max-h-[88vh] dark:bg-gray-900">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold mb-2 dark:text-white">Purchase</h2>
                    <div class="w-64 mb-4">
                        <input type="text" placeholder="Search Product by Name" id="searchBox"
                            class="dark:bg-gray-800 w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>

                {{-- Category Get --}}
                <div class="w-full sm:w-[200px] md:w-[400px] lg:w-[500px] overflow-x-auto whitespace-nowrap mb-4" id="category-buttons">
                    <button onclick="loadProducts('all')"
                        class="dark:bg-gray-800 inline-block bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                        All Category
                    </button>
                    @foreach ($categories as $category)
                        <button onclick="loadProducts({{ $category->id }})"
                            class="dark:bg-gray-800 inline-block bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                            {{ $category->category_name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- <!-- PRODUCT LIST -->
            <div class="flex-1 overflow-y-auto dark:bg-gray-500">
                <div id="product-grid"
                    class="p-4 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-2">
                    <!-- Products loaded by JS -->
                </div>
            </div> --}}

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto  dark:bg-gray-500">
                <div id="product-grid" class="p-4 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-2">
                    <!-- Products will be dynamically loaded here -->
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - PURCHASE CART -->
        <div class="dark:bg-gray-900 flex-1 bg-white p-4 rounded shadow overflow-hidden max-h-[88vh]">
            <h2 class="text-xl font-bold mb-4">Purchase Cart</h2>
            <div class="dark:bg-gray-800 mt-4 overflow-auto max-h-64 border rounded-lg shadow-sm">
                <table class="w-full text-auto border-collapse">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr class="text-left dark:bg-gray-800">
                            <th class="p-2">Product</th>
                            <th class="p-2">Price</th>
                            <th class="p-2">Qty</th>
                            <th class="p-2">Subtotal</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Cart::content() as $cart)
                            <tr class="dark:bg-gray-800">
                                <td class="px-4">{{ $cart->name }}</td>
                                <td class="px-4">{{ $cart->price }}</td>
                                <td class="px-2">
                                    <form method="post" action="{{ url('/purchase/cart/update/' . $cart->rowId) }}">
                                        @csrf
                                        <input name="qty" type="number" min="1" value="{{ $cart->qty }}"
                                            class="w-16 py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200"
                                            onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="px-4">{{ $cart->price * $cart->qty }}</td>
                                <td class="px-4">
                                    <a href="{{ url('/purchase/cart/remove/' . $cart->rowId) }}" class="text-red-500">
                                        Remove
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-center text-lg font-semibold bg-teal-300 py-2 rounded dark:bg-gray-700">
                Total Payable : $ {{ Cart::subtotal() }}
            </div>

            <form method="POST" action="{{ url('/purchase/store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block mb-1 font-medium text-gray-800 dark:text-white">Supplier</label>
                    <select name="supplier_id" id="supplier_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Supplier</option>
                        @foreach ($supplier as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_status" class="block mb-1 font-medium text-gray-800 dark:text-white">Payment Method</label>
                    <select name="payment_status" id="payment_status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Payment</option>
                        <option value="HandCash">HandCash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Due">Due</option>
                    </select>
                </div>

                <!-- Pay Now -->
                <div>
                    <label for="payNow" class="block mb-1 font-medium text-gray-800 dark:text-white">Pay Now ($)</label>
                    <input type="number" name="pay" id="payNow" placeholder="Pay Now" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="calculateDue()" required>
                </div>

                <!-- Discount -->
                <div>
                    <label for="discount" class="block mb-1 font-medium text-gray-800 dark:text-white">Discount ($)</label>
                    <input type="number" name="discount" id="discount" placeholder="Discount"
                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="calculateDue()" min="0" value="0" required>
                </div>

                <!-- Hidden Fields (keep them outside of visible grid) -->
                <input type="hidden" name="paid" id="paidHidden" value="{{ Cart::subtotal() }}">
                <input type="hidden" name="due" id="dueHidden" value="0">
                <input type="hidden" name="total" value="{{ Cart::subtotal() }}">

                <!-- Submit Button (span both columns) -->
                <div class="md:col-span-2">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-200">
                        Complete Purchase
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Include Local jQuery -->
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Include Local jQuery Validation -->
    <script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>


    <script src="{{ asset('backend/assets/js/pos.js') }}"></script> {{-- Correct way to include static JS --}}





    <script type="text/javascript">
        $(document).ready(function () {
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });

        function loadProducts(categoryId = 'all') {
            fetch(`/get-products?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = '';

                    data.products.forEach(product => {
                        const card =
                            `
                                                                                                                                                    <form method="POST" action="/add-cart" id="form-${product.id}">
                                                                                                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                                                                                                        <input type="hidden" name="id" value="${product.id}">
                                                                                                                                                        <input type="hidden" name="name" value="${product.name}">
                                                                                                                                                        <input type="hidden" name="qty" value="1">
                                                                                                                                                        <input type="hidden" name="price" value="${product.price}">

                                                                                                                                                        <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg cursor-pointer transform transition duration-200 hover:scale-105"
                                                                                                                                                            onclick="document.getElementById('form-${product.id}').submit();"
                                                                                                                                                            title="Click to add to cart">

                                                                                                                                                            <div class="p-3" style="width:150px; height: 50px;">
                                                                                                                                                                <img class="w-full h-24 rounded-md" src="${product.imageUrl}" alt="${product.name}">
                                                                                                                                                            </div>

                                                                                                                                                            <br><br><br>

                                                                                                                                                            <div class="p-4 px-3">
                                                                                                                                                                <h3 class="font-semibold mb-2 text-center">${product.name}</h3>
                                                                                                                                                                <p class="text-blue-600 font-bold text-lg text-center">$${product.price}</p>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </form>
                                                                                                                                                `;
                        productGrid.innerHTML += card;
                    });
                });
        }

        // Load all products initially
        window.onload = () => loadProducts();


        // Search
        document.getElementById('searchBox').addEventListener('input', function () {
            const keyword = this.value;

            fetch(`/search-products?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = ''; // Clear old results

                    data.products.forEach(product => {
                        const card = `
                                <form method="POST" action="/add-cart" id="form-${product.id}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="${product.id}">
                                    <input type="hidden" name="name" value="${product.name}">
                                    <input type="hidden" name="qty" value="1">
                                    <input type="hidden" name="price" value="${product.price}">

                                        <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg cursor-pointer transform transition duration-200 hover:scale-105"
                                        onclick="document.getElementById('form-${product.id}').submit();"
                                        title="Click to add to cart">

                                        <div class="p-3" style="width:150px; height: 50px;">
                                            <img class="w-full h-24 rounded-md" src="${product.imageUrl}" alt="${product.name}">
                                        </div>

                                        <br><br><br>

                                        <div class="p-4 px-3">
                                            <h3 class="font-semibold mb-2 text-center">${product.name}</h3>
                                            <p class="text-blue-600 font-bold text-lg text-center">$${product.price}</p>
                                        </div>
                                    </div>
                                    </form>
                            `;
                        productGrid.innerHTML += card;
                    });
                });
        });
    </script>
@endsection