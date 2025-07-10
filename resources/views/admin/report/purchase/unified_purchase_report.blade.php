@extends('admin.admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="container mx-auto p-4 md:p-1">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    <span class="px-2">Purchase Report:</span>
                    <span id="report-title-date" class="px-2 text-blue-600 dark:text-blue-400"></span>
                </h2>
            </div>

            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="reportTabs">
                    <li class="me-2"><button class="report-tab inline-block p-4 border-b-2 rounded-t-lg" data-type="daily">Daily</button></li>
                    <li class="me-2"><button class="report-tab inline-block p-4 border-b-2 rounded-t-lg" data-type="monthly">Monthly</button></li>
                    <li class="me-2"><button class="report-tab inline-block p-4 border-b-2 rounded-t-lg" data-type="yearly">Yearly</button></li>
                </ul>
            </div>

            <div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
                <div class="flex items-end gap-4">
                    <div id="filter-inputs">
                        <input type="date" id="date_filter" class="filter-input h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm">
                        <input type="month" id="month_filter" class="filter-input h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm hidden">
                        <input type="number" id="year_filter" class="filter-input h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm hidden" placeholder="Enter Year" min="2000">
                    </div>
                    <input class="h-10 dark:text-white dark:bg-gray-800 bg-white w-full pr-11 pl-3 placeholder:text-slate-400 border border-slate-200 rounded" placeholder="Search Invoice or Supplier" id="search" type="text" />
                </div>
                <div>
                    <a id="exportBtn" href="#" class="h-10 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Export
                    </a>
                </div>
            </div>

            <div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="table-wrapper overflow-y-auto max-h-[70vh]">
                    <table class="w-full text-left table-auto min-w-max">
                        <thead class="sticky top-0 bg-slate-50 dark:bg-gray-900">
                            <tr>
                                <th class="p-4">No.</th><th class="p-4">Date</th><th class="p-4">Invoice</th><th class="p-4">Supplier</th><th class="p-4">Amount</th><th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="report-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm"></tbody>
                        <tfoot id="report-table-footer" class="bg-slate-50 dark:bg-gray-900 font-bold"></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.report.purchase.modal.purchase_details_modal')

    <script>
        $(document).ready(function () {
            // --- State and Constants ---
            let currentReportType = 'daily'; // Default view is 'daily'
            let searchTimeout;
            const today = new Date().toISOString().slice(0, 10);
            const todayMonth = today.slice(0, 7);
            const todayYear = today.slice(0, 4);
    
            // --- Main Function to Fetch Data ---
            function fetchData() {
                let data = { search: $('#search').val() };
                let url = '';
    
                switch (currentReportType) {
                    case 'monthly':
                        data.month = $('#month_filter').val();
                        url = `{{ route('report.purchases.by_month') }}`;
                        break;
                    case 'yearly':
                        data.year = $('#year_filter').val();
                        url = `{{ route('report.purchases.by_year') }}`;
                        break;
                    case 'daily':
                    default:
                        data.date = $('#date_filter').val();
                        url = `{{ route('report.purchases.by_date') }}`;
                        break;
                }
    
                $.ajax({
                    url: url, type: 'GET', data: data,
                    beforeSend: function () {
                        $('#report-table-body').html('<tr><td colspan="6" class="text-center p-6">Loading...</td></tr>');
                        $('#report-table-footer').html('');
                    },
                    success: function (response) {
                        $('#report-table-body').html(response.table);
                        $('#report-table-footer').html(response.footer);
                        $('#report-title-date').text(response.formattedDate);
                        updateExportLink();
                    },
                    error: function () { $('#report-table-body').html('<tr><td colspan="6" class="text-center text-red-500 p-6">Failed to load data.</td></tr>');}
                });
            }
    
            // --- Function to Update Export Link ---
            function updateExportLink() {
                let params = new URLSearchParams();
                let baseUrl = '';
                params.set('search', $('#search').val() || '');
    
                switch (currentReportType) {
                    case 'monthly':
                        params.set('month', $('#month_filter').val());
                        baseUrl = "{{ route('report.purchases.export.month') }}";
                        break;
                    case 'yearly':
                        params.set('year', $('#year_filter').val());
                        baseUrl = "{{ route('report.purchases.export.year') }}";
                        break;
                    default:
                        params.set('date', $('#date_filter').val());
                        baseUrl = "{{ route('report.purchases.export.date') }}";
                        break;
                }
                $('#exportBtn').attr('href', `${baseUrl}?${params.toString()}`);
            }
    
            // --- Function to Switch Tabs and Filters ---
            function switchTab(type) {
                currentReportType = type;
                
                // Update tab styles
                $('.report-tab').removeClass('text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500').addClass('border-transparent hover:text-gray-600 hover:border-gray-300');
                $(`.report-tab[data-type="${type}"]`).addClass('text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500').removeClass('border-transparent');
                
                // Show the correct filter input
                $('.filter-input').addClass('hidden');
                $(`#${type}_filter`).removeClass('hidden');
                
                // Fetch data for the new view
                fetchData();
            }
    
            // --- Event Handlers ---
            $('.report-tab').on('click', function() {
                switchTab($(this).data('type'));
            });
    
            $('.filter-input').on('change', function() {
                fetchData();
            });
    
            $('#search').on('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fetchData(), 500);
            });
            
            // --- Modal Script ---
            @include('admin.report.purchase.modal.purchase_details_modal_script')
    
            // --- Initial Page Load ---
            function initializePage() {
                // Set default values for all filters to today's date/month/year
                $('#date_filter').val(today);
                $('#month_filter').val(todayMonth);
                $('#year_filter').val(todayYear);
    
                // Activate the 'daily' tab and fetch its data by default
                switchTab('daily');
            }
    
            initializePage();
        });
    </script>
@endsection