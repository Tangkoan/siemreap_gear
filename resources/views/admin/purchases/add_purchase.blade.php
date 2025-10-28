@extends('admin/admin_dashboard')
@section('admin')


{{-- 🛰️ ការបន្ថែម jQuery & jQuery Validate ពី CDN --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>



{{-- Style for Printing and Active Filter --}}
<style>
    @media print {
        body * { visibility: hidden; }
        #invoice-box, #invoice-box * { visibility: visible; }
        #invoice-box { position: absolute; left: 0; top: 0; width: 100%; }
        @page { size: A5; margin: 0; }
    }
    .active-filter {
        background-color: var(--color-primary) ;
        color: var(--color-primary) ;
        border-color: var(--color-primary) ;
    }
    .dark .active-filter {
        background-color: var(--color-primary) ; 
    }


    
    /* In the <style> tag at the top of the file */
.form-label {
    @apply block text-sm font-medium text-defalut;
}
.form-input {
    @apply block w-full px-3 py-2 text-sm bg-white dark:bg-slate-900/50 border border-primary rounded-lg shadow-sm placeholder-slate-400 dark:placeholder-slate-500 transition-colors
           focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500;
}
.form-error {
    @apply text-red-500 text-xs mt-1 h-4; /* h-4 prevents layout shift */
}
</style>

<div class="flex flex-col lg:flex-row gap-4 font-sans no-print w-full  container mx-auto p-4">
    {{-- Left Side - Product Selection --}}
    <div class="lg:w-3/5 xl:w-2/3 card-dynamic-bg p-4 rounded-xl border border-slate-200 dark:border-card-dynamic-bg flex flex-col h-[calc(100vh-90px)]">
        {{-- Header & Search --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-defalut mb-4">
            <h2 class="text-2xl font-bold text-defalut mb-2 sm:mb-0">{{ __('messages.purchase') }}</h2>
            <div class="relative w-full sm:w-64">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-defalut">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </span>
                <input type="text" placeholder="{{ __('messages.search') }}" id="searchBox" class="w-full p-2 pl-10 border border-primary rounded-lg focus:none card-dynamic-bg text-defalut" />
            </div>

            
        </div>

        {{-- Condition Filter Buttons --}}
        <div class="w-full overflow-x-auto whitespace-nowrap pb-2 mb-2">
            <span class="text-sm  text-defalut mr-2">{{ __('messages.condition') }}:</span>
            <button onclick="filterProducts('condition', 'all', this)" class="condition-btn filter-btn m-1 inline-block card-dynamic-bg hover:bg-card-dynamic-bg border border-primary px-4 py-2 rounded-lg  text-sm font-medium transition-colors active-filter">
                {{ __('messages.all') }}
            </button>
            @foreach ($conditions as $condition)
                <button onclick="filterProducts('condition', {{ $condition->id }}, this)" class="condition-btn filter-btn m-1 inline-block card-dynamic-bg hover:bg-card-dynamic-bg border border-primary px-4 py-2 rounded-lg  text-sm font-medium transition-colors">
                    {{ $condition->condition_name }}
                </button>
            @endforeach
        </div>

        {{-- Category Filter Buttons --}}
        <div class="w-full overflow-x-auto whitespace-nowrap pb-2 mb-2">
             <span class="text-sm  text-defalut mr-2">{{ __('messages.category') }}:</span>
            <button onclick="filterProducts('category', 'all', this)" class="category-btn filter-btn m-1 inline-block card-dynamic-bg border border-primary px-4 py-2 rounded-lg  text-sm font-medium transition-colors active-filter">
                {{ __('messages.all_category') }}
            </button>
            @foreach ($categories as $category)
                <button onclick="filterProducts('category', {{ $category->id }}, this)" class="category-btn filter-btn m-1 inline-block card-dynamic-bg border border-primary px-4 py-2 rounded-lg  text-sm font-medium transition-colors">
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
    <div class="lg:w-2/5 xl:w-1/3 card-dynamic-bg p-4 rounded-xl border border-defalut flex flex-col h-[calc(100vh-90px)]">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold text-defalut pb-4 border-b border-defalut">{{ __('messages.purchase_cart') }}</h2>

        {{-- Add Product Button --}}
            <button id="add-product-btn" type="button" title="{{ __('messages.add_new_product') }}" aria-label="{{ __('messages.add_new_product') }}"
                class="flex-shrink-0 bg-primary text-defalut  px-3 py-0.5 rounded-md  transition-colors  flex items-center gap-1 leading-none h-6 min-h-8">
                
                <span class="hidden md:inline font-medium text-sm ">{{ __('messages.add_product') }}</span>
            </button>

        </div>


        <div class="flex-1 mt-4 overflow-auto -mx-4 px-4">
            <table class="w-full text-sm ">
                <thead class="card-dynamic-bg sticky top-0 z-10">
                    <tr class="text-left text-defalut ">
                        <th class="p-2 ">{{ __('messages.product') }}</th>
                        <th class="p-2  text-center">{{ __('messages.qty') }}</th>
                        <th class="p-2  text-right">{{ __('messages.subtotal') }}</th>
                        <th class="p-2  text-center">{{ __('messages.table_action') }}</th>
                    </tr>
                </thead>
                <tbody id="cart-table-body" class="divide-y divide-slate-100 dark:divide-slate-700"></tbody>
            </table>
        </div>

       <div class="pt-2 border-t border-defalut space-y-1 text-xs">
            <div class="flex justify-between text-defalut">
                <span>{{ __('messages.subtotal') }}:</span>
                <span class="font-medium">$<span id="subtotalDisplay">0.00</span></span>
            </div>
            <div class="flex justify-between text-defalut">
                <span>{{ __('messages.discount') }}:</span>
                <span class="font-medium text-red-500">-$<span id="discountDisplay">0.00</span></span>
            </div>
            <div class="flex justify-between text-base font-bold text-defalut border-t border-dashed pt-1 mt-1 border-primary">
                <span>{{ __('messages.total_payable') }}:</span>
                <span>$<span id="totalPayableDisplay">0.00</span></span>
            </div>
        </div>

       <form method="POST" id="purchaseForm" action="{{ route('purchase.store') }}" class="space-y-0 pt-2 border-t border-defalut">
            @csrf
            <input type="hidden" name="total" id="orderTotalHidden">
            <input type="hidden" name="pay" id="paidHidden">
            <input type="hidden" name="due" id="dueHidden">

            {{-- Supplier Selection --}}
            <div>
                <label for="supplier_id" class="block mb-1.5 text-xs font-medium text-defalut">{{ __('messages.supplier_name') }}</label>
                <div class="relative">
                    <select name="supplier_id" id="supplier_id" required class="w-full appearance-none rounded-md border border-primary px-3 py-1.5 pr-10 text-sm text-defalut card-dynamic-bg ">
                        <option value="" disabled selected>{{ __('messages.select_supplier') }}</option>
                        @foreach ($supplier as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-supplier-btn" title="Add New Supplier" class="absolute inset-y-0 right-5 flex items-center rounded-r-md px-3  transition text-defalut focus:outline-none ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    </button>
                </div>
            </div>

            {{-- Form Grid --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                {{-- Invoice Number --}}
                <div>
                    <label for="invoice_no" class="block mb-1.5 text-xs font-medium text-defalut">{{ __('messages.invoice_no') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="invoice_no" id="invoice_no" placeholder="{{ __('messages.enter_invoice_no') }}" required class="w-full rounded-md border border-primary px-3 py-1.5 text-sm  transition-colors focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 text-defalut card-dynamic-bg">
                </div>
                
                {{-- Payment Method --}}
                <div>
                    <label for="payment_status" class="block mb-1.5 text-xs font-medium text-defalut">{{ __('messages.payment_method') }}</label>
                    <select name="payment_status" id="payment_status" required class="w-full rounded-md border border-primary px-3 py-1.5 text-sm transition-colors focus:border-red-500  focus:outline-none focus:ring-1 focus:ring-red-500 text-defalut card-dynamic-bg">
                        <option value="" disabled selected>{{ __('messages.select_payment') }}</option>
                        <option value="QrScan">Qr Scan</option>
                        <option value="HandCash">Hand Cash</option>
                    </select>
                </div>

                {{-- Discount --}}
                <div>
                    <label for="discount" class="block mb-1.5 text-xs font-medium text-defalut">{{ __('messages.discount') }} ($)</label>
                    <input type="number" name="discount" id="discount" placeholder="0.00" value="0" min="0" step="0.01" class="w-full rounded-md border border-primary px-3 py-1.5 text-sm  transition-colors focus:border-red-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-red-500 text-defalut card-dynamic-bg">
                </div>
                
                {{-- Pay Now --}}
                <div>
                    <label for="payNow" class="block mb-1.5 text-xs font-medium text-defalut">{{ __('messages.pay_now') }} ($)</label>
                    <input type="number" name="pay" id="payNow" placeholder="{{ __('messages.pay') }}" required min="0" step="0.01" class="w-full rounded-md border border-primary px-3 py-1.5 text-sm  transition-colors focus:border-red-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-red-500 text-defalut card-dynamic-bg">
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" class="bg-primary text-defalut font-medium w-full flex items-center justify-center gap-x-2 rounded-lg px-4 py-2.5 text-sm shadow-sm transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg> --}}
                    {{ __('messages.complete_purchase') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Add Product --}}
<div id="add-product-modal" class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Modal Container with Animation --}}
    <div id="add-product-modal-container" class="fixed inset-0 flex items-center justify-center p-4 opacity-0 scale-95 transition-all duration-300 ease-out">
        
        {{-- This container now mimics the Add Supplier modal's style --}}
        <div class="relative w-full max-w-xl transform rounded-xl card-dynamic-bg shadow-2xl border">
            <form id="addProductForm" novalidate>
                @csrf
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-5 border-b border-defalut">
                    <h3 id="modal-title" class="text-lg font-medium text-defalut">{{ __('messages.add_new_product') }}</h3>
                    <button id="cancel-add-product-x" type="button" class="p-1 rounded-full text-defalut transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                {{-- Modal Body with vertical fields --}}
                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="space-y-4">

                        {{-- Product Name --}}
                        <div>
                            <label for="product_name" class="form-label">{{ __('messages.product_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" id="product_name" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" placeholder="{{ __('messages.product_name') }}">
                            <div id="product_name_error" class="form-error text-red-500 text-sm mt-1"></div>
                            
                        </div>

                        {{-- Category & Condition (Side-by-side) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="form-label">{{ __('messages.category_name') }} <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
                                    <option value="" disabled selected>{{ __('messages.select_category') }}</option>
                                    @foreach ($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->category_name }}</option> @endforeach
                                </select>
                                <div id="category_id_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                             <div>
                                <label for="condition_id" class="form-label">{{ __('messages.condition_name') }} <span class="text-red-500">*</span></label>
                                <select name="condition_id" id="condition_id" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
                                    <option value="" disabled selected>{{ __('messages.select_condition') }}</option>
                                    @foreach ($conditions as $con) <option value="{{ $con->id }}">{{ $con->condition_name }}</option> @endforeach
                                </select>
                                <div id="condition_id_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <label for="modal_supplier_id" class="form-label">{{ __('messages.supplier_name') }} <span class="text-red-500">*</span></label>
                            <select name="supplier_id" id="modal_supplier_id" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
                                <option value="" disabled selected>{{ __('messages.select_supplier') }}</option>
                                @foreach ($supplier as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                            </select>
                            <div id="supplier_id_error" class="form-error text-red-500 text-sm mt-1"></div>
                        </div>
                        
                        {{-- Buying & Selling Price (Side-by-side) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="buying_price" class="form-label">{{ __('messages.cost') }} ($) <span class="text-red-500">*</span></label>
                                <input type="number" name="buying_price" id="buying_price" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" step="0.01" min="0" placeholder="0.00">
                                <div id="buying_price_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                            <div>
                                <label for="selling_price" class="form-label">{{ __('messages.price') }} ($) <span class="text-red-500">*</span></label>
                                <input type="number" name="selling_price" id="selling_price" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" step="0.01" min="0" placeholder="0.00">
                                <div id="selling_price_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                        </div>

                        {{-- Stock & Stock Alert (Side-by-side) --}}
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="product_store" class="form-label">{{ __('messages.inventory') }}  <span class="text-red-500">*</span></label>
                                <input type="number" value="0" readonly name="product_store" id="product_store" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" min="0" placeholder="e.g., 100">
                                <div id="product_store_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                            <div>
                                <label for="stock_alert" class="form-label">{{ __('messages.stock_alert') }}</label>
                                <input type="number" name="stock_alert" id="stock_alert" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" min="0" placeholder="e.g., 10">
                                <div id="stock_alert_error" class="form-error text-red-500 text-sm mt-1"></div>
                            </div>
                        </div>
                        
                        {{-- Product Details --}}
                        <div>
                            <label for="product_detail" class="form-label">{{ __('messages.details') }}</label>
                            <textarea name="product_detail" id="product_detail" rows="3" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg" placeholder="Optional details about the product..."></textarea>
                            <div id="product_detail_error" class="form-error text-red-500 text-sm mt-1"></div>
                        </div>

                        {{-- Product Image --}}
                        <div>
                            <label for="product_image_input" class="form-label">{{ __('messages.image') }}</label>
                            <input type="file" name="product_image" id="product_image_input" class="block w-full text-sm text-defalut
                                           file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                           file:text-sm file: file:bg-gray-300 dark:file:bg-gray-700
                                           file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-400 dark:hover:file:bg-gray-600
                                           cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                             <img id="image-preview" src="#" alt="Image Preview" class="mt-2 rounded-md max-h-40 hidden border border-primary" />
                            <div id="product_image_error" class="form-error text-red-500 text-sm mt-1"></div>
                        </div>

                    </div>
                </div>
                
                {{-- Modal Footer --}}
                <div class="flex justify-end gap-x-3 p-4 card-dynamic-bg rounded-lg border-t border-defalut">
                    <button id="cancel-add-product" type="button" class="px-4 py-2 card-dynamic-bg text-primary rounded-md  focus:outline-none">{{ __('messages.cancel') }}</button>
                    <button id="save-product-btn" type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-defalut rounded-md hover:bg-red-700 focus:outline-none ">
                        <span>{{ __('messages.save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal Add Supplier --}}
<div id="add-supplier-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm">
    <div class="relative top-10 sm:top-20 mx-auto w-full max-w-lg transform rounded-xl  p-6 shadow-2xl card-dynamic-bg border dark:border-slate-700">
        <div class="flex justify-between items-center pb-3 border-b border-defalut">
            <h3 class="text-lg leading-6 font-medium text-defalut">{{ __('messages.add_new_supplier') }}</h3>
            <button id="cancel-add-supplier-x" type="button" class="text-defalut"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        <form id="addSupplierForm" class="mt-4 text-left space-y-4">
            @csrf
            <div>
                <label for="supplier_name" class="block text-sm font-medium text-defalut">{{ __('messages.supplier_name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="supplier_name" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
                <div id="name_error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div>
                <label for="supplier_email" class="block text-sm font-medium text-defalut">{{ __('messages.email') }}</label>
                <input type="email" name="email" id="supplier_email" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
                <div id="email_error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div>
                <label for="supplier_phone" class="block text-sm font-medium text-defalut">{{ __('messages.phone') }}</label>
                <input type="text" name="phone" id="supplier_phone" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg">
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-defalut">{{ __('messages.notes') }}</label>
                <textarea name="notes" id="notes" rows="2" class="text-defalut mt-1 block w-full px-3 py-2 border border-primary rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 sm:text-sm card-dynamic-bg"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-x-3">
                <button id="cancel-add-supplier" type="button" class="px-4 py-2 g-defalut text-primary card-dynamic-bg font-medium rounded-md  focus:outline-none ">{{ __('messages.cancel') }}</button>
                <button id="save-supplier-btn" type="submit" class="px-4 py-2  rounded-md bg-primary text-defalut font-medium focus:outline-none">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</div>


{{-- Modal Product Details --}}
<div id="product-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative top-10 sm:top-20 mx-auto w-full max-w-2xl transform rounded-xl card-dynamic-bg p-6 shadow-2xl transition-all duration-300 text-defalut dark:border-slate-700">
        <div class="flex justify-between items-center pb-3 border-b border-defalut">
            <h3 class="text-xl leading-6 font-bold text-defalut">{{ __('messages.product_details') }}</h3>
            <button id="close-details-modal-btn" type="button" class="p-1 rounded-full text-defalut">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div id="modal-product-body" class="mt-4 text-left">
            <div id="modal-loading-state" class="text-center p-8">
                <svg class="animate-spin h-8 w-8 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="mt-2 text-sm text-defalut">Loading Details...</p>
            </div>
            <div id="modal-content-state" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img id="details-modal-image" src="" alt="Product Image" class="w-full h-64 object-cover rounded-lg border dark:border-slate-700">
                </div>
                <div class="space-y-3">
                    <h4 id="details-modal-name" class="text-2xl font-bold text-defalut"></h4>
                    <p id="details-modal-price" class="text-3xl font-light text-primary"></p>
                    <div class="text-sm space-y-2 pt-2 border-t dark:border-slate-700">
                        <p class="flex justify-between"><span class="text-defalut font-medium">Category:</span> <span id="details-modal-category" class="text-defalut"></span></p>
                        <p class="flex justify-between"><span class="text-defalut font-medium">Supplier:</span> <span id="details-modal-supplier" class="text-defalut"></span></p>
                        <p class="flex justify-between"><span class="text-defalut font-medium">Product Code:</span> <span id="details-modal-code" class="text-defalut"></span></p>
                        <p class="flex justify-between"><span class="text-defalut font-medium">Stock:</span> <span id="details-modal-stock" class="text-defalut"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>


<script>
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const supplier = document.getElementById('supplier_id').value;
        const invoice = document.getElementById('invoice_no').value;
        const payment = document.getElementById('payment_status').value;
        const pay = document.getElementById('payNow').value;

        if (!supplier || !invoice || !payment || !pay) {
            alert('សូមបំពេញទិន្នន័យទាំងអស់ដែលត្រូវការ!');
            e.preventDefault(); // បញ្ឈប់ការបញ្ជូនទម្រង់
        }
    });
</script>

<script type="text/javascript">

// ✅ ប្រើ $(document).ready() តែមួយដង γιαครอบคลุมកូដ jQuery ទាំងអស់
$(document).ready(function() {


    
    // --- GLOBAL VARIABLES ---
    let currentCategoryId = 'all';
    let currentConditionId = 'all';
    let searchTimeout;
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const defaultImagePath = "{{ asset('upload/no_image.jpg') }}";
    let cartSubtotal = 0;

    // --- UI RENDERING FUNCTIONS ---
    function createProductCardHTML(product) {
        const imageUrl = product.imageUrl || defaultImagePath;
        const conditionText = (product.condition && product.condition !== 'N/A')
            ? `<p class="text-xs text-primary font-medium">${product.condition}</p>`
            : '';

        return `
        <div class="group relative card-dynamic-bg0 rounded-lg overflow-hidden border border-defalut/50 transform transition-all duration-200 cursor-pointer hover:border-red-500 dark:hover:border-red-500 hover:shadow-lg"
             onclick="addProductToCartAjax(${product.id}, '${product.name.replace(/'/g, "\\'")}', 1, ${product.buying_price});"
             title="Click to add to purchase cart">
            <button
                onclick="showProductDetails(${product.id}, event)"
                title="View Details"
                class="absolute top-2 left-2 z-20 p-1.5 bg-white/70 dark:bg-slate-900/70 rounded-full text-defalut  transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
            </button>
            <div class="w-full h-32">
                <img class="w-full h-full object-cover" src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null; this.src='${defaultImagePath}';">
            </div>
            <div class="p-3 text-left">
                <h3 class=" text-sm text-defalut  truncate">${product.name}</h3>
                ${conditionText}
                <p class="text-sm text-defalut  mt-1">Stock: ${product.stock}</p>
                <p class="text-primary font-bold text-lg mt-1">$${parseFloat(product.buying_price).toFixed(2)}</p>
            </div>
        </div>`;
    }

    function updateCartDisplay(cartContent, subtotalString) {
        const cartTableBody = document.getElementById('cart-table-body');
        cartTableBody.innerHTML = '';
        cartSubtotal = parseFloat(subtotalString.replace(/,/g, '')) || 0;
        if (Object.keys(cartContent).length === 0) {
            cartTableBody.innerHTML = `<tr><td colspan="4" class="py-10 text-defalut text-center text-sm">{{ __('messages.no_items_in_cart') }}</td></tr>`;
        } else {
            for (const rowId in cartContent) {
                const item = cartContent[rowId];
                const row = `
                <tr class="hover:bg-primary">
                    <td class="p-2 font-medium text-defalut">${item.name}<br><span class="text-xs text-primary">$${parseFloat(item.price).toFixed(2)}</span></td>
                    <td class="p-2">
                        <input name="qty" type="number" min="1" value="${item.qty}" data-rowid="${item.rowId}" class="qty-input w-16 py-1 px-2 border border-primary rounded text-center card-dynamic-bg text-defalut">
                    </td>
                    <td class="p-2 text-right font-medium text-defalut">$${(item.price * item.qty).toFixed(2)}</td>
                    <td class="p-2 text-center">
                        <button type="button" class="text-red-800 hover:text-red-500 transition-colors" onclick="removeCartItem('${item.rowId}')" title="Remove Item"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button>
                    </td>
                </tr>`;
                cartTableBody.innerHTML += row;
            }
        }
        calculateTotals();
        attachCartEventListeners();
    }

    // --- CORE LOGIC & AJAX FUNCTIONS ---
    window.fetchProductsByFilter = function() {
        const url = `/get-products-for-purchase?category_id=${currentCategoryId}&condition_id=${currentConditionId}`;
        const productGrid = document.getElementById('product-grid');
        productGrid.innerHTML = `<p class="col-span-full text-center text-defalut p-10">Loading...</p>`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                productGrid.innerHTML = '';
                if (data.products && data.products.length > 0) {
                    data.products.forEach(product => productGrid.innerHTML += createProductCardHTML(product));
                } else {
                    productGrid.innerHTML = `<p class="col-span-full text-center text-defalut p-10">No products found for this filter.</p>`;
                }
            })
            .catch(error => console.error('Error loading products by filter:', error));
    }

    window.filterProducts = function(type, id, clickedButton) {
        if (type === 'category') { currentCategoryId = id; }
        else if (type === 'condition') { currentConditionId = id; }

        // Logic to toggle active class on buttons
        let container = clickedButton.closest('div');
        container.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));
        clickedButton.classList.add('active-filter');
        
        fetchProductsByFilter();
    }
    
    window.addProductToCartAjax = function(id, name, qty, price) {
        fetch("/purchase/add-cart", {
            method: 'POST',
            body: new URLSearchParams({ id, name, qty, price, _token: CSRF_TOKEN }),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                toastr.error(data.error);
            } else {
                updateCartDisplay(data.cart_content, data.cart_subtotal);
            }
        })
        .catch(err => console.error('Error adding to cart:', err));
    }

    function updateCartQuantity(event) {
        const input = event.currentTarget;
        const rowId = input.dataset.rowid;
        const newQty = (parseInt(input.value) > 0) ? parseInt(input.value) : 1;
        input.value = newQty;
        fetch(`/purchase/cart/update/${rowId}`, {
            method: 'POST',
            body: new URLSearchParams({ _token: CSRF_TOKEN, qty: newQty }),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => updateCartDisplay(data.cart_content, data.cart_subtotal));
    }

    window.removeCartItem = function(rowId) {
        fetch(`/purchase/cart/remove/${rowId}`)
        .then(res => res.json())
        .then(data => updateCartDisplay(data.cart_content, data.cart_subtotal));
    }

    function calculateTotals() {
        let discount = parseFloat(document.getElementById('discount').value) || 0;
        const payNow = parseFloat(document.getElementById('payNow').value) || 0;
        if (discount > cartSubtotal) {
            discount = cartSubtotal;
            document.getElementById('discount').value = discount.toFixed(2);
        }
        const finalTotal = cartSubtotal - discount;
        const dueAmount = finalTotal - payNow;
        document.getElementById('subtotalDisplay').innerText = cartSubtotal.toFixed(2);
        document.getElementById('discountDisplay').innerText = discount.toFixed(2);
        document.getElementById('totalPayableDisplay').innerText = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
        document.getElementById('orderTotalHidden').value = finalTotal > 0 ? finalTotal.toFixed(2) : '0.00';
        document.getElementById('paidHidden').value = payNow.toFixed(2);
        document.getElementById('dueHidden').value = dueAmount.toFixed(2);
    }
    
    function attachCartEventListeners() {
        document.querySelectorAll('#cart-table-body .qty-input').forEach(input => {
            input.addEventListener('change', updateCartQuantity);
        });
    }

    window.showProductDetails = function(productId, event) {
        event.stopPropagation();
        const detailsModal = document.getElementById('product-details-modal');
        const modalLoading = document.getElementById('modal-loading-state');
        const modalContent = document.getElementById('modal-content-state');
        detailsModal.classList.remove('hidden');
        modalLoading.classList.remove('hidden');
        modalContent.classList.add('hidden');
        fetch(`/get-product-details/${productId}`)
            .then(response => response.ok ? response.json() : Promise.reject('Product not found'))
            .then(data => {
                document.getElementById('details-modal-image').src = data.imageUrl || defaultImagePath;
                document.getElementById('details-modal-name').innerText = data.product_name || 'N/A';
                document.getElementById('details-modal-price').innerHTML = `Buying Price: <span class="font-bold">$${parseFloat(data.buying_price || 0).toFixed(2)}</span>`;
                document.getElementById('details-modal-category').innerText = data.category ? data.category.category_name : 'N/A';
                document.getElementById('details-modal-supplier').innerText = data.supplier ? data.supplier.name : 'N/A';
                document.getElementById('details-modal-code').innerText = data.product_code || 'N/A';
                document.getElementById('details-modal-stock').innerText = data.product_store || '0';
                modalLoading.classList.add('hidden');
                modalContent.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching product details:', error);
                modalLoading.innerHTML = `<p class="text-red-500 p-8">Could not load details.</p>`;
            });
    }

    // --- INITIALIZATION & EVENT LISTENERS ---
    fetchProductsByFilter(); 
    updateCartDisplay({!! Js::from(Cart::content()) !!}, "{{ Cart::subtotal() }}");
    
    $('#searchBox').on('input', function () {
        clearTimeout(searchTimeout);
        const keyword = this.value;

        searchTimeout = setTimeout(() => {
            const url = `/purchase/search-products?keyword=${keyword}`;
            const productGrid = $('#product-grid');
            productGrid.html(`<p class="col-span-full text-center text-defalut p-10">Searching...</p>`);
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    productGrid.html('');
                    if (data.products && data.products.length > 0) {
                        data.products.forEach(product => productGrid.append(createProductCardHTML(product)));
                    } else {
                        productGrid.html(`<p class="col-span-full text-center text-defalut p-10">No products found for "${keyword}".</p>`);
                    }
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                    productGrid.html(`<p class="col-span-full text-center text-red-500 p-10">An error occurred during search.</p>`);
                });
        }, 300); 
    });

    $('#payNow, #discount').on('input', calculateTotals);

    // --- Modal Add Product --
    // Inside your $(document).ready(function() { ... });
// Replace your previous "MODAL HANDLING for ADD PRODUCT" section with this new one.

// --- MODAL HANDLING for ADD PRODUCT (2025 UI) ---
const productModal = document.getElementById('add-product-modal');
const productModalContainer = document.getElementById('add-product-modal-container');
const addProductBtn = document.getElementById('add-product-btn');
const addProductForm = document.getElementById('addProductForm');
const cancelProductBtn = document.getElementById('cancel-add-product');
const cancelProductBtnX = document.getElementById('cancel-add-product-x');

// Image upload elements
const imageInput = document.getElementById('product_image_input');
const imagePreview = document.getElementById('image-preview');
const imageUploadPrompt = document.getElementById('image-upload-prompt');

// Function to open the modal with animation
function openProductModal() {
    productModal.classList.remove('hidden');
    // Use requestAnimationFrame to ensure the transition happens after the display property is set
    requestAnimationFrame(() => {
        productModalContainer.classList.remove('opacity-0', 'scale-95');
        productModalContainer.classList.add('opacity-100', 'scale-100');
    });
}

// Function to close the modal with animation
function closeProductModal() {
    productModalContainer.classList.remove('opacity-100', 'scale-100');
    productModalContainer.classList.add('opacity-0', 'scale-95');
    // Wait for the animation to finish before hiding the modal completely
    setTimeout(() => {
        productModal.classList.add('hidden');
        addProductForm.reset();
        // Reset image preview to its initial state
        imagePreview.classList.add('hidden');
        imagePreview.setAttribute('src', '');
        imageUploadPrompt.classList.remove('hidden');
        // Clear all validation errors
        addProductForm.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    }, 300); // Duration should match the transition duration in CSS
}

// Event Listeners for opening and closing the modal
if (addProductBtn) {
    addProductBtn.addEventListener('click', openProductModal);
}
cancelProductBtn.addEventListener('click', closeProductModal);
cancelProductBtnX.addEventListener('click', closeProductModal);

// Handle image preview
imageInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.setAttribute('src', e.target.result);
            imagePreview.classList.remove('hidden');
            imageUploadPrompt.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Form submission with AJAX
addProductForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const submitButton = document.getElementById('save-product-btn');
    const buttonSpan = submitButton.querySelector('span');
    const originalButtonText = buttonSpan.innerHTML;
    
    // Show loading state
    submitButton.disabled = true;
    buttonSpan.innerHTML = `<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

    // Clear previous errors
    addProductForm.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    
    fetch("{{ route('store.product.ajax') }}", {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN 
        }
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) throw data; 
        
        toastr.success(data.message);
        closeProductModal();
        fetchProductsByFilter(); // Refresh the product grid
    })
    .catch(errorData => {
        if (errorData.errors) {
                Object.keys(errorData.errors).forEach(key => {
                    const errorElement = document.getElementById(`${key}_error`);
                    if (errorElement) errorElement.textContent = errorData.errors[key][0];
                });
            }
    })
    .finally(() => {
        // Restore button state
        submitButton.disabled = false;
        buttonSpan.innerHTML = originalButtonText;
    });
});
    // -- End Modal Add product --
    
    // --- MODAL HANDLING ---
    const supplierModal = document.getElementById('add-supplier-modal');
    const addSupplierForm = document.getElementById('addSupplierForm');
    function closeSupplierModal() {
        supplierModal.classList.add('hidden');
        addSupplierForm.reset();
        addSupplierForm.querySelectorAll('[id$="_error"]').forEach(el => el.textContent = '');
    }
    document.getElementById('add-supplier-btn').addEventListener('click', () => supplierModal.classList.remove('hidden'));
    document.getElementById('cancel-add-supplier').addEventListener('click', closeSupplierModal);
    document.getElementById('cancel-add-supplier-x').addEventListener('click', closeSupplierModal);
    
    addSupplierForm.addEventListener('submit', function (e) {
        e.preventDefault();
        fetch("{{ route('store.supplier.ajax') }}", {
            method: 'POST', body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN }
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) throw data;
            toastr.success(data.message);
            const supplierSelect = document.getElementById('supplier_id');
            const newOption = new Option(data.newSupplier.name, data.newSupplier.id, true, true);
            supplierSelect.add(newOption, null);
            closeSupplierModal();
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

    const detailsModal = document.getElementById('product-details-modal');
    const closeDetailsBtn = document.getElementById('close-details-modal-btn');
    function closeDetailsModal() { detailsModal.classList.add('hidden'); }
    closeDetailsBtn.addEventListener('click', closeDetailsModal);
    detailsModal.addEventListener('click', (e) => { if (e.target === detailsModal) closeDetailsModal(); });

}); // End of $(document).ready()


function filterProducts(type, id, element) {
        // 1. កំណត់ឈ្មោះ group class
        let groupClassName = '';
        if (type === 'condition') {
            groupClassName = 'condition-btn';
        } else if (type === 'category') {
            groupClassName = 'category-btn';
        }

        // 2. ដក 'active-filter' ចេញពីប៊ូតុងទាំងអស់ក្នុង group នេះ
        if (groupClassName) {
            const allButtonsInGroup = document.querySelectorAll('.' + groupClassName);
            allButtonsInGroup.forEach(btn => {
                btn.classList.remove('active-filter');
            });
        }

        // 3. បន្ថែម 'active-filter' ទៅប៊ូតុងដែលបានចុច
        if (element) {
            element.classList.add('active-filter');
        }

        // ---
        // ដាក់កូដ AJAX ឬ Logic សម្រាប់ filter ផលិតផលរបស់អ្នកនៅទីនេះ
        // ឧទាហរណ៍: console.log('Filtering by', type, 'with id', id);
        // ---
    }
</script>


@endsection