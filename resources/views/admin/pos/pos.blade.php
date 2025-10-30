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

        .active-filter {
            background-color: #dc2626 !important; /* red-600 */
            color: white !important;
            border-color: #dc2626 !important;
        }
        .dark .active-filter {
            background-color: #dc2626 !important; /* red-400 */
        }
    </style>

    <div class="flex flex-col lg:flex-row gap-4 font-sans no-print w-full  p-4">

        {{-- Left Side - Product Selection --}}
        <div class="lg:w-3/5 xl:w-2/3 card-dynamic-bg p-4 rounded-xl border border-primary flex flex-col h-[calc(100vh-85px)]">
            {{-- Header & Search --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-primary mb-4">
                <h2 class="text-2xl font-bold text-defalut mb-2 sm:mb-0">{{ __('messages.pos') }}</h2>
                <div class="relative w-full sm:w-64">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" placeholder="{{ __('messages.search') }}" id="searchBox" class="w-full p-2 pl-10 border border-primary rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 card-dynamic-bg dark:text-white" />
                </div>
            </div>

            {{-- Condition Filter Buttons --}}
            <div class="w-full overflow-x-auto whitespace-nowrap pb-2 mb-2">
                <span class="text-sm  text-defalut  mr-2">{{ __('messages.condition') }}:</span>
                <button onclick="filterProducts('condition', 'all', this)" class="condition-btn filter-btn m-1 inline-block bg-white  border border-primary px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors active-filter">
                    {{ __('messages.all') }}
                </button>
                @foreach ($conditions as $condition)
                    <button onclick="filterProducts('condition', {{ $condition->id }}, this)" class="condition-btn filter-btn m-1 inline-block bg-white  border border-primary px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors">
                        {{ $condition->condition_name }}
                    </button>
                @endforeach
            </div>

            {{-- Category Filter Buttons --}}
            <div class="w-full overflow-x-auto whitespace-nowrap pb-2 mb-2">
                <span class="text-sm  text-defalut  mr-2">{{ __('messages.category') }}:</span>
                <button onclick="filterProducts('category', 'all', this)" class="category-btn filter-btn m-1 inline-block bg-white  border border-primary px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors active-filter">
                    {{ __('messages.all_category') }}
                </button>
                @foreach ($categories as $category)
                    <button onclick="filterProducts('category', {{ $category->id }}, this)" class="category-btn filter-btn m-1 inline-block bg-white  border border-primary px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors">
                        {{ $category->category_name }}
                    </button>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto -m-2 p-2">
                <div id="product-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    {{-- Product cards will be loaded here by JavaScript --}}
                </div>
            </div>
        </div>

        {{-- Right Side - Cart & Checkout --}}
        <div class="lg:w-2/5 xl:w-1/3 card-dynamic-bg p-4 rounded-xl border border-primary flex flex-col h-[calc(100vh-85px)]">
            <h2 class="text-xl font-bold text-defalut pb-4 border-b border-primary">
                {{ __('messages.product_items') }}
            </h2>

            <div class="flex-1 mt-4 overflow-auto -mx-4 px-4">
                <table class="w-full text-sm">
                    <thead class="card-dynamic-bg sticky top-0 z-10">
                        <tr class="text-left text-defalut">
                            <th class="p-2 ">{{ __('messages.product') }}</th>
                            <th class="p-2 ">{{ __('messages.price') }}</th>
                            <th class="p-2  text-center">{{ __('messages.qty') }}</th>
                            <th class="p-2  text-right">{{ __('messages.subtotal') }}</th>
                            <th class="p-2  text-center">{{ __('messages.table_action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table-body" class="divide-y divide-primary">
                        {{-- Cart items will be loaded here by JavaScript --}}
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-primary space-y-2 text-sm">
                <div class="flex justify-between text-defalut">
                    <span>{{ __('messages.subtotal') }}:</span>
                    <span class="font-medium">$<span id="subtotal">0.00</span></span>
                </div>
                <div class="flex justify-between text-defalut">
                    <span>{{ __('messages.discount') }}:</span>
                    <span class="font-medium text-red-500">-$<span id="discountDisplay">0.00</span></span>
                </div>
                <div class="flex justify-between text-lg font-bold text-defalut border-t border-dashed pt-2 mt-2 border-primary">
                    <span>{{ __('messages.total_payable') }}:</span>
                    <span>$<span id="totalPayable">0.00</span> / <span><span id="totalPayableKhr">0</span> ៛</span>
                
                </span>
                </div>
            

            </div>

            <form method="POST" id="myForm" action="{{ url('/store-sell') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-primary">
                @csrf
                <input type="hidden" name="order_date" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="total" id="orderTotalHidden">
                <input type="hidden" name="due" id="dueHidden">

                {{-- ✅ START: បន្ថែមបន្ទាត់នេះ --}}
                <input type="hidden" name="exchange_rate_khr" id="exchange_rate_khr" value="{{ $activeRate->rate_khr ?? 4100 }}">
                {{-- ✅ END --}}

                <div class="form-group">
                    <label for="customer_id" class="block mb-1 text-sm font-medium text-defalut">
                        {{ __('messages.customer_name') }}
                    </label>
                    <div class="group relative flex items-center">
                        <select name="customer_id" id="customer_id" class="w-full appearance-none rounded-lg border border-primary card-dynamic-bg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 ">
                            <option value="" disabled selected>{{ __('messages.select_customer') }}</option>
                            @foreach ($customers as $cus)
                                <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="add-customer-btn" title="Add New Customer" class="absolute inset-y-0 right-5 flex items-center rounded-r-lg px-3 text-defalut transition hover:text-red-600 focus:outline-none  dark:hover:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_status" class="block mb-1 text-sm font-medium text-defalut">{{ __('messages.payment_method') }}</label>
                    <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-primary rounded-lg card-dynamic-bg text-defalut focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="" disabled selected>{{ __('messages.select_payment') }}</option>
                        <option value="QrScan">Qr Scan</option>
                        <option value="HandCash">HandCash</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="discount" class="block mb-1 text-sm font-medium text-defalut">{{ __('messages.discount') }} ($)</label>
                    <input type="number" name="discount" id="discount" placeholder="0.00" class="w-full px-3 py-2 border border-primary rounded-lg card-dynamic-bg text-defalut focus:outline-none focus:ring-2 focus:ring-red-500" min="0" value="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="payNow" class="block mb-1 text-sm font-medium text-defalut">{{ __('messages.pay') }} ($)</label>
                    <input type="number" name="pay" id="payNow" placeholder="{{ __('messages.pay_now') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-primary rounded-lg card-dynamic-bg text-defalut focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <div class="sm:col-span-2 mt-2 flex items-center gap-3">

                        {{-- ប៊ូតុង Quotation (Secondary Action) --}}
                        <button type="button" id="create-quotation-btn" class="w-full card-dynamic-bg text-defalut font-bold  py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                             Quotation
                        </button>

                        {{-- ប៊ូតុង Pay Nows (Primary Action) --}}
                        <button type="submit" class="w-full bg-primary text-white  py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
                            </svg>
                            {{ __('messages.pay_nows') }}
                        </button>

                    </div>
            </form>
        </div>
    </div>

    {{-- Modal Customer --}}
    <div id="add-customer-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative top-10 sm:top-20 mx-auto w-full max-w-lg transform rounded-xl  p-6 shadow-2xl transition-all duration-300 border border-primary card-dynamic-bg">
            <div class="flex justify-between items-center pb-3 border-b border-primary">
                <h3 class="text-lg leading-6 font-medium text-defalut">{{ __('messages.add_new_customer') }}</h3>
                <button id="cancel-add-customer-x" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="addCustomerForm" class="mt-4 text-left space-y-4">
                @csrf
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-defalut">{{ __('messages.customer_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="customer_name" class="mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm ">
                    <div id="name_error" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div>
                    <label for="customer_address" class="block text-sm font-medium text-defalut">{{ __('messages.address') }}</label>
                    <input type="text" name="address" id="customer_address" class="mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm ">
                    <div id="address_error" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-defalut">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" id="customer_phone" class="mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm ">
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-defalut">{{ __('messages.notes') }}</label>
                    <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm "></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-x-3">
                    <button id="cancel-add-customer" type="button" class="px-4 py-2 card-dynamic-bg text-defalut  rounded-md  focus:outline-none">{{ __('messages.cancel') }}</button>
                    <button id="save-customer-btn" type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-defalut focus:outline-none">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Product Details --}}
    <div id="product-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative top-10 sm:top-20 mx-auto w-full max-w-2xl transform rounded-xl bg-white p-6 shadow-2xl transition-all duration-300 dark:bg-slate-800 border dark:border-slate-700">
            <div class="flex justify-between items-center pb-3 border-b border-primary">
                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white">{{ __('messages.product_details') }}</h3>
                <button id="close-details-modal-btn" type="button" class="p-1 rounded-full text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div id="modal-product-body" class="mt-4 text-left">
                <div id="modal-loading-state" class="text-center p-8">
                    <svg class="animate-spin h-8 w-8 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="mt-2 text-sm text-defalut">Loading Details...</p>
                </div>
                <div id="modal-content-state" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <img id="details-modal-image" src="" alt="Product Image" class="w-full h-64 object-cover rounded-lg border dark:border-slate-700">
                    </div>
                    <div class="space-y-3">
                        <h4 id="details-modal-name" class="text-2xl font-bold text-defalut"></h4>
                        <p id="details-modal-price" class="text-3xl font-light text-red-600 dark:text-red-400"></p>
                        <div class="text-sm space-y-2 pt-2 border-t dark:border-slate-700">
                            <p class="flex justify-between"><span class="text-defalut  font-medium">Category:</span> <span id="details-modal-category" class="text-defalut"></span></p>
                            <p class="flex justify-between"><span class="text-defalut  font-medium">Supplier:</span> <span id="details-modal-supplier" class="text-defalut"></span></p>
                            <p class="flex justify-between"><span class="text-defalut  font-medium">Product Code:</span> <span id="details-modal-code" class="text-defalut"></span></p>
                            <p class="flex justify-between"><span class="text-defalut  font-medium">Stock:</span> <span id="details-modal-stock" class="text-defalut"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>

        <script type="text/javascript">
            let currentCategoryId = 'all';
            let currentConditionId = 'all';
            let originalSubtotal = 0;
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const defaultImagePath = "{{ asset('upload/no_image.jpg') }}";

            // SECTION 1: FUNCTIONS FOR RENDERING UI
            function createProductCardHTML(product) {
                const imageUrl = product.imageUrl && product.imageUrl.endsWith('/') ? defaultImagePath : (product.imageUrl ||
                    defaultImagePath);
                const isPreOrder = product.stock <= 0;
                const stockBadge = isPreOrder ?
                    `<span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pre-Order</span>` :
                    `<span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock: ${product.stock}</span>`;
                const cardClass = isPreOrder ? 'opacity-80 hover:opacity-100' :
                    'hover:border-red-500 dark:hover:border-red-500 hover:shadow-lg';
                const conditionText = (product.condition && product.condition !== 'N/A') ?
                    `<p class="text-xs text-primary font-medium">${product.condition}</p>` : '';

                return `
            <div class="group relative card-dynamic-bg0 border-primary/50 rounded-lg overflow-hidden border  transform transition-all duration-200 cursor-pointer ${cardClass}"
                onclick="addProductToCartAjax(${product.id}, '${product.name.replace(/'/g, "\\'")}', 1, ${product.price}, ${product.stock});"
                title="Click to add to cart">
                <button
                    onclick="showProductDetails(${product.id}, event)"
                    title="View Details"
                    class="absolute top-2 left-2 z-20 p-1.5 bg-white/70 dark:bg-slate-900/70 rounded-full text-defalut hover:bg-white hover:text-red-600 dark:hover:bg-slate-900 dark:hover:text-red-400 transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </button>
                ${stockBadge}
                <div class="w-full h-32"><img class="w-full h-full object-cover" src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null; this.src='${defaultImagePath}';"></div>
                <div class="p-3 text-left">
                    <h3 class=" text-sm text-defalut truncate">${product.name}</h3>
                    ${conditionText}
                    <p class="text-primary font-bold text-lg mt-1">$${product.price.toFixed(2)}</p>
                </div>
            </div>`;
            }

            function updateCartDisplay(cartContent, subtotal) {
                const cartTableBody = document.getElementById('cart-table-body');
                cartTableBody.innerHTML = '';
                originalSubtotal = parseFloat(subtotal.replace(/,/g, '')) || 0;

                if (Object.keys(cartContent).length === 0) {
                    cartTableBody.innerHTML =
                        `<tr><td colspan="5" class="py-10 text-defalut text-center text-sm">{{ __('messages.no_items_in_cart') }}</td></tr>`;
                } else {
                    for (const rowId in cartContent) {
                        const item = cartContent[rowId];
                        const isPreOrder = item.options.is_pre_order === 'true';
                        const preOrderLabel = isPreOrder ? `<span class="block text-xs text-amber-500">Pre-Order</span>` : '';
                        const row = `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="p-2 font-medium text-defalut">${item.name} ${preOrderLabel}</td>
                        <td class="p-2 text-defalut ">$${parseFloat(item.price).toFixed(2)}</td>
                        <td class="p-2">
                            <input name="qty" type="number" min="1" value="${item.qty}" data-rowid="${item.rowId}" class="qty-input w-16 py-1 px-2 border border-primary rounded text-center bg-white  dark:text-white">
                        </td>
                        <td class="p-2 text-right font-medium text-defalut">$${(item.price * item.qty).toFixed(2)}</td>
                        <td class="p-2 text-center">
                            <button type="button" class="text-slate-400 hover:text-red-500 transition-colors" onclick="removeCartItem('${item.rowId}')" title="Remove Item"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button>
                        </td>
                    </tr>`;
                        cartTableBody.innerHTML += row;
                    }
                }

                // ✅ FIXED: Call the auto-fill function here to update payNow when the cart changes
                updateTotalsAndAutoFillPayNow();
                attachCartEventListeners();
            }

            // SECTION 2: CORE LOGIC & AJAX FUNCTIONS
            function fetchProducts() {
                const url = `/get-products?category_id=${currentCategoryId}&condition_id=${currentConditionId}`;
                const productGrid = document.getElementById('product-grid');
                productGrid.innerHTML =
                    `<p class="col-span-full text-center text-defalut  p-10">Loading...</p>`;
                fetch(url)
                    .then(response => response.ok ? response.json() : Promise.reject(`HTTP error! status: ${response.status}`))
                    .then(data => {
                        productGrid.innerHTML = '';
                        if (data.products && data.products.length > 0) {
                            data.products.forEach(product => productGrid.innerHTML += createProductCardHTML(product));
                        } else {
                            productGrid.innerHTML =
                                `<p class="col-span-full text-center text-defalut  p-10">No products found.</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                        productGrid.innerHTML =
                            `<p class="col-span-full text-center text-primary p-10">Failed to load products. Please check the URL and route.</p>`;
                    });
            }

            function filterProducts(type, id, clickedButton) {
                if (type === 'category') {
                    currentCategoryId = id;
                } else if (type === 'condition') {
                    currentConditionId = id;
                }
                const btnClass = (type === 'category') ? '.category-btn' : '.condition-btn';
                document.querySelectorAll(btnClass).forEach(button => button.classList.remove('active-filter'));
                clickedButton.classList.add('active-filter');
                fetchProducts();
            }

            function formatKhr(number) {
                const rounded = Math.round(number / 100) * 100;
                return new Intl.NumberFormat('en-US').format(rounded);
            }

            // This function only calculates and updates the display, respecting manual input in 'payNow'
            function calculateDue() {
                let discount = parseFloat(document.getElementById('discount').value) || 0;
                const payNow = parseFloat(document.getElementById('payNow').value) || 0;
                const finalTotal = originalSubtotal - discount;
                const dueAmount = finalTotal - payNow;
                const exchangeRate = parseFloat(document.getElementById('exchange_rate_khr').value) || 4100;
                const totalInKhr = finalTotal * exchangeRate;

                document.getElementById('subtotal').innerText = originalSubtotal.toFixed(2);
                document.getElementById('discountDisplay').innerText = discount.toFixed(2);
                document.getElementById('totalPayable').innerText = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
                document.getElementById('totalPayableKhr').innerText = formatKhr(totalInKhr);
                document.getElementById('orderTotalHidden').value = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
                document.getElementById('dueHidden').value = dueAmount.toFixed(2);
            }

            // This new function handles the auto-fill logic
            function updateTotalsAndAutoFillPayNow() {
                let discount = parseFloat(document.getElementById('discount').value) || 0;
                const finalTotal = originalSubtotal - discount;
                document.getElementById('payNow').value = finalTotal > 0 ? finalTotal.toFixed(2) : '';
                calculateDue();
            }

            function addProductToCartAjax(id, name, qty, price, stock) {
                const isPreOrder = stock <= 0;
                fetch("/add-cart", {
                        method: 'POST',
                        body: new URLSearchParams({
                            id,
                            name,
                            qty,
                            price,
                            'options[is_pre_order]': isPreOrder,
                            _token: CSRF_TOKEN
                        }),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            toastr.error(data.error);
                        } else {
                            updateCartDisplay(data.cart_content, data.cart_subtotal);
                        }
                    })
                    .catch(error => console.error('Error adding to cart:', error));
            }

            function updateCartQuantity(event) {
                const input = event.currentTarget;
                const newQty = (parseInt(input.value) > 0) ? parseInt(input.value) : 1;
                input.value = newQty;
                fetch(`/cart-update/${input.dataset.rowid}`, {
                        method: 'POST',
                        body: new URLSearchParams({
                            _token: CSRF_TOKEN,
                            qty: newQty
                        }),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => updateCartDisplay(data.cart_content, data.cart_subtotal))
                    .catch(error => console.error('Error updating cart:', error));
            }

            function removeCartItem(rowId) {
                fetch(`/cart-remove/${rowId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => updateCartDisplay(data.cart_content, data.cart_subtotal))
                    .catch(error => console.error('Error removing item:', error));
            }

            // SECTION 3: INITIALIZATION & EVENT LISTENERS
            function attachCartEventListeners() {
                document.querySelectorAll('#cart-table-body .qty-input').forEach(input => {
                    input.addEventListener('change', updateCartQuantity);
                });
            }

            document.addEventListener("DOMContentLoaded", function() {
                // Initial Data Loads
                fetchProducts();
                updateCartDisplay({!! Js::from(Cart::content()) !!}, "{{ Cart::subtotal() }}");

                // Search Functionality
                document.getElementById('searchBox').addEventListener('input', function() {
                    fetch(`/search-pos-products?keyword=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            const productGrid = document.getElementById('product-grid');
                            productGrid.innerHTML = '';
                            if (data.products && data.products.length > 0) {
                                data.products.forEach(product => productGrid.innerHTML +=
                                    createProductCardHTML(product));
                            }
                        });
                });

                // Calculation Listeners - This is the core logic
                document.getElementById('payNow').addEventListener('input', calculateDue);
                document.getElementById('discount').addEventListener('input', updateTotalsAndAutoFillPayNow);

                document.addEventListener('rateUpdated', () => {
                    updateTotalsAndAutoFillPayNow();
                    toastr.info('Exchange rate applied to current order.');
                });

                // --- Customer & Product Details Modals Functionality (No changes needed here) ---
                const customerModal = document.getElementById('add-customer-modal');
                const addCustomerBtn = document.getElementById('add-customer-btn');
                // ... (rest of the modal code is unchanged and correct)
                const cancelCustomerBtn = document.getElementById('cancel-add-customer');
                const cancelCustomerBtnX = document.getElementById('cancel-add-customer-x');
                const addCustomerForm = document.getElementById('addCustomerForm');

                function closeCustomerModal() {
                    customerModal.classList.add('hidden');
                    addCustomerForm.reset();
                    document.querySelectorAll('#addCustomerForm [id$="_error"]').forEach(el => el.textContent = '');
                }
                addCustomerBtn.addEventListener('click', () => customerModal.classList.remove('hidden'));
                cancelCustomerBtn.addEventListener('click', closeCustomerModal);
                cancelCustomerBtnX.addEventListener('click', closeCustomerModal);


                
                window.addEventListener('click', (e) => {
                    if (e.target == customerModal) closeCustomerModal();
                });
                addCustomerForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetch("{{ route('store.customer.ajax') }}", {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            }
                        })
                        .then(response => response.json().then(data => ({
                            ok: response.ok,
                            data
                        })))
                        .then(({
                            ok,
                            data
                        }) => {
                            if (!ok) throw data;
                            toastr.success(data.message);
                            const customerSelect = document.getElementById('customer_id');
                            const newOption = new Option(data.newCustomer.name, data.newCustomer.id, true,
                                true);
                            customerSelect.add(newOption, null);
                            customerSelect.dispatchEvent(new Event('change'));
                            closeCustomerModal();
                        })
                        .catch(errorData => {
                            if (errorData.errors) {
                                Object.keys(errorData.errors).forEach(key => {
                                    const errorElement = document.getElementById(`${key}_error`);
                                    if (errorElement) errorElement.textContent = errorData.errors[
                                        key][0];
                                });
                            }
                        });
                });
                const detailsModal = document.getElementById('product-details-modal');
                const closeDetailsBtn = document.getElementById('close-details-modal-btn');
                
                const modalLoading = document.getElementById('modal-loading-state');
                const modalContent = document.getElementById('modal-content-state');
                window.showProductDetails = function(productId, event) {
                    event.stopPropagation();
                    detailsModal.classList.remove('hidden');
                    modalLoading.classList.remove('hidden');
                    modalContent.classList.add('hidden');
                    fetch(`/get-product-details/${productId}`)
                        .then(response => response.ok ? response.json() : Promise.reject('Product not found'))
                        .then(data => {
                            document.getElementById('details-modal-image').src = data.imageUrl || defaultImagePath;
                            document.getElementById('details-modal-name').innerText = data.product_name || 'N/A';
                            document.getElementById('details-modal-price').innerText = `$${parseFloat(data.selling_price || 0).toFixed(2)}`;
                            document.getElementById('details-modal-category').innerText = data.category ? data.category.category_name : 'N/A';
                            document.getElementById('details-modal-supplier').innerText = data.supplier ? data.supplier.name : 'N/A';
                            document.getElementById('details-modal-code').innerText = data.product_code || 'N/A';
                            document.getElementById('details-modal-stock').innerText = data.product_store || '0';
                            modalLoading.classList.add('hidden');
                            modalContent.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error fetching product details:', error);
                            modalLoading.innerHTML = `<p class="text-red-500 p-8">Could not load details. Please try again.</p>`;
                        });
                }
                function closeDetailsModal() { detailsModal.classList.add('hidden'); }
                closeDetailsBtn.addEventListener('click', closeDetailsModal);
                detailsModal.addEventListener('click', (e) => { if (e.target === detailsModal) closeDetailsModal(); });

                //...

                // --- jQuery Form Validation (Complete with Overpayment Protection) ---
                // ✅ ADDED: Custom validation rule to prevent overpayment
                $.validator.addMethod("maxPayment", function(value, element) {
                    const payValue = parseFloat(value) || 0;
                    const totalPayable = parseFloat($('#totalPayable').text()) || 0;
                    return payValue <= totalPayable;
                }, function() {
                    const totalPayable = parseFloat($('#totalPayable').text()) || 0;
                    return `Payment cannot exceed total of $${totalPayable.toFixed(2)}`;
                });

                $.validator.addMethod("maxDiscount", function(value, element) {
                    return (parseFloat(value) || 0) <= originalSubtotal;
                }, function() {
                    return `Discount cannot exceed subtotal ($${originalSubtotal.toFixed(2)})`;
                });

                $('#myForm').validate({
                    rules: {
                        customer_id: {
                            required: true
                        },
                        payment_status: {
                            required: true
                        },
                        pay: { // ✅ ADDED: Apply the new validation rule
                            required: true,
                            number: true,
                            min: 0,
                            maxPayment: true
                        },
                        discount: {
                            number: true,
                            min: 0,
                            maxDiscount: true
                        }
                    },
                    messages: {
                        customer_id: {
                            required: 'Please select a customer'
                        },
                        payment_status: {
                            required: 'Please select a payment method'
                        },
                        pay: {
                            required: 'Please enter the amount paid',
                            number: 'Please enter a valid number',
                            min: 'Payment must be 0 or greater'
                        },
                        discount: {
                            number: 'Please enter a valid number',
                            min: 'Discount must be 0 or greater'
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback text-red-500 text-xs mt-1');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element) {
                        $(element).addClass('border-red-500').removeClass('border-slate-300');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('border-red-500').addClass('border-slate-300');
                    },
                });

                // --- Quotation Button Logic (No changes needed here) ---
                document.getElementById('create-quotation-btn').addEventListener('click', async function(e) {
                    // ... (rest of the quotation code is unchanged and correct)
                    e.preventDefault();
                    const customerId = document.getElementById('customer_id').value;
                    const discount = document.getElementById('discount').value;
                    const button = this;
                    if (!customerId) {
                        toastr.error('Please select a customer first.');
                        return;
                    }
                    const formData = new FormData();
                    formData.append('customer_id', customerId);
                    formData.append('discount', discount);
                    formData.append('_token', CSRF_TOKEN);
                    button.disabled = true;
                    button.innerHTML = 'Generating Preview...';
                    try {
                        const response = await fetch("{{ route('generate.quotation.preview') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!response.ok) {
                            throw new Error('please add product to cart');
                        }
                        const html = await response.text();
                        document.open();
                        document.write(html);
                        document.close();
                    } catch (error) {
                        console.error('Error:', error);
                        toastr.error(error.message);
                        button.disabled = false;
                        button.innerHTML = `Create Quotation`;
                    }
                });
            });
        </script>


@endsection