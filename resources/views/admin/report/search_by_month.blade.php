@extends('admin.admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-full p-0">
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold text-default flex items-center">
                        📆 Orders in Month: {{ $month }} / {{ $year }}
                    </h2>
                    <div>
                        <button type="button"
                            class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                            <a href="{{ route('sale.report.export') }}">Export</a>
                        </button>
                    </div>
                </div>

                <!-- Search + Per Page -->
                <div class="flex flex-wrap justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <label for="perPage" class="text-sm text-slate-600 dark:text-slate-300">Show:</label>
                        <select id="perPage"
                            class="w-28 border border-slate-300 rounded text-sm text-slate-700 h-9 dark:bg-gray-600 dark:text-white">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                    </div>

                    <div id="orderTableWrapper" class="w-full lg:w-auto">
                        <table class="w-full text-left table-auto min-w-max text-sm">
                        </table>
                        <div id="orderTableFooter"></div>
                    </div>

                    <div class="relative w-full sm:w-64 mt-2 sm:mt-0">
                        <input type="text" id="search" placeholder="Search by Invoice / Name"
                            class="w-full h-9 px-3 border border-slate-300 rounded text-sm dark:bg-gray-600 dark:text-white">
                        <button type="button"
                            class="absolute top-1 right-1 h-7 w-7 flex items-center justify-center rounded text-slate-600 dark:text-white">
                            🔍
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="table-wrapper overflow-y-auto max-h-[500px] bg-white dark:bg-gray-700 shadow-xl rounded-2xl p-4">
                    <table class="w-full text-left table-auto min-w-max text-sm">
                        <thead>
                            <tr class="sticky top-0 bg-slate-50 dark:bg-gray-800 border-b border-slate-200">
                                <th class="p-4">N<sup>O</sup></th>
                                <th class="p-4">Date</th>
                                <th class="p-4">Invoice</th>
                                <th class="p-4">Amount</th>
                                <th class="p-4">Payment</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Action</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody" class="divide-y divide-slate-200 dark:divide-gray-600">
                            {{-- Ajax data will appear here --}}
                        </tbody>
                    </table>

                    <div id="paginationLinks" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function fetchData(page = 1) {
                let query = $('#search').val();
                let perPage = $('#perPage').val();
                let month = "{{ $month }}";
                let year = "{{ $year }}";

                $.ajax({
                    url: "{{ url('/report/bymonth') }}?page=" + page,
                    type: "GET",
                    data: {
                        search: query,
                        perPage: perPage,
                        month: month,
                        year_name: year,
                    },
                    beforeSend: function () {
                        $('#orderTableBody').html('<tr><td colspan="7" class="text-center py-4">Loading...</td></tr>');
                        $('#paginationLinks').html('');
                    },
                    success: function (data) {
                        $('#orderTableBody').html(data.table);
                        $('#orderTableFooter').html(data.footer);
                        $('#paginationLinks').html(data.pagination);
                    },
                    error: function () {
                        $('#orderTableBody').html('<tr><td colspan="7" class="text-center text-red-500 py-4">Error loading data.</td></tr>');
                    }
                });
            }

            fetchData();

            $('#search, #perPage').on('keyup change', function () {
                fetchData();
            });

            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });
        });
    </script>

@endsection