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
                        
                        {{-- ✅ NEW: SELECT PER PAGE FOR RETURN LIST --}}
                        <div id="returnPerPageContainer" class="hidden">
                            <label for="returnPerPage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.show') }} (Items Per Page)</label>
                            <select id="returnPerPage" name="returnPerPage" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
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
                <div class="flex mt-4 justify-end space-x-4">
    <button type="submit"
        class="py-2 px-4 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
        <span>{{ __('messages.save') }}</span>
    </button>
    <button type="button" id="closeModalBtn"
        class="py-2 px-4 text-base font-medium text-gray-700 bg-white border dark:bg-gray-700 border-gray-300 dark:border-gray-700 rounded-md shadow-sm dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none sm:text-sm">
        <span>{{ __('messages.cancel') }}</span>
    </button>
</div>

            </form>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
// ----------------------------------------------------------------------
// 1. DOM ELEMENT REFERENCES
// ----------------------------------------------------------------------
const saleReturnContainer = $('#saleReturnListContainer');
const purchaseReturnContainer = $('#purchaseReturnListContainer');
const saleDetailSelect = $('#sale_detail_id');
const purchaseDetailSelect = $('#purchase_detail_id');
const hiddenSaleDetailId = $('#hidden_sale_detail_id');
const hiddenPurchaseDetailId = $('#hidden_purchase_detail_id');
const quantityInput = $('#quantity');
const typeSelect = $('#type');
const notesInput = $('#notes'); 

// ✅ NEW: REFERENCES សម្រាប់ទំហំទំព័រ
const returnPerPageContainer = $('#returnPerPageContainer');
const returnPerPageSelect = $('#returnPerPage');


// ----------------------------------------------------------------------
// 2. HELPER FUNCTIONS
// ----------------------------------------------------------------------

/**
 * មុខងារសម្រាប់កំណត់ Modal ត្រឡប់ទៅសភាពដើមវិញ (Reset)
 */
function resetReturnContainers() {
    saleReturnContainer.addClass('hidden');
    purchaseReturnContainer.addClass('hidden');
    // ✅ NEW: លាក់ container សម្រាប់រើសទំហំទំព័រ
    returnPerPageContainer.addClass('hidden'); 
    
    // បំផ្លាញ Select2 ចោលពេលបិទ ឬប្តូរ Type
    if (saleDetailSelect.hasClass("select2-hidden-accessible")) {
        saleDetailSelect.select2('destroy');
    }
    if (purchaseDetailSelect.hasClass("select2-hidden-accessible")) {
        purchaseDetailSelect.select2('destroy');
    }
    
    // កំណត់តម្លៃ input ត្រឡប់ទៅទទេវិញ
    saleDetailSelect.html('');
    purchaseDetailSelect.html('');
    hiddenSaleDetailId.val('');
    hiddenPurchaseDetailId.val('');
    quantityInput.val(1).prop('disabled', true).prop('max', '');
    notesInput.val('');
}

/**
 * មុខងារសម្រាប់បង្កើត Select2 AJAX Searchable Dropdown
 * @param {jQuery} selectElement - Element របស់ Select (ឧ. #sale_detail_id)
 * @param {string} type - ប្រភេទ (sale_return/purchase_return)
 * @param {number} productId - ID របស់ផលិតផល
 */
