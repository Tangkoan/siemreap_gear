@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content py-8 px-4 dark:bg-gray-800 min-h-screen w-full">
    <div class="container mx-auto">

        {{-- Page Title --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
                Stock Movement Report
            </h1>
        </div>

        {{-- Tab Buttons --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="reportTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" role="tab" data-tab-target="#day-tab-content">By Day</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" role="tab" data-tab-target="#month-tab-content">By Month</button>
                </li>
                <li role="presentation">
                    <button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" role="tab" data-tab-target="#year-tab-content">By Year</button>
                </li>
            </ul>
        </div>

        {{-- Tab Content --}}
        {{-- Tab Content --}}
<div id="reportTabContent">
    {{-- #1. By Day Tab Content --}}
    <div class="tab-pane p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="day-tab-content" role="tabpanel">
        {{-- ✅ កូដដែលបានកែ --}}
        @include('admin.report.stock.partials._report_by_day')
    </div>

    {{-- #2. By Month Tab Content --}}
    <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="month-tab-content" role="tabpanel">
        {{-- ✅ កូដដែលបានកែ --}}
        @include('admin.report.stock.partials._report_by_month')
    </div>

    {{-- #3. By Year Tab Content --}}
    <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="year-tab-content" role="tabpanel">
         {{-- ✅ កូដដែលបានកែ --}}
         @include('admin.report.stock.partials._report_by_year')
    </div>
</div>
    </div>
</div>

{{-- Modal (Popup) - Shared for all tabs --}}
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Transaction Details</h3>
            <div class="mt-2 px-7 py-3">
                <div class="overflow-y-auto max-h-[60vh]">
                    <table class="min-w-full text-left">
                        <thead class="sticky top-0 bg-slate-50 dark:bg-gray-700">
                            <tr>
                                <th class="p-2">Date/Time</th>
                                <th class="p-2">Type</th>
                                <th class="p-2">Quantity</th>
                                <th class="p-2">Reference</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body" class="divide-y divide-gray-200 dark:divide-gray-600"></tbody>
                    </table>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const tabs = $('.tab-button');
        const panes = $('.tab-pane');
        let activeTab = 'day'; // Default active tab

        // Helper function to show loading state
        function showLoading(tableBody) {
            tableBody.html('<tr><td colspan="5" class="text-center p-6"><span>Loading...</span></td></tr>');
        }

        // Helper function to show error state
        function showError(tableBody) {
            tableBody.html('<tr><td colspan="5" class="text-center text-red-500 p-6">Failed to load data.</td></tr>');
        }

        // --- 1. LOGIC FOR "BY DAY" TAB ---
        function fetchDayData(page = 1) {
            let date = $('#date').val();
            let search = $('#search-day').val();
            showLoading($('#report-table-body-day'));
            $('#pagination-links-day').empty();

            $.ajax({
                url: `{{ route('report.stock.by_day') }}?page=${page}`,
                type: 'GET',
                data: { date: date, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-day').html(response.table);
                    $('#pagination-links-day').html(response.pagination);
                    $('#report-title-day').text(response.formattedDate);
                    updateExportLinkDay();
                },
                error: function() { showError($('#report-table-body-day')); }
            });
        }
        function updateExportLinkDay() {
            let url = new URL("{{ route('report.stock.export.day') }}");
            url.searchParams.set('date', $('#date').val());
            if ($('#search-day').val()) {
                url.searchParams.set('search', $('#search-day').val());
            }
            $('#exportBtn-day').attr('href', url.href);
        }
        
        // --- 2. LOGIC FOR "BY MONTH" TAB ---
        function fetchMonthData(page = 1) {
            let month = $('#month').val();
            let search = $('#search-month').val();
            showLoading($('#report-table-body-month'));
            $('#pagination-links-month').empty();

            $.ajax({
                url: `{{ route('report.stock.by_month') }}?page=${page}`,
                type: 'GET',
                data: { month: month, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-month').html(response.table);
                    $('#pagination-links-month').html(response.pagination);
                    $('#report-title-month').text(response.formattedDate);
                    updateExportLinkMonth();
                },
                error: function() { showError($('#report-table-body-month')); }
            });
        }
        function updateExportLinkMonth() {
            let url = new URL("{{ route('report.stock.export.month') }}");
            url.searchParams.set('month', $('#month').val());
            if ($('#search-month').val()) {
                url.searchParams.set('search', $('#search-month').val());
            }
            $('#exportBtn-month').attr('href', url.href);
        }

        // --- 3. LOGIC FOR "BY YEAR" TAB ---
        function fetchYearData(page = 1) {
            let year = $('#year').val();
            let search = $('#search-year').val();
            showLoading($('#report-table-body-year'));
            $('#pagination-links-year').empty();

            $.ajax({
                url: `{{ route('report.stock.by_year') }}?page=${page}`,
                type: 'GET',
                data: { year: year, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-year').html(response.table);
                    $('#pagination-links-year').html(response.pagination);
                    $('#report-title-year').text(response.formattedDate);
                    updateExportLinkYear();
                },
                error: function() { showError($('#report-table-body-year')); }
            });
        }
        function updateExportLinkYear() {
            let url = new URL("{{ route('report.stock.export.year') }}");
            url.searchParams.set('year', $('#year').val());
            if ($('#search-year').val()) {
                url.searchParams.set('search', $('#search-year').val());
            }
            $('#exportBtn-year').attr('href', url.href);
        }

        // --- TAB SWITCHING LOGIC ---
        tabs.on('click', function() {
            const target = $(this).data('tab-target');
            
            // Update button styles
            tabs.removeClass('border-blue-500 text-blue-600 dark:border-blue-500 dark:text-blue-500').addClass('border-transparent hover:text-gray-600 hover:border-gray-300');
            $(this).removeClass('hover:text-gray-600 hover:border-gray-300').addClass('border-blue-500 text-blue-600 dark:border-blue-500 dark:text-blue-500');

            // Show/hide panes
            panes.addClass('hidden');
            $(target).removeClass('hidden');

            activeTab = target.replace('#', '').replace('-tab-content', '');

            // Lazy load data for tabs that haven't been loaded yet
            if (!$(this).data('loaded')) {
                if (activeTab === 'month') fetchMonthData();
                else if (activeTab === 'year') fetchYearData();
                $(this).data('loaded', true);
            }
        });

        // Set the initial active tab
        tabs.first().trigger('click');
        fetchDayData(); // Initial data load for the default tab

        // --- EVENT HANDLERS FOR FILTERS (Applied to all tabs) ---
        let searchTimeout;
        $('#date, #search-day').on('change keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchDayData(1), 500); });
        $('#month, #search-month').on('change keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchMonthData(1), 500); });
        $('#year, #search-year').on('change keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchYearData(1), 500); });

        // --- DELEGATED EVENT HANDLERS FOR PAGINATION & MODAL ---
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            if (activeTab === 'day') fetchDayData(page);
            else if (activeTab === 'month') fetchMonthData(page);
            else if (activeTab === 'year') fetchYearData(page);
        });
        
        $(document).on('click', '.stock-row', function() {
            let productId = $(this).data('product-id');
            let productName = $(this).data('product-name');
            let ajaxUrl, data, title;

            // Prepare data based on the active tab
            if (activeTab === 'day') {
                ajaxUrl = "{{ route('report.stock.details.day') }}";
                data = { productId: productId, date: $('#date').val() };
                title = `Details for: ${productName} (${$('#report-title-day').text()})`;
            } else if (activeTab === 'month') {
                ajaxUrl = "{{ route('report.stock.details.month') }}";
                data = { productId: productId, month: $('#month').val() };
                title = `Details for: ${productName} (${$('#report-title-month').text()})`;
            } else { // year
                ajaxUrl = "{{ route('report.stock.details') }}";
                data = { productId: productId, year: $('#year').val() };
                title = `Details for: ${productName} (${$('#report-title-year').text()})`;
            }
            
            // Show modal and fetch details
            $('#modal-title').text(title);
            $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-4">Loading details...</td></tr>');
            $('#detailsModal').removeClass('hidden');

            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: data,
                success: function(transactions) {
                    let detailsHtml = '';
                    if (transactions.length > 0) {
                        transactions.forEach(function(trx) {
                            let dateStr = new Date(trx.transaction_date);
                            let formattedDate = (activeTab === 'day') ? dateStr.toLocaleTimeString('en-GB') : dateStr.toLocaleDateString('en-GB');
                            let quantityClass = trx.transaction_type === 'Stock In' ? 'text-green-600' : 'text-red-600';
                            let quantityPrefix = trx.transaction_type === 'Stock In' ? '+' : '-';
                            detailsHtml += `
                                <tr>
                                    <td class="p-2">${formattedDate}</td>
                                    <td class="p-2">${trx.transaction_type}</td>
                                    <td class="p-2 font-semibold ${quantityClass}">${quantityPrefix}${trx.quantity}</td>
                                    <td class="p-2">${trx.reference || 'N/A'}</td>
                                </tr>`;
                        });
                    } else {
                        detailsHtml = '<tr><td colspan="4" class="text-center p-4">No transactions found for this period.</td></tr>';
                    }
                    $('#modal-table-body').html(detailsHtml);
                },
                error: function() {
                    $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-4">Failed to load details.</td></tr>');
                }
            });
        });

        // Close Modal
        $('#closeModal').on('click', function() {
            $('#detailsModal').addClass('hidden');
        });
    });
</script>
@endsection