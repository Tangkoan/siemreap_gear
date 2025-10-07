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
    const returnPerPageContainer = $('#returnPerPageContainer');
    const typeSelect = $('#type');
    const quantityInput = $('#quantity');
    const modal = $('#stockAdjustmentModal');
    const form = $('#stockAdjustmentForm');

    // ----------------------------------------------------------------------
    // 2. STOCK TABLE AJAX (Keep the original logic)
    // ----------------------------------------------------------------------
    function searchStock() {
        const search = $('#search').val();
        const perPage = $('#perPage').val();

        $.ajax({
            url: "{{ route('search.stock') }}",
            type: 'GET',
            data: {
                search: search,
                perPage: perPage
            },
            success: function(response) {
                $('tbody').html(response.table);
                $('.pagination-wrapper').html(response.pagination); // Make sure you have a wrapper for pagination
            },
            error: function(xhr) {
                console.error("Error fetching data: ", xhr);
            }
        });
    }

    // Initial load and event listeners for search/pagination
    $(document).ready(function() {
        searchStock(); // Initial load

        $('#search, #perPage').on('input change', function() {
            searchStock();
        });

        // Event listener for pagination links (requires wrapper class)
        $(document).on('click', '.pagination-wrapper a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('tbody').html(response.table);
                    $('.pagination-wrapper').html(response.pagination);
                }
            });
        });

    });

    // ----------------------------------------------------------------------
    // 3. STOCK ADJUSTMENT MODAL & SELECT2 LOGIC
    // ----------------------------------------------------------------------

    // A. Open Modal Event
    $(document).on('click', '.open-modal-btn', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');

        // Reset the form and modal state
        form[0].reset();
        $('#modal_product_id').val(productId);
        $('#modal_product_name').text(productName);
        modal.removeClass('hidden');
        
        // Reset dynamic fields
        saleReturnContainer.addClass('hidden');
        purchaseReturnContainer.addClass('hidden');
        returnPerPageContainer.addClass('hidden');
        quantityInput.prop('disabled', false); // Allow quantity input for Clear Stock
        
        // Re-initialize Select2 to ensure product_id is set for the AJAX call
        initReturnSelect2('sale_return'); 
        initReturnSelect2('purchase_return'); 
    });

    // B. Close Modal Event
    $('#closeModalBtn').on('click', function() {
        modal.addClass('hidden');
    });
    
    // C. Type Change Event
    typeSelect.on('change', function() {
        const type = $(this).val();
        
        // Hide all extra fields by default
        saleReturnContainer.addClass('hidden');
        purchaseReturnContainer.addClass('hidden');
        returnPerPageContainer.addClass('hidden');
        quantityInput.prop('disabled', false).val(''); // Reset and enable for Clear Stock
        
        // Clear hidden IDs and remove required attributes on hidden fields
        $('#hidden_sale_detail_id').val('');
        $('#hidden_purchase_detail_id').val('');
        $('#sale_detail_id').prop('required', false);
        $('#purchase_detail_id').prop('required', false);

        if (type === 'sale_return') {
            saleReturnContainer.removeClass('hidden');
            returnPerPageContainer.removeClass('hidden');
            quantityInput.prop('disabled', true).val(''); // Disable quantity until a Sale ID is selected
            $('#sale_detail_id').prop('required', true); // Add required attribute
            
        } else if (type === 'purchase_return') {
            purchaseReturnContainer.removeClass('hidden');
            returnPerPageContainer.removeClass('hidden');
            quantityInput.prop('disabled', true).val(''); // Disable quantity until a Purchase ID is selected
            $('#purchase_detail_id').prop('required', true); // Add required attribute
            
        } else if (type === 'clear_stock') {
            // Clear Stock: Quantity is enabled and is the only required field
            // No action needed on return containers
        }
    });

    // D. Select2 Initialization Function
    function initReturnSelect2(type) {
        let selectElement = (type === 'sale_return') ? $('#sale_detail_id') : $('#purchase_detail_id');
        let hiddenInput = (type === 'sale_return') ? $('#hidden_sale_detail_id') : $('#hidden_purchase_detail_id');
        
        // Destroy previous Select2 instance before re-initializing
        if (selectElement.data('select2')) {
            selectElement.select2('destroy');
        }

        selectElement.select2({
            dropdownParent: modal, // Important for modal display
            placeholder: (type === 'sale_return') ? 'Search by Sale Invoice No.' : 'Search by Purchase Invoice No.',
            allowClear: true,
            ajax: {
                url: "{{ route('stock.get_return_details') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // Search term
                        page: params.page || 1, // Page number
                        pageSize: $('#returnPerPage').val(), // Items per page from custom select
                        product_id: $('#modal_product_id').val(),
                        type: type
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1 // Search only starts after 1 character
        });

        // E. Select2 Change Event
        selectElement.on('select2:select', function (e) {
            const data = e.params.data;
            if (data && data.qty) {
                // Set Quantity input to the Max Qty from the transaction
                quantityInput.val(data.qty).attr('max', data.qty).prop('disabled', false);
                // Set the hidden input value (ID of the transaction detail)
                hiddenInput.val(data.id);
            }
        });
        
        // Clear Quantity and Hidden ID when selection is cleared
        selectElement.on('select2:unselect select2:clear', function (e) {
             quantityInput.val('').attr('max', '').prop('disabled', true);
             hiddenInput.val('');
        });
    }

    // F. Return Per Page Change Event (Re-initialize Select2 on change)
    $('#returnPerPage').on('change', function() {
        const type = typeSelect.val();
        if (type === 'sale_return') {
            initReturnSelect2('sale_return');
        } else if (type === 'purchase_return') {
            initReturnSelect2('purchase_return');
        }
    });

</script>
@endsection