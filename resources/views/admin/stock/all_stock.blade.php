@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform "> --}}
                <div class="lg:col-span-full p-0">
                <div class="flex justify-between">
                    <h2 class="text-xl  text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                        </svg>

                        <div class="px-2">{{ __('messages.stock') }}</div>
                    </h2>
                    <div>
                        {{-- Button Add Product --}}
                    </div>
                </div>



                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-slate-600">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage"
                                    class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">{{ __('messages.all') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="ml-3">
                            <div class="w-full max-w-sm min-w-[200px] relative">
                                <div class="relative">
                                    <input
                                        class="dark:text-white dark:bg-gray-800 bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                        placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button
                                        class="dark:bg-gray-800 absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                                        type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper overflow-y-auto max-h-[500px]">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                           {!! __('messages.table_no') !!}
                                        </p>
                                    </th>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.image') }}
                                        </p>
                                    </th>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.product_code') }}
                                        </p>
                                    </th>
                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.product_name') }}
                                        </p>
                                    </th>
                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.category') }}
                                        </p>
                                    </th>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.condition_name') }}
                                        </p>
                                    </th>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.price') }}
                                        </p>
                                    </th>


                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __(key: 'messages.inventory') }}
                                        </p>
                                    </th>

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __(key: 'messages.status') }}
                                        </p>
                                    </th>

                                    

                                    <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                        <p class="text-sm font-normal leading-none text-slate-500">
                                            {{ __('messages.table_action') }}
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>


                </div>

            </div>
        </div>


        {{-- Modal --}}
        
    </div>

    {{-- ជំនួស Modal ចាស់របស់អ្នកនៅខាងក្រោម --}}
<div id="stockAdjustmentModal" class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm " aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="stockAdjustmentForm" method="POST" action="{{ route('stock.adjust') }}">
                @csrf
                <input type="hidden" name="product_id" id="modal_product_id">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                        {{ __('messages.adjust_stock_for') }} <span id="modal_product_name" class="font-bold"></span>
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.a_t') }}</label>
                            <select id="type" name="type" required class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('messages.select_adj_type') }}</option>
                                <option value="sale_return">Sale Return (+)</option>
                                <option value="purchase_return">Purchase Return (-)</option>
                                <option value="clear_stock">Clear Damaged Stock (-)</option>
                            </select>
                        </div>

                        {{-- NEW DIV FOR SALE RETURN LIST --}}
                        <div id="saleReturnListContainer" class="hidden">
                            <label for="sale_detail_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.select_sale_transaction') }}</label>
                            <select id="sale_detail_id" name="sale_detail_id_temp" required class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                {{-- Options will be loaded here --}}
                            </select>
                            <input type="hidden" name="sale_detail_id" id="hidden_sale_detail_id">
                        </div>

                        {{-- NEW DIV FOR PURCHASE RETURN LIST --}}
                        <div id="purchaseReturnListContainer" class="hidden">
                            <label for="purchase_detail_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.select_purchase_transaction') }}</label>
                            <select id="purchase_detail_id" name="purchase_detail_id_temp" required class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                {{-- Options will be loaded here --}}
                            </select>
                            <input type="hidden" name="purchase_detail_id" id="hidden_purchase_detail_id">
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }}</label>
                            {{-- ត្រូវបិទ quantity លុះត្រាតែមានការជ្រើសរើស transaction --}}
                            <input type="number" name="quantity" id="quantity" required min="1" disabled class="block w-full px-3 py-2 mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.notes_reason') }}</label>
                            <textarea id="notes" name="notes" rows="3" required class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex mt-4 justify-end">
                    <button type="submit" class="my-5 mx-5 w-full py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-reds-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        <span>{{ __('messages.save') }}</span>
                    </button>
                    <button type="button" id="closeModalBtn" class="my-5 mx-5 w-full py-2 mt-3 text-base font-medium text-gray-700 bg-white border dark:bg-gray-700 border-gray-300 dark:border-gray-700 rounded-md shadow-sm dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                        <span>{{ __('messages.cancel') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
// ... (ទុក code ចាស់របស់អ្នកសម្រាប់ open-modal-btn, closeModalBtn, toggle-password, និង Short data ដូចដើម)
$(document).on('click', '.open-modal-btn', function() {
    const productId = $(this).data('product-id');
    const productName = $(this).data('product-name');

    $('#modal_product_id').val(productId);
    $('#modal_product_name').text(productName);
    $('#stockAdjustmentModal').removeClass('hidden');
});

$('#closeModalBtn').on('click', function() {
    $('#stockAdjustmentModal').addClass('hidden');
    $('#stockAdjustmentForm')[0].reset(); // Clear the form
});

        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                const targetId = $(this).data('target');
                const passwordField = $('#' + targetId);
                const icon = $(this).find('svg');

                // Toggle the type attribute
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);

                // Toggle the eye icon
                if (type === 'password') {
                    icon.html(
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                        ); // Eye open
                } else {
                    icon.html(
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7A10.05 10.05 0 0112 5c.424 0 .84.037 1.246.109m 3.167 3.167a3 3 0 11-4.243 4.243m4.243-4.243a3 3 0 00-4.243 4.243M3 3l3.59 3.59m0 0a9.953 9.953 0 01.442-.442L21 21"></path>'
                        ); // Eye closed
                }
            });
        });


        // Start Short data
        $(document).ready(function() {
            function fetchData(page = 1) {
                let query = $('#search').val();
                let perPage = $('#perPage').val();

                $.ajax({
                    url: "{{ route('search.stock') }}?page=" + page,
                    type: "GET",
                    data: {
                        search: query,
                        perPage: perPage
                    },
                    success: function(data) {
                        $('tbody').html(data.table);
                        $('#pagination-links').html(data.pagination);
                    }
                });
            }

            // Trigger fetch on load
            fetchData(); // ✅ Fetch 10 by default

            // Search or perPage change
            $('#search, #perPage').on('keyup change', function() {
                fetchData(); // Always page 1 when changed
            });

            // Pagination click
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });
        });

