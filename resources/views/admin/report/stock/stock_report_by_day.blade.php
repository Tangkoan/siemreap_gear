@extends('admin.admin_dashboard')
@section('admin')

    {{-- Make sure you have jQuery included in your project --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="container mx-auto p-4 md:p-1">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 flex items-center">
                    {{-- Icon and Title --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="px-2">Stock Movement Report:</span>
                    {{-- Dynamic Date Span --}}
                    <span id="report-title-date" class="px-2 text-blue-600 dark:text-blue-400">{{ $formattedDate }}</span>
                </h2>
                {{-- Note: Action buttons like Add, Import, Export are removed as they are not relevant to this report --}}
            </div>

            <div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
                {{-- Date Picker --}}
                <div class="flex items-center space-x-2">

                    <input type="date" name="date" id="date"
                        class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm w-full"
                        value="{{ $date }}">
                </div>

                {{-- Per Page Dropdown --}}
                {{-- <div class="flex-shrink-0">
                    <label for="perPage"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Show</label>
                    <select id="perPage" name="perPage"
                        class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm">
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="all">All</option>
                    </select>
                </div> --}}



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

            <div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="table-wrapper overflow-y-auto max-h-[60vh]">
                    <table class="w-full text-left table-auto min-w-max">



                        <thead>
                            <tr>

                                <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                    <b>
                                        <p class="text-sm font-semibold leading-none text-slate-500">
                                            Product Name
                                        </p>
                                    </b>
                                </th>

                                <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                    <p class="text-sm font-semibold leading-none text-slate-500">
                                        Opening Stock
                                    </p>
                                </th>

                                <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                    <p class="text-sm font-semibold leading-none text-slate-500">
                                        Stock In
                                    </p>
                                </th>
                                <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                    <p class="text-sm font-semibold leading-none text-slate-500">
                                        Stock out
                                    </p>
                                </th>
                                <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                    <p class="text-sm font-semibold leading-none text-slate-500">
                                        Closing Stock
                                    </p>
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

    <script>
        $(document).ready(function () {
            // --- Central Function to Fetch Data ---
            function fetchData(page = 1) {
                let date = $('#date').val();
                let perPage = $('#perPage').val();
                let search = $('#search').val();

                $.ajax({
                    url: "{{ route('report.stock.by_day') }}?page=" + page,
                    type: 'GET',
                    data: {
                        date: date,
                        perPage: perPage,
                        search: search
                    },
                    beforeSend: function () {
                        $('#report-table-body').html('<tr><td colspan="5" class="text-center p-6"><span class="text-gray-500">Loading...</span></td></tr>');
                        $('#pagination-links').empty();
                    },
                    success: function (response) {
                        $('#report-table-body').html(response.table);
                        $('#pagination-links').html(response.pagination);
                        // Update the title with the date from the response
                        $('#report-title-date').text(response.formattedDate);
                    },
                    error: function (xhr) {
                        $('#report-table-body').html('<tr><td colspan="5" class="text-center text-red-500 p-6">Failed to load data. Please try again.</td></tr>');
                    }
                });
            }

            // --- EVENT HANDLERS ---
            // Fetch data when date or perPage dropdown changes
            $('#date, #perPage').on('change', function () {
                fetchData(1); // Reset to page 1 on new filter
            });

            // Use a debounce for the search input to avoid too many requests
            let searchTimeout;
            $('#search').on('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    fetchData(1);
                }, 500); // 500ms delay before sending request
            });

            // Handle clicks on pagination links
            $(document).on('click', '#pagination-links .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

            // Load initial data for today's date when the page first loads
            fetchData();
        });
    </script>

@endsection