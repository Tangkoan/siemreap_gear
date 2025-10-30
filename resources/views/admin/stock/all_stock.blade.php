@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- ✅✅✅ ចាប់ផ្តើម៖ បន្ថែមកូដ CSS សម្រាប់ Dark Mode របស់ Select2 នៅទីនេះ ✅✅✅ --}}
    <style>
        /* This styles the Select2 elements only when the parent has the 'dark' class */
        .dark .select2-dropdown {
            background-color: #1f2937; /* bg-gray-800 */
            border: 1px solid #4b5563; /* border-gray-600 */
        }

        .dark .select2-search__field {
            background-color: #374151; /* bg-gray-700 */
            color: #d1d5db; /* text-gray-300 */
            border: 1px solid #4b5563; /* border-gray-600 */
        }

        .dark .select2-results__option {
            color: #d1d5db; /* text-gray-300 */
        }

        /* Style for the hovered/highlighted option */
        .dark .select2-results__option--highlighted {
            background-color: #3b82f6; /* bg-blue-500 */
            color: white;
        }

        /* Style for the main selection box */
        .dark .select2-selection--single {
            background-color: #374151 !important; /* bg-gray-700 */
            border: 1px solid #4b5563 !important; /* border-gray-600 */
            height: 32px !important; /* កំណត់កម្ពស់ប្រអប់ (អាចប្តូរលេខបាន) */
        }

        /* Style for the selected text in the box */
        .dark .select2-selection__rendered {
            color: #d1d5db !important; /* text-gray-300 */
        }

        /* Style for the dropdown arrow */
        .dark .select2-selection__arrow b {
            border-color: #9ca3af transparent transparent transparent !important; /* gray-400 */
        }

        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }

    </style>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">
            <div class="p-0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl text-default flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                        </svg>
                        {{ __('messages.stock') }}
                    </h2>
                </div>

                <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-defalut">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class="text-defalut card-dynamic-bg h-10 border border-primary dark:border-black-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
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
                                    <input class="card-dynamic-bg text-defalut w-full pr-11 h-10 pl-3 py-2  text-sm border-primary  rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center   rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="overflow-x-auto rounded-md card-dynamic-bg">
                    <table class="w-full text-left table-auto min-w-max ">
                        <thead >
                            <tr>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{!! __('messages.table_no') !!}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.image') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.product_code') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.product_name') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.category') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.condition_name') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.price') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200 text-center">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.inventory') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.status') }}</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-medium text-primary">{{ __('messages.table_action') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tbody"></tbody>
                    </table>
                </div>
                <div class="pagination-wrapper mt-4"></div>
            </div>
        </div>
    </div>

    <div id="stockAdjustmentModal" class="hidden fixed inset-0 z-[100] backdrop-blur-sm"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="inline-block card-dynamic-bg rounded-lg shadow-xl w-full max-w-lg p-6">
                <form clas id="stockAdjustmentForm" method="POST" action="{{ route('stock.adjust') }}">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <h3 class="text-lg font-medium text-defalut" id="modal-title">
                        {{ __('messages.adjust_stock_for') }} <span id="modal_product_name" class="font-bold"></span></h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="type"
                                class="block text-sm font-medium text-defalut">{{ __('messages.a_t') }}</label>
                            <select id="type" name="type" required
                                class="block w-full mt-1 card-dynamic-bg text-defalut dark:border-gray-600 rounded-md shadow-sm"></select>
                        </div>
                        <div id="returnPerPageContainer" class="hidden">
                            <label for="returnPerPage"
                                class="block text-sm font-medium text-defalut">{{ __('messages.show') }}</label>
                            <select id="returnPerPage"
                                class="block w-full mt-1 card-dynamic-bg text-defalut dark:border-gray-600 rounded-md shadow-sm">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                            </select>
                        </div>
                        <div id="saleReturnListContainer" class="hidden">
                            <label for="sale_detail_id"
                                class="block text-sm font-sm text-defalut">{{ __('messages.select_sale_transaction') }}</label>
                            <select id="sale_detail_id" class="block w-full mt-1 text-defalut card-dynamic-bg"></select>
                            <input class="card-dynamic-bg text-defalut" type="hidden" name="sale_detail_id"  id="hidden_sale_detail_id">
                        </div>
                        <div id="purchaseReturnListContainer" class="hidden">
                            <label for="purchase_detail_id"
                                class="block text-sm font-medium text-defalut">{{ __('messages.select_purchase_transaction') }}</label>
                            <select id="purchase_detail_id" class="block w-full mt-1 card-dynamic-bg text-defalut"></select>
                            <input type="hidden" name="purchase_detail_id" id="hidden_purchase_detail_id">
                        </div>
                        <div>
                            <label for="quantity"
                                class="block text-sm font-medium text-defalut ">{{ __('messages.quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" required min="1" disabled
                                class="block w-full mt-1 card-dynamic-bg dark:border-gray-600 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="notes"
                                class="block text-sm font-medium text-defalut">{{ __('messages.notes_reason') }}</label>
                            <textarea id="notes" name="notes" rows="3" required
                                class="block w-full mt-1 card-dynamic-bg dark:border-gray-600 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="flex mt-6 justify-end space-x-4">
                        <button type="button" id="closeModalBtn"
                            class="py-2 px-4  bg-gray-500 text-white dark:bg-gray-700 dark:text-gray-200  rounded-md">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="py-2 px-4 bg-primary text-white rounded-md">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const modal = $('#stockAdjustmentModal');
            const form = $('#stockAdjustmentForm');
            const typeSelect = $('#type');
            const quantityInput = $('#quantity');
            const saleReturnContainer = $('#saleReturnListContainer');
            const purchaseReturnContainer = $('#purchaseReturnListContainer');
            const returnPerPageContainer = $('#returnPerPageContainer');

            function searchStock(url = "{{ route('search.stock') }}") {
                $.ajax({
                    url: url,
                    data: {
                        search: $('#search').val(),
                        perPage: $('#perPage').val()
                    },
                    success: response => {
                        $('tbody').html(response.table);
                        $('.pagination-wrapper').html(response.pagination);
                    }
                });
            }

            $('#search, #perPage').on('input change', () => searchStock());
            $('.pagination-wrapper').on('click', 'a', function(e) {
                e.preventDefault();
                searchStock($(this).attr('href'));
            });
            searchStock();

            $(document).on('click', '.open-modal-btn', function() {
                form[0].reset();
                $('#modal_product_id').val($(this).data('product-id'));
                $('#modal_product_name').text($(this).data('product-name'));

                // Reset and clear Select2 fields
                $('#sale_detail_id, #purchase_detail_id').each(function() {
                    if ($(this).data('select2')) $(this).select2('destroy');
                    $(this).empty();
                });

                // Populate and reset the 'type' dropdown
                typeSelect.html(`
            <option value="">{{ __('messages.select_adj_type') }}</option>
            <option value="sale_return">Sale Return (+)</option>
            <option value="purchase_return">Purchase Return (-)</option>
            <option value="clear_stock">Clear Damaged Stock (-)</option>
        `).val('');

                // Hide conditional fields
                saleReturnContainer.add(purchaseReturnContainer).add(returnPerPageContainer).addClass(
                    'hidden');
                quantityInput.prop('disabled', true);
                modal.removeClass('hidden');
            });

            $('#closeModalBtn').on('click', () => modal.addClass('hidden'));

            typeSelect.on('change', function() {
                const type = $(this).val();
                saleReturnContainer.add(purchaseReturnContainer).add(returnPerPageContainer).addClass(
                    'hidden');
                quantityInput.prop('disabled', true).val('');

                if (type === 'sale_return' || type === 'purchase_return') {
                    returnPerPageContainer.removeClass('hidden');
                    if (type === 'sale_return') saleReturnContainer.removeClass('hidden');
                    if (type === 'purchase_return') purchaseReturnContainer.removeClass('hidden');
                    initReturnSelect2(type);
                } else if (type === 'clear_stock') {
                    quantityInput.prop('disabled', false);
                }
            });

            function initReturnSelect2(type) {
                const selectElement = (type === 'sale_return') ? $('#sale_detail_id') : $('#purchase_detail_id');
                const hiddenInput = (type === 'sale_return') ? $('#hidden_sale_detail_id') : $(
                    '#hidden_purchase_detail_id');

                if (selectElement.data('select2')) selectElement.select2('destroy');
                selectElement.empty();

                selectElement.select2({
                    dropdownParent: modal,
                    placeholder: (type === 'sale_return' ? 'Search by Sale Invoice' :
                        'Search by Purchase Invoice'),
                    ajax: {
                        url: "{{ route('stock.get_return_details') }}",
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            searchTerm: params.term,
                            page: params.page || 1,
                            pageSize: $('#returnPerPage').val(),
                            product_id: $('#modal_product_id').val(),
                            type: type,
                        }),
                        processResults: (data, params) => ({
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        }),
                        cache: false // *** IMPORTANT: Prevents showing old, cached results ***
                    }
                });

                selectElement.off('select2:select select2:unselect').on('select2:select', function(e) {
                    const data = e.params.data;
                    // ✅✅✅ CHANGE THIS LINE ✅✅✅
                    // Use 'returnable_qty' from the AJAX response to set the value and max attribute
                    quantityInput.val(data.returnable_qty).attr('max', data.returnable_qty).prop('disabled',
                        false);
                    hiddenInput.val(data.id);
                }).on('select2:unselect', function() {
                    quantityInput.val('').prop('disabled', true);
                    hiddenInput.val('');
                });
            }

            $('#returnPerPage').on('change', function() {
                const type = typeSelect.val();
                if (type) initReturnSelect2(type);
            });
        });
    </script>
@endsection