function initSelect2(selectElement, type, productId) {
    // ✅ NEW: ទាញយកទំហំទំព័រពី select field ថ្មី
    const pageSize = returnPerPageSelect.val(); 

    // បំផ្លាញ Select2 ចាស់បើមាន
    if (selectElement.hasClass("select2-hidden-accessible")) {
        selectElement.select2('destroy');
    }
    
    selectElement.select2({
        dropdownParent: $('#stockAdjustmentModal'), // ត្រូវកំណត់ Dropdown Parent នេះ
        placeholder: '{{ __('messages.search_transaction_by_invoice') }}',
        allowClear: true,
        ajax: {
            url: "{{ route('stock.get_return_details') }}", // ប្រើ Route ដដែល
            dataType: 'json',
            delay: 250, 
            data: function (params) {
                return {
                    searchTerm: params.term, // ពាក្យដែលគេវាយ (Live Search)
                    page: params.page || 1, // ទំព័រ (Pagination)
                    pageSize: pageSize, // ✅ NEW: បញ្ជូនទំហំទំព័រថ្មី
                    product_id: productId,
                    type: type,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                // ត្រូវបញ្ជូនលទ្ធផលត្រឡប់មកវិញតាម format របស់ Select2
                return {
                    results: data.results, 
                    pagination: {
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        // ខ្ញុំលុប minimumInputLength: 1 ចេញដើម្បីឲ្យវាបង្ហាញ 10 ចុងក្រោយពេលចុចដំបូង
        // minimumInputLength: 1, 
        templateSelection: function(data, container) {
            // ប្រើ data.text សម្រាប់បង្ហាញតម្លៃដែលបានជ្រើសរើស
            return data.text;
        }
    });
}


// ----------------------------------------------------------------------
// 3. EVENT LISTENERS
// ----------------------------------------------------------------------

// Event ពេលបើក Modal
$(document).on('click', '.open-modal-btn', function() {
    const productId = $(this).data('product-id');
    const productName = $(this).data('product-name');
    
    $('#modal_product_id').val(productId);
    $('#modal_product_name').text(productName);
    
    $('#stockAdjustmentForm')[0].reset(); 
    typeSelect.val(''); 
    resetReturnContainers(); // Reset ទាំងអស់ពេលបើក Modal
    $('#stockAdjustmentModal').removeClass('hidden'); 
});

// Event ពេលបិទ Modal
$('#closeModalBtn').on('click', function() {
    resetReturnContainers(); // ត្រូវ Reset ពេលបិទផងដែរ
    $('#stockAdjustmentModal').addClass('hidden');
    $('#stockAdjustmentForm')[0].reset(); // Clear the form
});


// Event ពេលអ្នកប្រើផ្លាស់ប្តូរប្រភេទ (Type)
typeSelect.on('change', function() {
    const selectedType = $(this).val();
    const productId = $('#modal_product_id').val();
    
    resetReturnContainers(); // Reset ទាំងអស់មុនពេលកំណត់ថ្មី
    
    // ✅ NEW: បង្ហាញ Select field សម្រាប់ទំហំទំព័រ ពេលជ្រើសរើស Return
    if (selectedType === 'sale_return' || selectedType === 'purchase_return') {
        returnPerPageContainer.removeClass('hidden');

        if (selectedType === 'sale_return') {
            saleReturnContainer.removeClass('hidden');
            // ហៅ Select2 សម្រាប់ Sale
            initSelect2(saleDetailSelect, 'sale_return', productId); 
            
        } else if (selectedType === 'purchase_return') {
            purchaseReturnContainer.removeClass('hidden');
            // ហៅ Select2 សម្រាប់ Purchase
            initSelect2(purchaseDetailSelect, 'purchase_return', productId); 
        }
        
    } else if (selectedType === 'clear_stock') {
        // Clear Stock មិនត្រូវការ Select2
        quantityInput.prop('disabled', false).prop('max', '').val(1); 
    }
});

// ✅ NEW: Event ពេលអ្នកប្រើផ្លាស់ប្តូរទំហំទំព័រ (Pagination Size)
returnPerPageSelect.on('change', function() {
    const selectedType = typeSelect.val();
    const productId = $('#modal_product_id').val();
    
    // បង្កើត Select2 ឡើងវិញដើម្បី reload ទិន្នន័យថ្មី
    if (selectedType === 'sale_return') {
        // Clear Select2 value ដើម្បីចាប់ផ្តើមថ្មី
        saleDetailSelect.val(null).trigger('change');
        initSelect2(saleDetailSelect, 'sale_return', productId); 
    } else if (selectedType === 'purchase_return') {
         // Clear Select2 value ដើម្បីចាប់ផ្តើមថ្មី
        purchaseDetailSelect.val(null).trigger('change');
        initSelect2(purchaseDetailSelect, 'purchase_return', productId); 
    }
});


// Event ពេលអ្នកប្រើជ្រើសរើស Sale Detail (ពី Select2)
saleDetailSelect.on('select2:select', function(e) {
    const data = e.params.data;
    const selectedQty = data.qty;
    const selectedId = data.id;

    if (selectedId) {
        quantityInput.val(selectedQty).prop('max', selectedQty).prop('disabled', false);
        hiddenSaleDetailId.val(selectedId);
        hiddenPurchaseDetailId.val(''); // Clear the other hidden field
    } else {
        // Fallback for empty selection
        quantityInput.val(1).prop('max', '').prop('disabled', true);
        hiddenSaleDetailId.val('');
    }
});

// Event ពេលអ្នកប្រើជ្រើសរើស Purchase Detail (ពី Select2)
purchaseDetailSelect.on('select2:select', function(e) {
    const data = e.params.data;
    const selectedQty = data.qty;
    const selectedId = data.id;

    if (selectedId) {
        quantityInput.val(selectedQty).prop('max', selectedQty).prop('disabled', false);
        hiddenPurchaseDetailId.val(selectedId);
        hiddenSaleDetailId.val(''); // Clear the other hidden field
    } else {
        // Fallback for empty selection
        quantityInput.val(1).prop('max', '').prop('disabled', true);
        hiddenPurchaseDetailId.val('');
    }
});

// Event ពេល Clear Selection (ពី Select2)
saleDetailSelect.on('select2:unselect', function(e) {
    quantityInput.val(1).prop('max', '').prop('disabled', true);
    hiddenSaleDetailId.val('');
});

purchaseDetailSelect.on('select2:unselect', function(e) {
    quantityInput.val(1).prop('max', '').prop('disabled', true);
    hiddenPurchaseDetailId.val('');
});


// ----------------------------------------------------------------------
// 4. Stock Table (Short data / Pagination) - កូដនៅដដែល
// ----------------------------------------------------------------------

$(document).ready(function() {
    // ⭐ NEW: Global variables សម្រាប់តាមដានការ Short
    let currentSortBy = 'id'; // ប្រើ ID ជា default
    let currentSortOrder = 'asc'; 
    
    // ⭐ UPDATED: fetchData ទទួលយក sort parameters
    function fetchData(page = 1) {
        let query = $('#search').val();
        let perPage = $('#perPage').val();

        $.ajax({
            url: "{{ route('search.stock') }}?page=" + page,
            type: "GET",
            data: {
                search: query,
                perPage: perPage,
                // ✅ NEW: បន្ថែម Sort Parameters
                sortBy: currentSortBy,
                sortOrder: currentSortOrder
            },
            success: function(data) {
                $('tbody').html(data.table);
                // ត្រូវផ្លាស់ប្តូរ ID/Class នៃតំបន់ pagination របស់អ្នក
                $('.pagination-container').html(data.pagination); 
                
                // ⭐ NEW: បន្ថែម Icon Short ទៅលើ Header
                updateSortIcons();
            }
        });
    }

    // ⭐ NEW: មុខងារសម្រាប់បន្ថែម Icon ទៅលើ Header
    function updateSortIcons() {
        $('th[data-sortable]').each(function() {
            $(this).find('.sort-icon').remove();
            if ($(this).data('sort-by') === currentSortBy) {
                const icon = currentSortOrder === 'asc' 
                    ? '<svg class="w-4 h-4 ml-1 inline sort-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>' 
                    : '<svg class="w-4 h-4 ml-1 inline sort-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
                $(this).append(icon);
            } else {
                // Icon សម្រាប់ Column ដែលមិនទាន់ Short
                const defaultIcon = '<svg class="w-4 h-4 ml-1 inline sort-icon opacity-30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5.83L15.17 9l1.41-1.41L12 3 7.41 7.59 8.83 9 12 5.83zM12 18.17L8.83 15l-1.41 1.41L12 21l4.59-4.59L15.17 15 12 18.17z"/></svg>';
                $(this).append(defaultIcon);
            }
        });
    }


    // ⭐ NEW: Event Listener សម្រាប់ Short
    $(document).on('click', 'th[data-sortable]', function() {
        const newSortBy = $(this).data('sort-by');
        
        if (newSortBy === currentSortBy) {
            // ប្ដូរទិសដៅ Short 
            currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            // Short លើ Column ថ្មី
            currentSortBy = newSortBy;
            currentSortOrder = 'asc'; // អាចកំណត់ 'desc' វិញក៏បាន តែ 'asc' ជាទូទៅ
        }
        
        fetchData(1); // ត្រឡប់ទៅទំព័រទី 1 ពេល Short
    });


    // Trigger fetch on load
    fetchData(); 

    // Search or perPage change
    $('#search, #perPage').on('keyup change', function() {
        fetchData(); // Always page 1 when changed
    });

    // Pagination click (ត្រូវបន្ថែម class 'pagination-container' នៅជុំវិញ pagination links របស់អ្នក)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        // ប្រើ regExp ដើម្បីទាញ page number
        let page = $(this).attr('href').split('page=')[1].split('&')[0]; 
        fetchData(page);
    });
});
</script>
@endsection