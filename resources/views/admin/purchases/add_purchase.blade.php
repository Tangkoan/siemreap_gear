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

                <!-- Category Filter -->
                <div class="w-full overflow-x-auto whitespace-nowrap mb-4" id="category-buttons">
                    <button onclick="loadProducts('all')"
                        class="dark:bg-gray-800 bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                        All Category
                    </button>
                    @foreach ($categories as $category)
                        <button onclick="loadProducts({{ $category->id }})"
                            class="dark:bg-gray-800 bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                            {{ $category->category_name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- PRODUCT LIST -->
            <div class="flex-1 overflow-y-auto dark:bg-gray-500">
                <div id="product-grid"
                    class="p-4 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-2">
                    <!-- Products loaded by JS -->
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

            <div class="mt-4 text-center text-lg font-semibold bg-teal-300 py-2 rounded">
                Total Payable : $ {{ Cart::subtotal() }}
            </div>

            <!-- SUPPLIER SELECTION -->
            <form method="POST" action="{{ url('/purchase/store') }}">
                @csrf
                <label for="supplier" class="block mt-4 mb-2 text-gray-800">Supplier</label>
                <select name="supplier_id" required
                    class="w-full py-2 px-4 border border-gray-300 rounded dark:bg-gray-800 dark:text-white">
                    <option value="" disabled selected>Select Supplier</option>
                    @foreach ($supplier as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                    @endforeach
                </select>

                <input type="hidden" name="payment_status" value="Paid">
                <input type="hidden" name="paid" value="{{ Cart::subtotal() }}">
                <input type="hidden" name="due" value="0">
                <input type="hidden" name="total" value="{{ Cart::subtotal() }}">

                <button type="submit" class="mt-4 w-full bg-green-600 text-white py-2 px-4 rounded">
                    Complete Purchase
                </button>
            </form>
        </div>
    </div>

    <script>
        function loadProducts(categoryId = 'all') {
            fetch(`/api/purchase/products?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = '';

                    data.products.forEach(product => {
                        const card = `
                            <form method="POST" action="/purchase/add-to-cart" id="form-${product.id}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="${product.id}">
                                <input type="hidden" name="name" value="${product.name}">
                                <input type="hidden" name="qty" value="1">
                                <input type="hidden" name="price" value="${product.price}">

                                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md cursor-pointer hover:scale-105"
                                    onclick="document.getElementById('form-${product.id}').submit();">
                                    <img src="${product.imageUrl}" class="w-full h-24 object-cover">
                                    <div class="p-4">
                                        <h3 class="font-semibold text-center">${product.name}</h3>
                                        <p class="text-blue-600 font-bold text-center mt-2">$${product.price}</p>
                                    </div>
                                </div>
                            </form>
                        `;
                        productGrid.innerHTML += card;
                    });
                });
        }

        window.onload = () => loadProducts();

        document.getElementById('searchBox').addEventListener('input', function () {
            const keyword = this.value;
            fetch(`/search-products?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = '';

                    data.products.forEach(product => {
                        const card = `... same as above ...`;
                        productGrid.innerHTML += card;
                    });
                });
        });
    </script>
@endsection