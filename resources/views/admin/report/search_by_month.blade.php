@extends('admin.admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-full p-0">
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold text-default flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        <span class="px-2">Order in Month:</span> <span class="px-2 text-black dark:text-blue-500"> {{ $monthName }} / {{ $year }}</span>
                    </h2>
                    <div>

                        <button type="button"
                            class=" button-add  py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent    focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">

                            <a href="{{ route('sale.report.export') }}">Export</a>
                        </button>


                    </div>
                    </div>

                    <!-- Search + Per Page -->
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-slate-600 dark:text-white">Show</label>
                                <select id="perPage" name="perPage"
                                    class="h-10 border dark:bg-gray-800  dark:text-white border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                    <option value="6">6</option>
                                    <option value="10">10</option> <!-- ✅ Default -->
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all" selected>All</option>
                                </select>
                            </div>
                        </div>

                        <div id="orderTableWrapper">
                            <table class="w-full text-left table-auto min-w-max text-sm">
                            </table>
                            <div id="orderTableFooter"></div> {{-- <<< នៅក្រោម Table --}} </div>

                                <div class="ml-3">
                                    <div class="w-full max-w-sm min-w-[200px] relative">
                                        <div class="relative w-full max-w-sm min-w-[200px]">
                                            <input
                                                class="dark:text-white dark:bg-gray-800 bg-white w-full pr-11 h-10 pl-3 py-2 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                                placeholder="Search for invoice no. or customer name" id="search" name="search" type="text" />
                                            <button class="dark:bg-gray-800 absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
                                                type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
                                                    class="w-8 h-8 text-slate-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                </svg>
                                            </button>              </div>
                                    </div>
                                </div>
                        </div>
                <!-- Table -->
                <div class="table-wrapper overflow-y-auto max-h-[500px]">
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
                        <tfoot id="orderTableFooter" class="bg-slate-50 dark:bg-gray-800">
                            {{-- Ajax footer data will appear here --}}
                        </tfoot>
                    </table>

                    <div id="paginationLinks" class="mt-4"></div>          </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function fetchData(page = 1) {
                let query = $('#search').val();
                let perPage = $('#perPage').val();
                let month = "{{ $monthName }}";
                let year = "{{ $year }}";

                $.ajax({
                    url: "{{ url('/report/bymonth') }}",
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