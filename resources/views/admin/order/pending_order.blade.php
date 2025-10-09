@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- ✅ START: បន្ថែម CSS សម្រាប់ Print --}}
    <style>
        @media print {
            body * { visibility: hidden; }
            #invoice-box, #invoice-box * { visibility: visible; }
            #invoice-box {
                position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 10px;
            }
            @page { size: A5; margin: 0; }
        }
    </style>
    {{-- ✅ END --}}

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-full p-0">
                <div class="flex justify-between">
                    <h2 class="text-xl text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                        <div class="px-2">{{ __('messages.pending_orders') }}</div>
                    </h2>
                    <div></div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-slate-600">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class="dark:bg-gray-800 dark:text-white h-10 border border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
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
                                    <input class="dark:bg-gray-800 dark:text-white bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center dark:bg-gray-800 bg-white rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper overflow-y-auto max-h-[520px]">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-800">
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{!! __('messages.table_no') !!}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.customer_name') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.order_date') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.payment_method') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.invoice') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.total') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.pay') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.due') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.status') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.order_type') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 bg-slate-50 dark:bg-gray-800"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.table_action') }}</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper mt-4"></div>

                </div>
            </div>
        </div>
    </div>

    <div id="invoice-box" class="hidden">
        {{-- Invoice HTML will be injected here by JavaScript --}}
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // --- កូដផ្សេងៗទៀត (មិនកែប្រែ) ---
            $('.toggle-password').on('click', function() {
                // ... (your toggle password logic remains unchanged)
            });

            // --- កូដសម្រាប់ Fetch Data និង Pagination (កែសម្រួល) ---
            function fetchData(pageUrl = "{{ route('search.order') }}") {
                let query = $('#search').val();
                let perPage = $('#perPage').val();

                $.ajax({
                    url: pageUrl, // Use the provided pageUrl
                    type: "GET",
                    data: {
                        search: query,
                        perPage: perPage
                    },
                    success: function(data) {
                        $('tbody').html(data.table);
                        // ✅ ជំហានទី២: កែ target ឱ្យចង្អុលទៅ class ថ្មី
                        $('.pagination-wrapper').html(data.pagination);
                    }
                });
            }

            fetchData();

            $('#search, #perPage').on('keyup change', function() {
                fetchData("{{ route('search.order') }}?page=1"); // Reset to page 1
            });

            // ✅ ជំហានទី៣: ធ្វើឱ្យ Pagination Links ដំណើរការ
            $(document).on('click', '.pagination-wrapper a', function(e) {
                e.preventDefault();
                let pageUrl = $(this).attr('href');
                fetchData(pageUrl);
            });

            // --- កូដសម្រាប់ Print Invoice (មិនកែប្រែ) ---
            $(document).on('click', '.print-invoice-btn', function() {
                const orderId = $(this).data('order-id');
                const invoiceBox = $('#invoice-box');

                invoiceBox.html('<div class="text-center p-20">Loading Invoice...</div>').removeClass('hidden');

                $.ajax({
                    url: `/get-invoice-html/${orderId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.html) {
                            invoiceBox.html(response.html);
                            
                            window.onafterprint = function() {
                                invoiceBox.addClass('hidden').html(''); 
                                window.onafterprint = null; 
                            };

                            window.print();

                        } else {
                            invoiceBox.html('<div class="text-center p-20 text-red-500">Could not load invoice.</div>');
                        }
                    },
                    error: function() {
                        invoiceBox.html('<div class="text-center p-20 text-red-500">Error loading data.</div>');
                    }
                });
            });
        });
    </script>
@endsection