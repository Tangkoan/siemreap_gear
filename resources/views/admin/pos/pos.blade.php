@extends('admin/admin_dashboard')
@section('admin')

{{-- Style សម្រាប់ពេល Print ត្រូវបានរក្សាទុកដដែល --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #invoice-box, #invoice-box * {
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

{{-- ✨ NEW DESIGN START --}}
<div class="flex flex-col lg:flex-row gap-4 font-sans no-print w-full bg-slate-100 dark:bg-slate-900 p-4">

    {{-- Left Side - Product Selection --}}
    <div class="lg:w-3/5 xl:w-2/3 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col h-[calc(100vh-100px)]">
        {{-- Header & Search --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-slate-200 dark:border-slate-700 mb-4">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 sm:mb-0">{{ __('messages.pos') }}</h2>
            <div class="relative w-full sm:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <input type="text" placeholder="{{ __('messages.search') }}" id="searchBox"
                       class="w-full p-2 pl-10 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-slate-900 dark:text-white" />
            </div>
        </div>

        {{-- Category Buttons --}}
        <div class="w-full overflow-x-auto whitespace-nowrap pb-2 mb-2" id="category-buttons">
            <button onclick="loadProducts('all', this)"
                    class="category-btn m-1 inline-block bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors">
                {{ __('messages.all_category') }}
            </button>
            @foreach ($categories as $category)
                <button onclick="loadProducts({{ $category->id }}, this)"
                        class="category-btn m-1 inline-block bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 px-4 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 text-sm font-medium transition-colors">
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
    <div class="lg:w-2/5 xl:w-1/3 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex flex-col h-[calc(100vh-100px)]">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white pb-4 border-b border-slate-200 dark:border-slate-700">{{ __('messages.product_items') }}</h2>

        {{-- Cart Items Table --}}
        <div class="flex-1 mt-4 overflow-auto -mx-4 px-4">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 sticky top-0 z-10">
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="p-2 font-semibold">{{ __('messages.product') }}</th>
                        <th class="p-2 font-semibold">{{ __('messages.price') }}</th>
                        <th class="p-2 font-semibold text-center">{{ __('messages.qty') }}</th>
                        <th class="p-2 font-semibold text-right">{{ __('messages.subtotal') }}</th>
                        <th class="p-2 font-semibold text-center">{{ __('messages.table_action') }}</th>
                    </tr>
                </thead>
                <tbody id="cart-table-body" class="divide-y divide-slate-100 dark:divide-slate-700">
                    {{-- Cart items will be loaded here by JavaScript --}}
                </tbody>
            </table>
        </div>

        {{-- Order Summary --}}
        <div class="pt-4 border-t border-slate-200 dark:border-slate-700 space-y-2 text-sm">
             <div class="flex justify-between text-slate-600 dark:text-slate-300">
                <span>{{ __('messages.subtotal') }}:</span>
                <span class="font-medium">$<span id="subtotal">0.00</span></span>
            </div>
             <div class="flex justify-between text-slate-600 dark:text-slate-300">
                <span>{{ __('messages.discount') }}:</span>
                <span class="font-medium text-red-500">-$<span id="discountDisplay">0.00</span></span>
            </div>
            <div class="flex justify-between text-lg font-bold text-slate-800 dark:text-white border-t border-dashed pt-2 mt-2 border-slate-300 dark:border-slate-600">
                <span>{{ __('messages.total_payable') }}:</span>
                <span>$<span id="totalPayable">0.00</span></span>
            </div>
        </div>
        
        {{-- Checkout Form --}}
        <form method="POST" id="myForm" action="{{ url('/store-sell') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
            @csrf
            {{-- Hidden fields --}}
            <input type="hidden" name="order_date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="total" id="orderTotalHidden">
            <input type="hidden" name="due" id="dueHidden">

            <div class="form-group sm:col-span-2">
                <label for="customer_id" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('messages.customer_name') }}
                </label>
                <div class="group relative flex items-center">
                    <select name="customer_id" id="customer_id" class="w-full appearance-none rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-white">
                        <option value="" disabled selected>{{ __('messages.select_customer') }}</option>
                        @foreach ($customers as $cus)
                            <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-customer-btn" title="Add New Customer" class="absolute inset-y-0 right-0 flex items-center rounded-r-lg px-3 text-slate-500 transition hover:text-indigo-600 focus:outline-none dark:text-slate-400 dark:hover:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="payment_status" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('messages.payment_method') }}</label>
                <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="" disabled selected>{{ __('messages.select_payment') }}</option>
                    <option value="QrScan">Qr Scan</option>
                    <option value="HandCash">HandCash</option>
                    <option value="Due">Due</option>
                </select>
            </div>

             <div class="form-group">
                <label for="discount" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('messages.discount') }} ($)</label>
                <input type="number" name="discount" id="discount" placeholder="0.00" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500" min="0" value="0" step="0.01">
            </div>
            
            <div class="form-group sm:col-span-2">
                <label for="payNow" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ __('messages.pay') }} ($)</label>
                <input type="number" name="pay" id="payNow" placeholder="{{ __('messages.pay_now') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="sm:col-span-2 mt-2">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
                    </svg>
                    {{ __('messages.pay_nows') }}
                </button>
            </div>
        </form>
    </div>
