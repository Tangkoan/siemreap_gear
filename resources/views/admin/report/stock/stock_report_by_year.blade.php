@extends('admin.admin_dashboard')
@section('admin')

    {{-- ตรวจสอบให้แน่ใจว่าได้รวม jQuery ไว้ในโปรเจ็กต์ของคุณแล้ว --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="container mx-auto p-4 md:p-1">

            {{-- Title --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="px-2">Stock Movement Report:</span>
                    <span id="report-title-date" class="px-2 text-blue-600 dark:text-blue-400">{{ $formattedDate }}</span>
                </h2>
            </div>

            {{-- Filters: Year and Search --}}
            <div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
                <div class="flex items-center space-x-2">
                    <input type="number" name="year" id="year"
                        class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm w-full"
                        value="{{ $year }}" placeholder="Enter Year" min="2000">
                </div>
                <div class="ml-3">
                    <div class="w-full max-w-sm min-w-[200px] relative">
                        <div class="relative">
                            <input
                                class="dark:text-white dark:bg-gray-800 bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                placeholder="Search for name" id="search" name="search" type="text" />
                            <button
                                class="dark:bg-gray-800 absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                                    stroke="currentColor" class="w-8 h-8 text-slate-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Report Table --}}
            <div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="table-wrapper overflow-y-auto max-h-[60vh]">
                    <table class="w-full text-left table-auto min-w-max">
                        <thead class="sticky top-0 bg-slate-50 dark:bg-gray-900">
                            <tr>
                                <th class="p-4 border-b border-slate-200"><b>
                                        <p class="text-sm font-semibold leading-none text-slate-500">Product Name</p>
                                    </b></th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-semibold leading-none text-slate-500">Opening Stock</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-semibold leading-none text-slate-500">Stock In</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-semibold leading-none text-slate-500">Stock out</p>
                                </th>
                                <th class="p-4 border-b border-slate-200">
                                    <p class="text-sm font-semibold leading-none text-slate-500">Closing Stock</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="report-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            {{-- AJAX results will be loaded here --}}
                        </tbody>
                    </table>
                </div>
                <div id="pagination-links"
                    class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    {{-- AJAX pagination links will be loaded here --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Transaction Details --}}
    <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Transaction Details
                </h3>
                <div class="mt-2 px-7 py-3">
                    <div class="overflow-y-auto max-h-[60vh]">
                        <table class="min-w-full text-left">
                            <thead class="sticky top-0 bg-slate-50 dark:bg-gray-700">
                                <tr>
                                    <th class="p-2">Date</th>
                                    <th class="p-2">Type</th>
                                    <th class="p-2">Quantity</th>
                                    <th class="p-2">Reference</th>
                                </tr>
                            </thead>
                            <tbody id="modal-table-body" class="divide-y divide-gray-200 dark:divide-gray-600">
                                {{-- Detailed rows will be loaded here by AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // --- Central Function to Fetch Summary Data ---
            function fetchData(page = 1) {
                let year = $('#year').val();
                let search = $('#search').val();

                $.ajax({
                    url: "{{ route('report.stock.by_year') }}?page=" + page,
                    type: 'GET',
                    data: { year: year, search: search, perPage: 15 },
                    beforeSend: function () {
                        $('#report-table-body').html('<tr><td colspan="5" class="text-center p-6"><span>Loading...</span></td></tr>');
                        $('#pagination-links').empty();
                    },
                    success: function (response) {
                        $('#report-table-body').html(response.table);
                        $('#pagination-links').html(response.pagination);
                        $('#report-title-date').text(response.formattedDate);
                    },
                    error: function (xhr) {
                        $('#report-table-body').html('<tr><td colspan="5" class="text-center text-red-500 p-6">Failed to load data.</td></tr>');
                    }
                });
            }

            // --- EVENT HANDLERS ---
            $('#year').on('change', function () { fetchData(1); });

            let searchTimeout;
            $('#search').on('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fetchData(1), 500);
            });

            $(document).on('click', '#pagination-links .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

            // --- CLICK ON A ROW TO SHOW DETAILS MODAL ---
            $(document).on('click', '.stock-row', function () {
                let productId = $(this).data('product-id');
                let productName = $(this).data('product-name');
                let year = $('#year').val();

                $('#modal-title').text('Details for: ' + productName + ' (' + year + ')');
                $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-4">Loading details...</td></tr>');
                $('#detailsModal').removeClass('hidden');

                $.ajax({
                    url: "{{ route('report.stock.details') }}",
                    type: 'GET',
                    data: { productId: productId, year: year },
                    success: function (transactions) {
                        let detailsHtml = '';
                        if (transactions.length > 0) {
                            transactions.forEach(function (trx) {
                                let formattedDate = new Date(trx.transaction_date).toLocaleDateString('en-GB'); // DD/MM/YYYY
                                let quantityClass = trx.transaction_type === 'Stock In' ? 'text-green-600' : 'text-red-600';
                                let quantityPrefix = trx.transaction_type === 'Stock In' ? '+' : '-';

                                detailsHtml += '<tr>';
                                detailsHtml += '<td class="p-2">' + formattedDate + '</td>';
                                detailsHtml += '<td class="p-2">' + trx.transaction_type + '</td>';
                                detailsHtml += '<td class="p-2 font-semibold ' + quantityClass + '">' + quantityPrefix + trx.quantity + '</td>';
                                detailsHtml += '<td class="p-2">' + (trx.reference || 'N/A') + '</td>';
                                detailsHtml += '</tr>';
                            });
                        } else {
                            detailsHtml = '<tr><td colspan="4" class="text-center p-4">No transactions found for this period.</td></tr>';
                        }
                        $('#modal-table-body').html(detailsHtml);
                    },
                    error: function () {
                        $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-4">Failed to load details.</td></tr>');
                    }
                });
            });

            // --- CLOSE THE MODAL ---
            $('#closeModal').on('click', function () {
                $('#detailsModal').addClass('hidden');
            });

            // --- Load initial data on page load ---
            fetchData();
        });
    </script>

@endsection