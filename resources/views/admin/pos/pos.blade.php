@extends('admin/admin_dashboard')
@section('admin')

                    <style>
                        /* Style នេះនឹងដំណើរការតែពេលបញ្ជាឱ្យបោះពុម្ពប៉ុណ្ណោះ */
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

                    <div class="flex flex-col md:flex-row gap-4 m-1 p-4 bg-gray-50 font-sans no-print w-full dark:bg-gray-800">
                        <div class="flex-2 bg-white p-4 rounded shadow flex flex-col max-h-[88vh] dark:bg-gray-900">
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-2xl font-bold mb-2 dark:text-white">{{ __('messages.pos') }}</h2>
                                    <div class="w-64 mb-4">
                                        <input type="text" placeholder="{{ __('messages.search') }}"
                                            class="dark:bg-gray-900 w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            id="searchBox" />
                                    </div>
                                </div>

                                {{-- Category Get --}}
                                <div class="w-full sm:w-[200px] md:w-[400px] lg:w-[500px] overflow-x-auto whitespace-nowrap mb-4"
                                    id="category-buttons">
                                    <button onclick="loadProducts('all')"
                                        class="dark:bg-gray-800 m-1 inline-block bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                                        {{ __('messages.all_category') }}
                                    </button>
                                    @foreach ($categories as $category)
                                        <button onclick="loadProducts({{ $category->id }})"
                                            class="dark:bg-gray-800 inline-block bg-gray-200 px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
                                            {{ $category->category_name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto  dark:bg-gray-800">
                                <div id="product-grid"
                                    class="p-4 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-2">
                                </div>
                            </div>
                        </div>

                        <div class="dark:bg-gray-900 flex-1 bg-white p-4 rounded shadow overflow-y-auto h-full" id="detailSection">
                            <h2 class="text-xl font-bold mb-4">{{ __('messages.product_items') }}</h2>
                            <div class="dark:bg-gray-800 mt-4 overflow-auto max-h-64 border rounded-lg shadow-sm">
                                <table class="w-full text-auto border-collapse">
                                    <thead class="bg-gray-100 sticky top-0 z-10">
                                        <tr class="text-left dark:bg-gray-800 hover:dark:dark:bg-gray-500">
                                            <th class="p-2">{{ __('messages.product') }}</th>
                                            <th class="p-2">{{ __('messages.price') }}</th>
                                            <th class="p-2">{{ __('messages.qty') }}</th>
                                            <th class="p-2">{{ __('messages.subtotal') }}</th>
                                            <th class="p-2">{{ __('messages.table_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-table-body">
                                        {{-- Cart items will be dynamically loaded here --}}
                                        @php
    $allcart = Cart::content();
                                        @endphp
                                        @foreach ($allcart as $cart)
                                            <tr
                                                class="hover:bg-gray-50 transition duration-200 blcock dark:bg-gray-800 hover:dark:dark:bg-gray-500">
                                                <td class="px-4">{{ $cart->name }}</td>
                                                <td class="px-4">{{ $cart->price }}</td>
                                                <td class="px-2 ">
                                                    {{-- យើងលែងប្រើ form ធម្មតាសម្រាប់ update ទៀតហើយ --}}
                                                    <div class="flex items-center space-x-2">
                                                        <input name="qty" type="number" min="1" step="1" value="{{ $cart->qty }}"
                                                            data-rowid="{{ $cart->rowId }}" {{-- បន្ថែម data-rowid --}}
                                                            class="input-field-custom w-16 py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                    </div>
                                                </td>
                                                <td class="px-4">
                                                    <div class="flex flex-row space-x-4">
                                                        <p>{{ $cart->price * $cart->qty }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-4">
                                                    <button type="button"
                                                        class=" icon-delete  focus:outline-none"
                                                        onclick="removeCartItem('{{ $cart->rowId }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-center text-lg font-semibold bg-teal-300 py-2 rounded dark:bg-gray-700">
                                {{ __('messages.subtotal') }}: $<span id="subtotal">{{ Cart::subtotal() }}</span><br>
                                {{ __('messages.total_payable') }} : <span id="totalPayable">{{ Cart::subtotal() }}</span>
                            </div>
                            <br>
                            <form method="POST" id="myForm" action="{{ url('/store-sell') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @csrf

                                

                                <div class="form-group">
                                    <label for="customer_id" class="block mb-1 font-medium text-gray-800 dark:text-white">
                                        {{ __('messages.customer_name') }}
                                    </label>
                                    
                                    {{-- ចាប់ផ្តើមចម្លង មកដាក់នៅត្រង់នេះ --}}
                                    <div class="group relative flex items-center rounded-lg border border-gray-300 bg-white transition-all duration-200 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:focus-within:border-indigo-500">
                                        
                                        <select name="customer_id" id="customer_id" 
                                                class="w-full appearance-none border-none rounded-lg bg-transparent px-4 py-2 pr-14 focus:outline-none focus:ring-0 dark:text-white dark:bg-gray-700">
                                            <option value="" disabled selected>{{ __('messages.select_customer') }}</option>
                                            @foreach ($customers as $cus)
                                                <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                                            @endforeach
                                        </select>

                                        {{-- ប៊ូតុង Add Customer ប្រើ Icon ដូច Supplier --}}
                                        <button type="button" id="add-customer-btn" 
                                                title="Add New Customer"
                                                class="absolute inset-y-0 right-5 flex items-center rounded-r-lg px-4 text-gray-500 transition hover:text-red-600 focus:outline-none dark:text-gray-400 dark:hover:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                        
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="payment_status" class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __('messages.payment_method') }}</label>
                                    <select name="payment_status" id="payment_status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>{{ __('messages.select_payment') }}</option>
                                        <option value="QrScan">Qr Scan</option>
                                        <option value="HandCash">HandCash</option>
                                        <option value="Due">Due</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="payNow" class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __('messages.pay') }} ($)</label>
                                    <input type="number" name="pay" id="payNow" placeholder="{{ __('messages.pay_now') }}" min="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        oninput="calculateDue()" >
                                </div>

                                <div class="form-group">
                                    <label for="discount" class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __('messages.discount') }}
                                        ($)</label>
                                    <input type="number" name="discount" id="discount" placeholder="Discount"
                                        class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        oninput="calculateDue()" min="0" value="0">
                                </div>

                                <input type="hidden" name="order_date" id="order_date" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="total" id="orderTotalHidden" value="{{ Cart::subtotal() }}">
                                <input type="hidden" name="due" id="dueHidden" value="0">

                                <div class="md:col-span-2">
                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-200">
                                        {{ __('messages.pay_nows') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Customer --}}
                    <div id="add-customer-modal" 
                    class="hidden fixed inset-0 z-50 overflow-y-auto 
                            bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
                        <div class="relative top-20 mx-auto w-full max-w-lg transform rounded-xl border border-slate-200/50 
                            bg-white/80 p-6 shadow-2xl transition-all duration-300 
                            dark:border-slate-700/50 dark:bg-slate-900/80">
                    
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    {{ __('messages.add_new_customer') }}</h3>

                                <form id="addCustomerForm" class="mt-4 text-left space-y-4">
                                    @csrf
                                    <div>
                                        <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ __('messages.customer_name') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" id="customer_name"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                                        <div id="name_error" class="text-red-500 text-sm mt-1"></div>
                                    </div>

                                    <div>
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.address') }}</label>
                                        <input type="text" name="address" id="customer_address"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                                        <div id="address_error" class="text-red-500 text-sm mt-1"></div>
                                    </div>

                                    <div>
                                        <label for="customer_phone"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.phone') }}</label>
                                        <input type="text" name="phone" id="customer_phone"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                    <div>
                                        <label for="notes"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.notes') }}</label>
                                        <textarea name="notes" id="notes" rows="2"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600"></textarea>
                                    </div>

                                    <div class="items-center px-4 py-3 flex justify-end gap-x-3">
                                        <button id="cancel-add-customer" type="button"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none">
                                            {{ __('messages.cancel') }}
                                        </button>
                                        <button id="save-customer-btn" type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none">
                                            {{ __('messages.save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
                    <script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>
                    <script src="{{ asset('backend/assets/js/pos.js') }}"></script> {{-- Correct way to include static JS --}}

                    <script type="text/javascript">
                        document.addEventListener("DOMContentLoaded", function () {
                            const today = new Date().toISOString().split('T')[0];
                            document.getElementById("order_date").value = today;

                            // Initial load of products
                            loadProducts();
                            // Initial load of cart items
                            updateCartDisplay({{ Js::from(Cart::content()) }}, {{ Cart::subtotal() }});
                        
                        
                            // customer JS
                            const modal = document.getElementById('add-customer-modal');
                            const addCustomerBtn = document.getElementById('add-customer-btn');
                            const cancelBtn = document.getElementById('cancel-add-customer');
                            const addCustomerForm = document.getElementById('addCustomerForm');

                            // បើក Modal
                            addCustomerBtn.addEventListener('click', () => {
                                modal.classList.remove('hidden');
                            });

                            // បិទ Modal
                            cancelBtn.addEventListener('click', () => {
                                modal.classList.add('hidden');
                                clearFormErrors(); // សម្អាត error messages ចាស់ៗ
                                addCustomerForm.reset(); // សម្អាត input fields
                            });

                            // បិទ Modal ពេលចុចក្រៅ
                            window.addEventListener('click', (e) => {
                                if (e.target == modal) {
                                    modal.classList.add('hidden');
                                }
                            });

                            // AJAX function to save new Customer
                            addCustomerForm.addEventListener('submit', function (e) {
                                e.preventDefault();
                                clearFormErrors();

                                const formData = new FormData(this);

                                fetch("{{ route('store.customer.ajax') }}", {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        // FIX: បន្ថែម CSRF Token ទៅក្នុង Header
                                        'X-CSRF-TOKEN': formData.get('_token') 
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json().then(data => Promise.reject(data));
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Success case
                                    toastr.success(data.message);

                                    const customerSelect = document.getElementById('customer_id');
                                    const newOption = new Option(data.newCustomer.name, data.newCustomer.id, true, true);
                                    customerSelect.add(newOption, null);
                                    
                                    // Close and reset the modal
                                    modal.classList.add('hidden');
                                    addCustomerForm.reset();
                                })
                                .catch(errorData => {
                                    // Error case (e.g., validation failed)
                                    if (errorData.errors) {
                                        Object.keys(errorData.errors).forEach(key => {
                                            const errorElement = document.getElementById(`${key}_error`);
                                            if (errorElement) {
                                                errorElement.textContent = errorData.errors[key][0];
                                            }
                                        });
                                        toastr.warning('{{ __('messages.errors') }}');
                                    } else {
                                        toastr.error('An unexpected error occurred.');
                                        console.error('Submission error:', errorData);
                                    }
                                });
                            });
                            function clearFormErrors() {
                                document.querySelectorAll('#addCustomerForm [id$="_error"]').forEach(el => {
                                    el.textContent = '';
                                });
                            }
                        });
                        
                        
                        
                        
                        
                        
                        
                      

                        


                        // Function to update the cart display
                        function updateCartDisplay(cartContent, subtotal) {
                        const cartTableBody = document.getElementById('cart-table-body');
                        cartTableBody.innerHTML = '';

                        // រក្សាទុកតម្លៃសរុបថ្មីទៅក្នុងអថេរ
                        originalSubtotal = parseFloat(subtotal) || 0;

                        if (Object.keys(cartContent).length === 0) {
                            cartTableBody.innerHTML = `<tr><td colspan="5" class="py-4 text-gray-500 text-center">{{ __('messages.no_items_in_cart') }}</td></tr>`;
                        } else {
                            for (const rowId in cartContent) {
                                const item = cartContent[rowId];
                                const row = `
                                    <tr class="hover:bg-gray-50 transition duration-200 blcock dark:bg-gray-800 hover:dark:dark:bg-gray-500">
                                        <td class="px-4">${item.name}</td>
                                        <td class="px-4">${item.price}</td>
                                        <td class="px-2">
                                            <div class="flex items-center space-x-2">
                                                <input name="qty" type="number" min="1" step="1" value="${item.qty}"
                                                    data-rowid="${item.rowId}"
                                                    class="qty-input w-16 py-2.5 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                            </div>
                                        </td>
                                        <td class="px-4">
                                            <div class="flex flex-row space-x-4">
                                                <p>${(item.price * item.qty).toFixed(2)}</p>
                                            </div>
                                        </td>
                                        <td class="px-4">
                                            <button type="button" class="icon-delete focus:outline-none"
                                                    onclick="removeCartItem('${item.rowId}')">
                                                {{-- START: បន្ថែមកូដ SVG នៅត្រង់នេះ --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                {{-- END: បន្ថែមកូដ SVG នៅត្រង់នេះ --}}
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                cartTableBody.innerHTML += row;
                            }
                        }

                        // យើងលែង update total payable នៅទីនេះទៀតហើយ គឺទុកឲ្យ calculateDue ជាអ្នកធ្វើแทน
                        calculateDue(); // ហៅ calculateDue ដើម្បីគណនា និងបង្ហាញតម្លៃសរុបថ្មី
                        attachCartEventListeners();
                    }

                        // Function to handle adding product to cart via AJAX
                        function addProductToCartAjax(id, name, qty, price) {
                            const formData = new FormData();
                            formData.append('_token', "{{ csrf_token() }}");
                            formData.append('id', id);
                            formData.append('name', name);
                            formData.append('qty', qty);
                            formData.append('price', price);

                            fetch("/add-cart", { // Direct API endpoint
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest' // Important for Laravel to recognize AJAX request
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    // លុបការបង្ហាញ Toastr Message ចេញ
                                    // if (data.message) {
                                    //     toastr[data['alert-type']](data.message);
                                    // }
                                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                                })
                                .catch(error => {
                                    console.error('Error adding product to cart:', error);
                                    toastr["error"]("Failed to add product to cart.");
                                });
                        }


                        // Function to handle updating cart quantity via AJAX
                        // ឥឡូវនេះយើងនឹងហៅ function នេះផ្ទាល់ពី input's onchange event
                        function updateCartQuantity(event) {
                            const input = event.currentTarget;
                            const rowId = input.dataset.rowid; // ទទួលបាន rowId ពី data-rowid attribute
                            const newQty = input.value;

                            // ពិនិត្យមើលថា Quantity មិនទទេ ឬជា 0
                            if (newQty === '' || parseInt(newQty) <= 0) {
                                // optionally set to 1 or show error
                                return;
                            }

                            fetch(`/cart-update/${rowId}`, {
                                method: 'POST',
                                body: new URLSearchParams({
                                    _token: "{{ csrf_token() }}", // ប្រើ CSRF token ដោយផ្ទាល់
                                    qty: newQty
                                }),
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.message) {
                                        toastr[data['alert-type']](data.message);
                                    }
                                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                                })
                                .catch(error => {
                                    console.error('Error updating cart quantity:', error);
                                    toastr["error"]("Failed to update cart quantity.");
                                });
                        }

                        // Function to handle removing cart item via AJAX
                        function removeCartItem(rowId) {
                            fetch(`/cart-remove/${rowId}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.message) {
                                        toastr[data['alert-type']](data.message);
                                    }
                                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                                })
                                .catch(error => {
                                    console.error('Error removing cart item:', error);
                                    toastr["error"]("Failed to remove cart item.");
                                });
                        }


                        // Function to attach event listeners to newly loaded cart items
                        function attachCartEventListeners() {
                            // Attach 'change' event listener for updating quantity inputs
                            document.querySelectorAll('#cart-table-body input[name="qty"]').forEach(input => {
                                input.removeEventListener('change', updateCartQuantity); // Remove old listener
                                input.addEventListener('change', updateCartQuantity); // Add new listener
                            });
                        }


                        

                        function loadProducts(categoryId = 'all') {
                fetch(`/get-products?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        const productGrid = document.getElementById('product-grid');
                        productGrid.innerHTML = '';

                        data.products.forEach(product => {
                            if (product.stock === 0) return; // ⛔ បើ stock = 0 ទេព្រមាន

                            const card = `
                                <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg transform transition duration-200
                                                        cursor-pointer hover:scale-[1.02]"
                                                        onclick="addProductToCartAjax(${product.id}, '${product.name.replace(/'/g, "\\'")}', 1, ${product.buying_price});"
                                                        title="Click to add to purchase cart">

                                                        <div class="p-2 w-full" >
                                                            <img class="w-full h-32 rounded-md object-fill" src="${product.imageUrl}" alt="${product.name}">
                                                        </div>
                                                        <br>

                                                        <div class="p-4 px-3 text-center">
                                                            <div class="w-40">
                                                                <h3 class="font-semibold mb-1">${product.name}</h3>
                                                            </div>
                                                            <p class="text-blue-600 font-bold text-lg">$${product.price}</p>
                                                            <p class="font-semibold mb-1">${product.code}</p>
                                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                                Qty: ${product.stock}
                                                            </p>
                                                        </div>
                                                    </div>
                                                `;

                            productGrid.innerHTML += card;
                        });
                    });
            }


                        // Search
                        // Search
                            document.getElementById('searchBox').addEventListener('input', function () {
                                const keyword = this.value;

                                fetch(`/search-pos-products?keyword=${keyword}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const productGrid = document.getElementById('product-grid');
                                        productGrid.innerHTML = ''; // Clear old results

                                        data.products.forEach(product => {
                                            // ⛔ បន្ថែមលក្ខខណ្ឌនេះ ដើម្បីកុំឲ្យបង្ហាញ Product ដែលអស់ Stock
                                            if (product.stock === 0) return;

                                            // បន្ទាប់ពីបន្ថែមលក្ខខណ្ឌខាងលើ យើងលែងត្រូវការ const disabled ទៀតហើយ
                                            // ព្រោះ Product ដែលអស់ Stock នឹងមិនត្រូវបានបង្ហាញទាល់តែសោះ

                                            const card = `
                            <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg transform transition duration-200
                                cursor-pointer hover:scale-105"
                                onclick="addProductToCartAjax(${product.id}, '${product.name}', 1, ${product.price});"
                                title="Click to add to cart">

                                <div class="p-3" style="width:150px; height: 50px;">
                                    <img class="w-full h-24 rounded-md object-cover" src="${product.imageUrl}" alt="${product.name}">
                                </div>

                                <br><br><br>

                                <div class="p-4 px-3 text-center">
                                    <h3 class="font-semibold mb-1">${product.name}</h3>
                                    <p class="text-blue-600 font-bold text-lg">$${product.price}</p>
                                    <h3 class="font-semibold mb-1">${product.code}</h3>

                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Qty: ${product.stock}
                                    </p>
                                </div>
                            </div>
                        `;
                                            productGrid.innerHTML += card;
                                        });
                                    });
                            });


                        // Function to calculate due amount
                        function calculateDue() {
                            // ប្រើអថេរ originalSubtotal ជាតម្លៃตั้งต้นសម្រាប់ការគណនា
                            const subtotal = originalSubtotal;
                            const discount = parseFloat(document.getElementById('discount').value) || 0;
                            const payNow = parseFloat(document.getElementById('payNow').value) || 0;

                            const finalTotal = subtotal - discount;
                            const dueAmount = finalTotal - payNow;

                            // ធ្វើបច្ចុប្បន្នភាព Element ដែលបង្ហាញ
                            document.getElementById('totalPayable').innerText = `$${finalTotal.toFixed(2)}`;

                            // ធ្វើបច្ចុប្បន្នភាព Hidden Fields
                            document.getElementById('orderTotalHidden').value = finalTotal.toFixed(2);
                            document.getElementById('dueHidden').value = dueAmount.toFixed(2);
                        }


                        // Initial call to calculateDue when page loads
                        document.addEventListener('DOMContentLoaded', calculateDue);
                        // Add event listeners to input fields to recalculate on change
                        document.getElementById('payNow').addEventListener('input', calculateDue);
                        document.getElementById('discount').addEventListener('input', calculateDue);

                        $(document).ready(function () {
                            $('#myForm').validate({
                                rules: {
                                    customer_id: {
                                        required: true,
                                    },
                                    payment_status: {
                                        required: true,
                                    },

                                    pay: {
                                        required: true,
                                    },
                                    
                                },
                                messages: {
                                    customer_id: {
                                        required: '{{ __('messages.please_select_customer') }}',
                                    },
                                    payment_status: {
                                        required: '{{ __('messages.please_select_payment_status') }}',
                                    },

                                    pay: {
                                        required: '{{ __('messages.input_pay_now') }}',
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

                    </script>

                    @if (request('message'))
                        <script>
                            toastr["{{ request('alert-type') ?? 'success' }}"]("{{ request('message') }}");
                        </script>
                    @endif

@endsection