// ✅ NEW JAVASCRIPT FOR DYNAMIC MODAL CONTENT
const saleReturnContainer = $('#saleReturnListContainer');
const purchaseReturnContainer = $('#purchaseReturnListContainer');
const saleDetailSelect = $('#sale_detail_id');
const purchaseDetailSelect = $('#purchase_detail_id');
const hiddenSaleDetailId = $('#hidden_sale_detail_id');
const hiddenPurchaseDetailId = $('#hidden_purchase_detail_id');
const quantityInput = $('#quantity');
const typeSelect = $('#type');

function resetReturnContainers() {
    saleReturnContainer.addClass('hidden');
    purchaseReturnContainer.addClass('hidden');
    saleDetailSelect.html('<option value="">{{ __('messages.loading') }}</option>');
    purchaseDetailSelect.html('<option value="">{{ __('messages.loading') }}</option>');
    hiddenSaleDetailId.val('');
    hiddenPurchaseDetailId.val('');
    quantityInput.val(1).prop('disabled', true).prop('max', '');
}

function loadReturnDetails(productId, type) {
    if (type === 'sale_return' || type === 'purchase_return') {
        $.ajax({
            url: "{{ route('stock.get_return_details') }}", 
            type: "GET",
            data: {
                product_id: productId,
                type: type
            },
            success: function(data) {
                let options = '<option value="">{{ __('messages.select_transaction') }}</option>';
                const isSale = type === 'sale_return';
                
                if (data.length > 0) {
                    data.forEach(item => {
                        const id = item.id;
                        // ✅ កែសម្រួល៖ ប្រើ item.invoice_no សម្រាប់ទាំង Sale និង Purchase 
                        const transactionNo = item.invoice_no; 
                        // កែសម្រួល៖ សម្រាប់ Purchase គឺ item.purchase_date, សម្រាប់ Sale គឺ item.order_date
                        const date = isSale ? item.order_date : item.purchase_date; 
                        const qty = item.quantity;
                        
                        options += `<option value="${id}" data-qty="${qty}">
                            ${isSale ? 'SALE' : 'PURCHASE'} # ${transactionNo} | Qty: ${qty} | Date: ${date}
                        </option>`;
                    });
                } else {
                    options = `<option value="">{{ __('messages.no_transaction_found') }}</option>`;
                }
                
                if (isSale) {
                    saleDetailSelect.html(options);
                } else {
                    purchaseDetailSelect.html(options);
                }
            },
            error: function() {
                const errorMessage = '<option value="">{{ __('messages.failed_to_load_transactions') }}</option>';
                if (type === 'sale_return') {
                    saleDetailSelect.html(errorMessage);
                } else {
                    purchaseDetailSelect.html(errorMessage);
                }
            }
        });
    }
}

// Event ពេល Product ID ត្រូវបានកំណត់
$(document).on('click', '.open-modal-btn', function() {
    // ... (existing code)
    $('#stockAdjustmentForm')[0].reset(); 
    typeSelect.val(''); // ត្រូវកំណត់ type ទៅជាទទេវិញ
    resetReturnContainers();
    $('#stockAdjustmentModal').removeClass('hidden'); // បើក modal ក្រោយ reset
});


// Event ពេលអ្នកប្រើផ្លាស់ប្តូរប្រភេទ (Type)
typeSelect.on('change', function() {
    const selectedType = $(this).val();
    const productId = $('#modal_product_id').val();
    
    resetReturnContainers(); // លាក់ទាំងអស់
    quantityInput.val(1).prop('disabled', true).prop('max', ''); 

    if (selectedType === 'sale_return') {
        saleReturnContainer.removeClass('hidden');
        loadReturnDetails(productId, 'sale_return');
    } else if (selectedType === 'purchase_return') {
        purchaseReturnContainer.removeClass('hidden');
        loadReturnDetails(productId, 'purchase_return');
    } else if (selectedType === 'clear_stock') {
        quantityInput.prop('disabled', false).prop('max', ''); // អនុញ្ញាតឲ្យកែបរិមាណសម្រាប់ Clear Stock
    }
});


// Event ពេលអ្នកប្រើជ្រើសរើស Sale Detail
saleDetailSelect.on('change', function() {
    const selectedQty = $(this).find(':selected').data('qty');
    const selectedId = $(this).val();
    
    if (selectedId) {
        quantityInput.val(selectedQty).prop('max', selectedQty).prop('disabled', false);
        hiddenSaleDetailId.val(selectedId);
    } else {
        quantityInput.val(1).prop('max', '').prop('disabled', true);
        hiddenSaleDetailId.val('');
    }
});

// Event ពេលអ្នកប្រើជ្រើសរើស Purchase Detail
purchaseDetailSelect.on('change', function() {
    const selectedQty = $(this).find(':selected').data('qty');
    const selectedId = $(this).val();
    
    if (selectedId) {
        quantityInput.val(selectedQty).prop('max', selectedQty).prop('disabled', false);
        hiddenPurchaseDetailId.val(selectedId);
    } else {
        quantityInput.val(1).prop('max', '').prop('disabled', true);
        hiddenPurchaseDetailId.val('');
    }
});
</script>


@endsection