</div>
{{-- ✨ NEW DESIGN END --}}


{{-- Modal Customer (Restyled) --}}
<div id="add-customer-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative top-10 sm:top-20 mx-auto w-full max-w-lg transform rounded-xl bg-white p-6 shadow-2xl transition-all duration-300 dark:bg-slate-800 border dark:border-slate-700">
        <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
             <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('messages.add_new_customer') }}
             </h3>
             <button id="cancel-add-customer-x" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
             </button>
        </div>
        <form id="addCustomerForm" class="mt-4 text-left space-y-4">
            @csrf
            <div>
                <label for="customer_name" class="block text-sm font-medium text-slate-700 dark:text-gray-200">
                    {{ __('messages.customer_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="customer_name" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-slate-700">
                <div id="name_error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 dark:text-gray-200">{{ __('messages.address') }}</label>
                <input type="text" name="address" id="customer_address" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-slate-700">
                <div id="address_error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div>
                <label for="customer_phone" class="block text-sm font-medium text-slate-700 dark:text-gray-200">{{ __('messages.phone') }}</label>
                <input type="text" name="phone" id="customer_phone" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-slate-700">
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-gray-200">{{ __('messages.notes') }}</label>
                <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-slate-700"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-x-3">
                <button id="cancel-add-customer" type="button" class="px-4 py-2 bg-slate-100 text-slate-800 rounded-md hover:bg-slate-200 focus:outline-none dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                    {{ __('messages.cancel') }}
                </button>
                <button id="save-customer-btn" type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>

<script type="text/javascript">
    // ប្រកាសអថេរដែលប្រើរួមគ្នានៅខាងលើគេ
    let originalSubtotal = 0;
    const CSRF_TOKEN = "{{ csrf_token() }}";

    // ✅ NEW: Function សម្រាប់បង្កើត Product Card ជាមួយ Design ថ្មី
    function createProductCardHTML(product) {

        // 💡 ជំហានទី១: កំណត់ Default Image Path
        const defaultImagePath = "{{ asset('images/icons/no_image.jpg') }}"; 

        // 💡 ជំហានទី២: ពិនិត្យមើល imageUrl របស់ផលិតផល
        // ប្រសិនបើ product.imageUrl មានតម្លៃ (មិនមែន null), ប្រើវា។ បើមិនដូច្នោះទេ, ប្រើ defaultImagePath។
        const imageUrl = product.imageUrl ? product.imageUrl : defaultImagePath;

        const isPreOrder = product.stock <= 0;
        const stockBadge = isPreOrder
            ? `<span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pre-Order</span>`
            : `<span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">In Stock: ${product.stock}</span>`;
        
        const cardClass = isPreOrder 
            ? 'opacity-80 hover:opacity-100' 
            : 'hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-lg';

        return `
        <div class="relative bg-white dark:bg-slate-800 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700/50 transform transition-all duration-200 cursor-pointer ${cardClass}"
             onclick="addProductToCartAjax(${product.id}, '${product.name.replace(/'/g, "\\'")}', 1, ${product.price}, ${product.stock});"
             title="Click to add to cart">
            ${stockBadge}
            <div class="w-full h-32">
                {{-- 💡 ជំហានទី៣: ប្រើអថេរ imageUrl ដែលបានពិនិត្យរួច --}}
                <img class="w-full h-full object-cover" src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null; this.src='${defaultImagePath}';">
            </div>
            <div class="p-3 text-left">
                <h3 class="font-semibold text-sm text-slate-800 dark:text-slate-100 truncate">${product.name}</h3>
                <p class="text-indigo-600 dark:text-indigo-400 font-bold text-lg">$${product.price}</p>
            </div>
        </div>`;
    }

    // ✅ REFINED: កែប្រែ function loadProducts ឱ្យប្រើ active class ថ្មី
    function loadProducts(categoryId = 'all', clickedButton = null) {
        // Remove active class from all buttons
        document.querySelectorAll('.category-btn').forEach(button => {
            button.classList.remove('active-category');
        });

        // Add active class to the clicked button or the first button on initial load
        if (clickedButton) {
            clickedButton.classList.add('active-category');
        } else {
            const firstButton = document.querySelector('.category-btn');
            if (firstButton) {
                firstButton.classList.add('active-category');
            }
        }

        fetch(`/get-products?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                const productGrid = document.getElementById('product-grid');
                productGrid.innerHTML = '';
                data.products.forEach(product => {
                    productGrid.innerHTML += createProductCardHTML(product);
                });
            })
            .catch(error => console.error('Error loading products:', error));
    }
    
    // ✅ NEW: បន្ថែម style សម្រាប់ប៊ូតុង category ដែល active
    const style = document.createElement('style');
    style.innerHTML = `
        .active-category {
            background-color: #4f46e5 !important; /* indigo-600 */
            color: white !important;
            border-color: #4f46e5 !important;
        }
        .dark .active-category {
            background-color: #6366f1 !important; /* indigo-500 */
        }
    `;
    document.head.appendChild(style);


    // ✅ REFINED: Function សម្រាប់ Update Cart ជាមួយ Design ថ្មី
    function updateCartDisplay(cartContent, subtotal) {
        const cartTableBody = document.getElementById('cart-table-body');
        cartTableBody.innerHTML = '';
        originalSubtotal = parseFloat(subtotal.replace(/,/g, '')) || 0;

        if (Object.keys(cartContent).length === 0) {
            cartTableBody.innerHTML = `<tr><td colspan="5" class="py-10 text-slate-400 text-center text-sm">{{ __('messages.no_items_in_cart') }}</td></tr>`;
        } else {
            for (const rowId in cartContent) {
                const item = cartContent[rowId];
                const isPreOrder = item.options.is_pre_order === 'true';
                const preOrderLabel = isPreOrder ? `<span class="block text-xs text-amber-500">Pre-Order</span>` : '';
                
                const row = `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-2 font-medium text-slate-700 dark:text-slate-200">
                        ${item.name}
                        ${preOrderLabel}
                    </td>
                    <td class="p-2 text-slate-500 dark:text-slate-400">$${parseFloat(item.price).toFixed(2)}</td>
                    <td class="p-2">
                        <input name="qty" type="number" min="1" value="${item.qty}" data-rowid="${item.rowId}"
                               class="qty-input w-16 py-1 px-2 border border-slate-300 dark:border-slate-600 rounded text-center bg-white dark:bg-slate-700 dark:text-white">
                    </td>
                    <td class="p-2 text-right font-medium text-slate-600 dark:text-slate-300">$${(item.price * item.qty).toFixed(2)}</td>
                    <td class="p-2 text-center">
                        <button type="button" class="text-slate-400 hover:text-red-500 transition-colors" onclick="removeCartItem('${item.rowId}')" title="Remove Item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </td>
                </tr>`;
                cartTableBody.innerHTML += row;
            }
        }
        calculateDue();
        attachCartEventListeners();
    }

    // ✅ REFINED: Function សម្រាប់គណនាទឹកប្រាក់
    function calculateDue() {
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const payNow = parseFloat(document.getElementById('payNow').value) || 0;
        const finalTotal = originalSubtotal - discount;
        const dueAmount = finalTotal - payNow;

        // Update display elements
        document.getElementById('subtotal').innerText = originalSubtotal.toFixed(2);
        document.getElementById('discountDisplay').innerText = discount.toFixed(2);
        document.getElementById('totalPayable').innerText = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
        
        // Update hidden form fields
        document.getElementById('orderTotalHidden').value = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
        document.getElementById('dueHidden').value = dueAmount.toFixed(2);
    }

    // --- Functionality (Add, Update, Remove Cart Item) - No major changes, kept from original logic ---

    function addProductToCartAjax(id, name, qty, price, stock) {
        const isPreOrder = stock <= 0;
        const params = new URLSearchParams({
            id, name, qty, price,
            'options[is_pre_order]': isPreOrder,
            _token: CSRF_TOKEN
        });

        fetch("/add-cart", {
            method: 'POST',
            body: params,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                toastr.error(data.error);
            } else {
                toastr.success(data.message);
                updateCartDisplay(data.cart_content, data.cart_subtotal);
            }
        })
        .catch(error => console.error('Error adding to cart:', error));
    }

    function updateCartQuantity(event) {
        const input = event.currentTarget;
        const rowId = input.dataset.rowid;
        const newQty = (parseInt(input.value) > 0) ? parseInt(input.value) : 1;
        input.value = newQty; // Ensure value is not negative or zero

        fetch(`/cart-update/${rowId}`, {
            method: 'POST',
            body: new URLSearchParams({ _token: CSRF_TOKEN, qty: newQty }),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if(data.message) toastr[data.alert_type || 'info'](data.message);
            updateCartDisplay(data.cart_content, data.cart_subtotal);
        })
        .catch(error => console.error('Error updating cart:', error));
    }

    function removeCartItem(rowId) {
        fetch(`/cart-remove/${rowId}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) toastr[data.alert_type](data.message);
            updateCartDisplay(data.cart_content, data.cart_subtotal);
        })
        .catch(error => console.error('Error removing item:', error));
    }

    function attachCartEventListeners() {
        document.querySelectorAll('#cart-table-body .qty-input').forEach(input => {
            input.addEventListener('change', updateCartQuantity);
        });
    }

    // --- Main Execution when DOM is ready ---
    document.addEventListener("DOMContentLoaded", function () {
        // Initial loads
        loadProducts();
        updateCartDisplay({!! Js::from(Cart::content()) !!}, "{{ Cart::subtotal() }}");

        // Search functionality
        document.getElementById('searchBox').addEventListener('input', function () {
            const keyword = this.value;
            fetch(`/search-pos-products?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    const productGrid = document.getElementById('product-grid');
                    productGrid.innerHTML = '';
                    data.products.forEach(product => {
                        productGrid.innerHTML += createProductCardHTML(product);
                    });
                });
        });

        // Calculation listeners
        document.getElementById('payNow').addEventListener('input', calculateDue);
        document.getElementById('discount').addEventListener('input', calculateDue);

        // Customer Modal functionality
        const modal = document.getElementById('add-customer-modal');
        const addCustomerBtn = document.getElementById('add-customer-btn');
        const cancelBtn = document.getElementById('cancel-add-customer');
        const cancelBtnX = document.getElementById('cancel-add-customer-x');
        const addCustomerForm = document.getElementById('addCustomerForm');
        
        function closeModal() {
            modal.classList.add('hidden');
            addCustomerForm.reset();
            document.querySelectorAll('#addCustomerForm [id$="_error"]').forEach(el => el.textContent = '');
        }

        addCustomerBtn.addEventListener('click', () => modal.classList.remove('hidden'));
        cancelBtn.addEventListener('click', closeModal);
        cancelBtnX.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => { if (e.target == modal) closeModal(); });
        
        addCustomerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetch("{{ route('store.customer.ajax') }}", {
                method: 'POST', body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN }
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) throw data;
                toastr.success(data.message);
                const customerSelect = document.getElementById('customer_id');
                const newOption = new Option(data.newCustomer.name, data.newCustomer.id, true, true);
                customerSelect.add(newOption, null);
                customerSelect.dispatchEvent(new Event('change')); // Trigger change event if needed
                closeModal();
            })
            .catch(errorData => {
                if (errorData.errors) {
                    Object.keys(errorData.errors).forEach(key => {
                        const errorElement = document.getElementById(`${key}_error`);
                        if (errorElement) errorElement.textContent = errorData.errors[key][0];
                    });
                }
            });
        });

        // JQuery Form Validation (Restyled for new design)
        $('#myForm').validate({
            rules: {
                customer_id: { required: true },
                payment_status: { required: true },
                pay: { required: true },
            },
            messages: { /* Messages are kept the same */ },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback text-red-500 text-xs mt-1');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('border-red-500').removeClass('border-slate-300');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('border-red-500').addClass('border-slate-300');
            },
        });
    });
</script>

{{-- Toastr notification from session --}}
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr["{{ session('alert-type') ?? 'success' }}"]("{{ session('message') }}");
        });
    </script>
@endif

@endsection