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

    <div class="flex flex-col md:flex-row gap-4 p-4 m-1 font-sans no-print w-full dark:bg-gray-800">
        <div class="flex-2 bg-white p-4 rounded shadow flex flex-col max-h-[88vh] dark:bg-gray-900">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold mb-2 dark:text-white">{{ __('messages.purchase') }}</h2>
                    <div class="w-64 mb-4">
                        <input type="text" placeholder="{{ __('messages.search') }}" id="searchBox"
                            class="dark:bg-gray-800 w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>

                {{-- Category Get --}}
<div class="w-full sm:w-[200px] md:w-[400px] lg:w-[800px] overflow-x-auto whitespace-nowrap mb-4"
    id="category-buttons">
    <button onclick="loadProducts('all', this)"
        class="dark:bg-gray-800 bg-gray-200 inline-block px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
        {{ __('messages.all_category') }}
    </button>
    @foreach ($categories as $category)
        <button onclick="loadProducts({{ $category->id }}, this)"
            class="dark:bg-gray-800 bg-gray-200 inline-block px-3 py-1 mr-2 rounded hover:bg-gray-300 text-sm">
            {{ $category->category_name }}
        </button>
    @endforeach
</div>
            </div>

            <div class="flex-1 overflow-y-auto ">
                <div id="product-grid"
                    class="p-4 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mb-2">
                </div>
            </div>
        </div>

        <div class="dark:bg-gray-900 flex-1 bg-white p-4 rounded shadow overflow-hidden max-h-[88vh]">
            <h2 class="text-xl font-bold mb-4">{{ __('messages.purchase_cart') }}</h2>
            <div class="dark:bg-gray-800 mt-4 overflow-auto max-h-64 border rounded-lg shadow-sm">
                <table class="w-full text-auto border-collapse">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr class="text-left dark:bg-gray-800">
                            <th class="p-2">{{ __('messages.product') }}</th>
                            <th class="p-2">{{ __('messages.price') }}</th>
                            <th class="p-2">{{ __('messages.qty') }}</th>
                            <th class="p-2">{{ __('messages.subtotal') }}</th>
                            <th class="p-2">{{ __('messages.table_action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table-body">
                        @php
                            $allcart = Cart::content();
                        @endphp
                        @foreach ($allcart as $cart)
                            <tr class="dark:bg-gray-800 hover:dark:bg-gray-700">
                                <td class="px-4">{{ $cart->name }}</td>
                                <td class="px-4">{{ $cart->price }}</td>
                                <td class="px-2">
                                    <div class="flex items-center space-x-2">
                                        <input name="qty" type="number" min="1" value="{{ $cart->qty }}"
                                            data-rowid="{{ $cart->rowId }}"
                                            class="qty-input w-16 py-2.5 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                    </div>
                                </td>
                                <td class="px-4 item-subtotal">{{ $cart->price * $cart->qty }}</td>
                                <td class="px-4">
                                    <button type="button" class=" icon-delete"
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
                {{ __('messages.subtotal') }} : $<span id="subtotal">{{ Cart::subtotal() }}</span><br>
                {{ __('messages.total_payable') }} : $<span id="totalPayable">{{ Cart::subtotal() }}</span>
            </div>

            <form method="POST" id="myForm" action="{{ url('/purchase/store') }}"
                class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                @csrf

                <div class="form-group">
                    <label for="supplier_id" class="block mb-1 font-medium text-gray-800 dark:text-white">
                        {{ __('messages.supplier_name') }}
                    </label>
                    
                    <div class="group relative flex items-center rounded-lg border border-gray-300 bg-white  transition-all duration-200 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:focus-within:border-indigo-500">
                        
                        <select name="supplier_id" id="supplier_id" 
                                class="w-full appearance-none rounded-lg border-none bg-transparent px-4 py-2 pr-14 focus:outline-none focus:ring-0 dark:text-white dark:bg-gray-700">
                            <option value="" disabled selected>{{ __('messages.select_supplier') }}</option>
                            @foreach ($supplier as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>

                        <button type="button" id="add-supplier-btn" 
                                class="absolute inset-y-0 right-5 flex items-center rounded-r-lg px-4 text-gray-500 transition hover:text-red-600 focus:outline-none dark:text-gray-400 dark:hover:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                        
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_status"
                        class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __('messages.payment_method') }}</label>
                    <select name="payment_status" id="payment_status"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                        <option value="" disabled selected>
                            {{ __('messages.select_payment') }}
                        </option>
                        <option value="QrScan">Qr Scan</option>
                        <option value="HandCash">HandCash</option>
                        <option value="Due">Due</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payNow"
                        class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __('messages.pay_now') }}
                        ($)</label>
                    <input type="number" name="pay" id="payNow" placeholder="{{ __('messages.pay') }}"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                </div>

                <div class="form-group">
                    <label for="discount"
                        class="block mb-1 font-medium text-gray-800 dark:text-white">{{ __(key: 'messages.discount') }}
                        ($)</label>
                    <input type="number" name="discount" id="discount" placeholder="Discount"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                        oninput="calculateDue()" min="0" value="0">
                </div>

                <input type="hidden" name="paid" id="paidHidden" value="{{ Cart::subtotal() }}">
                <input type="hidden" name="due" id="dueHidden" value="0">
                <input type="hidden" name="total" id="orderTotalHidden" value="{{ Cart::subtotal() }}">

                <div class="md:col-span-2">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-200">
                        {{ __('messages.complete_purchase') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal Supplier --}}
    <div id="add-supplier-modal" 
     class="hidden fixed inset-0 z-50 overflow-y-auto 
            bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative top-20 mx-auto w-full max-w-lg transform rounded-xl border border-slate-200/50 
             bg-white/80 p-6 shadow-2xl transition-all duration-300 
             dark:border-slate-700/50 dark:bg-slate-900/80">
    
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ __('messages.add_new_supplier') }}</h3>

                <form id="addSupplierForm" class="mt-4 text-left space-y-4">
                    @csrf
                    <div>
                        <label for="supplier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('messages.supplier_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="supplier_name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                        <div id="name_error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div>
                        <label for="supplier_email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="supplier_email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                        <div id="email_error" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div>
                        <label for="supplier_phone"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" id="supplier_phone"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.notes') }}</label>
                        <textarea name="notes" id="notes" rows="2"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600"></textarea>
                    </div>

                    <div class="items-center px-4 py-3 flex justify-end gap-x-3">
                        <button id="cancel-add-supplier" type="button"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none">
                            {{ __('messages.cancel') }}
                        </button>
                        <button id="save-supplier-btn" type="submit"
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

    <script type="text/javascript">
        const CSRF_TOKEN = "{{ csrf_token() }}";

        document.addEventListener("DOMContentLoaded", function() {
            
            loadProducts();
            updateCartDisplay({{ Js::from(Cart::content()) }}, {{ Cart::subtotal() }});
            calculateDue();
            document.getElementById('payNow').addEventListener('input', calculateDue);
            document.getElementById('discount').addEventListener('input', calculateDue);

            // Supplier JS
            const modal = document.getElementById('add-supplier-modal');
            const addSupplierBtn = document.getElementById('add-supplier-btn');
            const cancelBtn = document.getElementById('cancel-add-supplier');
            const addSupplierForm = document.getElementById('addSupplierForm');

             // បើក Modal
            addSupplierBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            // បិទ Modal
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                clearFormErrors(); // សម្អាត error messages ចាស់ៗ
                addSupplierForm.reset(); // សម្អាត input fields
            });

            // បិទ Modal ពេលចុចក្រៅ
            window.addEventListener('click', (e) => {
                if (e.target == modal) {
                    modal.classList.add('hidden');
                }
            });

            // AJAX function to save new supplier
            addSupplierForm.addEventListener('submit', function (e) {
                e.preventDefault();
                clearFormErrors();

                const formData = new FormData(this);

                fetch("{{ route('store.supplier.ajax') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                        // 'X-CSRF-TOKEN': formData.get('_token') // FormData includes it
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        // If we get an error response (like 422 for validation)
                        return response.json().then(data => Promise.reject(data));
                    }
                    return response.json(); // If response is OK
                })
                .then(data => {
                    // Success case
                    toastr.success(data.message);

                    const supplierSelect = document.getElementById('supplier_id');
                    // Create a new <option> element
                    const newOption = new Option(data.newSupplier.name, data.newSupplier.id, true, true);
                    // The last two 'true' arguments make it selected by default
                    
                    supplierSelect.add(newOption, null); // Add the new option to the dropdown
                    
                    // Close and reset the modal
                    modal.classList.add('hidden');
                    addSupplierForm.reset();
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
                document.querySelectorAll('#addSupplierForm [id$="_error"]').forEach(el => {
                    el.textContent = '';
                });
            }
        });



        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    supplier_id: {
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
                    supplier_id: {
                        required: '{{ __('messages.please_select_supplier') }}',
                    },
                    payment_status: {
                        required: '{{ __('messages.please_select_payment_status') }}',
                    },

                    pay: {
                        required: '{{ __('messages.input_pay_now') }}',
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


        function updateCartDisplay(cartContent, subtotal) {
            const cartTableBody = document.getElementById('cart-table-body');
            cartTableBody.innerHTML = '';

            if (Object.keys(cartContent).length === 0) {
                cartTableBody.innerHTML =
                    `<tr><td colspan="5" class="py-4 text-gray-500 text-center">No items in cart.</td></tr>`;
            } else {
                for (const rowId in cartContent) {
                    const item = cartContent[rowId];
                    const row = `
                                                <tr class="dark:bg-gray-800 hover:dark:bg-gray-700">
                                                    <td class="px-4">${item.name}</td>
                                                    <td class="px-4">${item.price}</td>
                                                    <td class="px-2">
                                                        <div class="flex items-center space-x-2">
                                                            <input name="qty" type="number" min="1" value="${item.qty}"
                                                                data-rowid="${item.rowId}"
                                                                class="qty-input w-16 py-2.5 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                                        </div>
                                                    </td>
                                                    <td class="px-4 item-subtotal">${(item.price * item.qty).toFixed(2)}</td>
                                                    <td class="px-4">
                                                        <button type="button" class=" icon-delete"
                                                            onclick="removeCartItem('${item.rowId}')">
                                                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            `;
                    cartTableBody.innerHTML += row;
                }
            }

            document.getElementById('totalPayable').innerText = `Total Payable : $ ${parseFloat(subtotal).toFixed(2)}`;
            document.getElementById('orderTotalHidden').value = parseFloat(subtotal).toFixed(2);
            document.getElementById('paidHidden').value = parseFloat(subtotal).toFixed(2);
            document.getElementById('subtotal').innerText = parseFloat(subtotal).toFixed(2);
            calculateDue();

            attachCartEventListeners();
        }

        function addProductToCartAjax(id, name, qty, price) {
            const formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('id', id);
            formData.append('name', name);
            formData.append('qty', qty);
            formData.append('price', price);

            fetch("/purchase/add-cart", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                })
                .catch(error => {
                    console.error('Error adding product to cart:', error);
                    toastr["error"]("Failed to add product to cart.");
                });
        }

        function updateCartQuantity(event) {
            const input = event.currentTarget;
            const rowId = input.dataset.rowid;
            let newQty = parseInt(input.value);

            if (isNaN(newQty) || newQty < 1) {
                newQty = 1;
                input.value = newQty;
            }

            fetch(`/purchase/cart/update/${rowId}`, {
                    method: 'POST',
                    body: new URLSearchParams({
                        _token: CSRF_TOKEN,
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
                        toastr[data.alert_type](data.message);
                    }
                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                })
                .catch(error => {
                    console.error('Error updating cart quantity:', error);
                    toastr["error"]("Failed to update cart quantity.");
                });
        }

        function removeCartItem(rowId) {
            fetch(`/purchase/cart/remove/${rowId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        toastr[data.alert_type](data.message);
                    }
                    updateCartDisplay(data.cart_content, data.cart_subtotal);
                })
                .catch(error => {
                    console.error('Error removing cart item:', error);
                    toastr["error"]("Failed to remove cart item.");
                });
        }

        function attachCartEventListeners() {
            document.querySelectorAll('#cart-table-body .qty-input').forEach(input => {
                input.removeEventListener('change', updateCartQuantity);
                input.addEventListener('change', updateCartQuantity);
                input.removeEventListener('input', updateCartQuantity);
                input.addEventListener('input', updateCartQuantity);
            });
        }

        function loadProducts(categoryId = 'all', clickedButton = null) {
    // 💡 ជំហានទី១៖ ដក style 'active' ចេញពីប៊ូតុងទាំងអស់
    const categoryButtonsContainer = document.getElementById('category-buttons');
    const allButtons = categoryButtonsContainer.querySelectorAll('button');
    allButtons.forEach(button => {
        button.classList.remove('bg-red-500', 'text-white', 'dark:bg-red-600'); // ដក class active
        button.classList.add('bg-gray-200', 'dark:bg-gray-800'); // បន្ថែម class ដើមវិញ
    });

    // 💡 ជំហានទី២៖ បន្ថែម style 'active' ទៅប៊ូតុងដែលបានចុច
    if (clickedButton) {
        // ប្រសិនបើមានប៊ូតុងត្រូវបានចុច
        clickedButton.classList.add('bg-red-500', 'text-white', 'dark:bg-red-600');
        clickedButton.classList.remove('bg-gray-200', 'dark:bg-gray-800');
    } else {
        // ករណីពិសេស៖ ពេលបើកទំព័រដំបូង (មិនមានការចុច) ដាក់ active លើ 'All Category'
        const firstButton = categoryButtonsContainer.querySelector('button:first-child');
        if (firstButton) {
            firstButton.classList.add('bg-red-500', 'text-white', 'dark:bg-red-600');
            firstButton.classList.remove('bg-gray-200', 'dark:bg-gray-800');
        }
    }
    
    // កូដ Fetch data របស់អ្នក (រក្សាទុកដដែល)
    fetch(`/get-products?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            const productGrid = document.getElementById('product-grid');
            productGrid.innerHTML = '';

            data.products.forEach(product => {
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
                            <p class="text-blue-600 font-bold text-lg">$${product.buying_price}</p>
                            <p class="font-semibold mb-1">${product.code}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Qty: ${product.stock}
                            </p>
                        </div>
                    </div>
                `;
                productGrid.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            toastr["error"]("Failed to load products.");
        });
}
        document.getElementById('searchBox').addEventListener('input', function() {
            const keyword = this.value;
            fetch(`/search-pos-products?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = '';

                    data.products.forEach(product => {
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
                                                            <p class="text-blue-600 font-bold text-lg">$${product.buying_price}</p>
                                                            <p class="font-semibold mb-1">${product.code}</p>
                                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                                Qty: ${product.stock}
                                                            </p>
                                                        </div>
                                                    </div>
                                                `;
                        productGrid.innerHTML += card;
                    });
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                    toastr["error"]("Failed to search products.");
                });
        });

        function calculateDue() {
            const subtotalEl = document.getElementById('subtotal');
            const subtotal = parseFloat(subtotalEl.innerText) || 0;
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            const payNow = parseFloat(document.getElementById('payNow').value) || 0;

            // ❗ បង្ខាំង discount មិនឲ្យលើស subtotal
            if (discount > subtotal) {
                discount = subtotal;
                document.getElementById('discount').value = subtotal.toFixed(2); // reset to max allowed
                toastr.warning("{{ __('messages.discount_cannot_exceed_subtotal') }}");

            }

            const finalTotal = subtotal - discount;
            const dueAmount = finalTotal - payNow;

            document.getElementById('totalPayable').innerText = finalTotal.toFixed(2);
            document.getElementById('orderTotalHidden').value = finalTotal.toFixed(2);
            document.getElementById('paidHidden').value = payNow.toFixed(2);
            document.getElementById('dueHidden').value = dueAmount.toFixed(2);
        }


        @if (request('message'))
        <script >
                toastr["{{ request('alert-type') ?? 'success' }}"]("{{ request('message') }}");
    </script>
    @endif
    </script>
@endsection
