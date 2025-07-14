@extends('admin.admin_dashboard') {{-- Your main admin layout --}}
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content py-8 px-4 dark:bg-gray-900 min-h-screen w-full text-gray-900 dark:text-gray-100">
    <div class="container mx-auto">

        {{-- Page Title --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-8">
            <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white flex items-center gap-3">
                

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                Stock Movement Report
            </h1>
        </div>

        {{-- Tab Buttons --}}
       {{-- ✨ NEW 2025 UI/UX for Tabs --}}
    <div class="mb-6">
        <div class="inline-block bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <ul class="flex items-center space-x-1" id="reportTab" role="tablist">
                
                {{-- Tab Button: By Day --}}
                <li role="presentation">
                    <button class="tab-button text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-300" 
                            type="button" 
                            role="tab" 
                            data-tab-target="#day-tab-content">
                        By Day
                    </button>
                </li>

                {{-- Tab Button: By Month --}}
                <li role="presentation">
                    <button class="tab-button text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-300" 
                            type="button" 
                            role="tab" 
                            data-tab-target="#month-tab-content">
                        By Month
                    </button>
                </li>

                {{-- Tab Button: By Year --}}
                <li role="presentation">
                    <button class="tab-button text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-300" 
                            type="button" 
                            role="tab" 
                            data-tab-target="#year-tab-content">
                        By Year
                    </button>
                </li>

            </ul>
        </div>
    </div>

        

        

        {{-- Tab Content Containers --}}
        <div id="reportTabContent">
            {{-- #1. By Day Tab Content --}}
            <div class="tab-pane p-6 rounded-lg shadow-lg bg-white dark:bg-gray-850" id="day-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_day')
            </div>

            {{-- #2. By Month Tab Content --}}
            <div class="tab-pane hidden p-6 rounded-lg shadow-lg bg-white dark:bg-gray-850" id="month-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_month')
            </div>

            {{-- #3. By Year Tab Content --}}
            <div class="tab-pane hidden p-6 rounded-lg shadow-lg bg-white dark:bg-gray-850" id="year-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_year')
            </div>
        </div>
    </div>
</div>

{{-- Modal (Popup) - Shared for all tabs --}}
<div id="detailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-1 border border-gray-700 rounded-lg w-full max-w-3xl shadow-2xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 scale-95 transition-transform duration-300">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4 px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                   <h3 class="text-2xl font-semibold" id="modal-title">Transaction Details</h3>
                   <button id="closeModal" class="text-gray-400 hover:text-gray-700 dark:hover:text-white transition p-2 rounded-full -mr-2 text-2xl font-bold">&times;</button>
            </div>
            
            <div class="px-5 py-3">
                <div class="overflow-y-auto max-h-[60vh] border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full text-left">
                        <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="p-3">Date/Time</th>
                                <th class="p-3">Type</th>
                                <th class="p-3 text-right">Quantity</th>
                                <th class="p-3">Reference</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            {{-- Details will be loaded here by AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="items-center px-5 py-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                <button id="closeModalBtn" class="px-6 py-2 bg-red-600 dark:bg-red-700 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 dark:hover:bg-red-800 transition duration-150 ease-in-out">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).ready(function() {
        const tabs = $('.tab-button');
        const panes = $('.tab-pane');
        let activeTab = 'day'; // Default active tab
        let searchTimeout;

        // Helper function to show loading state in table
        function showLoading(tableBodyId) {
            $(`#${tableBodyId}`).html('<tr><td colspan="5" class="text-center p-6 text-gray-500 dark:text-gray-400"><div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-red-500 rounded-full" role="status" aria-label="loading"></div> <span class="ml-2">Loading data...</span></td></tr>');
        }

        // Helper function to show error state in table
        function showError(tableBodyId) {
            $(`#${tableBodyId}`).html('<tr><td colspan="5" class="text-center text-red-500 p-6">Failed to load data. Please try again.</td></tr>');
        }

        // --- Data Fetching Functions ---
        function fetchDayData(page = 1) {
            let date = $('#date').val();
            let search = $('#search-day').val();
            showLoading('report-table-body-day');
            $('#pagination-links-day').empty();
            $('#total-stock-in-day, #total-stock-out-day').text('...'); // Show loading for cards

            $.ajax({
                url: `{{ route('report.stock.by_day') }}?page=${page}`,
                type: 'GET',
                data: { date: date, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-day').html(response.table);
                    $('#pagination-links-day').html(response.pagination);
                    $('#report-title-day').text(response.formattedDate);
                    $('#total-stock-in-day').text(response.totalStockIn); // Update total stock in card
                    $('#total-stock-out-day').text(response.totalStockOut); // Update total stock out card
                    updateExportLinkDay();
                },
                error: function() { 
                    showError('report-table-body-day');
                    $('#total-stock-in-day, #total-stock-out-day').text('N/A');
                }
            });
        }

        function fetchMonthData(page = 1) {
            let month = $('#month').val();
            let search = $('#search-month').val();
            showLoading('report-table-body-month');
            $('#pagination-links-month').empty();
            $('#total-stock-in-month, #total-stock-out-month').text('...'); // Show loading for cards

            $.ajax({
                url: `{{ route('report.stock.by_month') }}?page=${page}`,
                type: 'GET',
                data: { month: month, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-month').html(response.table);
                    $('#pagination-links-month').html(response.pagination);
                    $('#report-title-month').text(response.formattedDate);
                    $('#total-stock-in-month').text(response.totalStockIn); // Update total stock in card
                    $('#total-stock-out-month').text(response.totalStockOut); // Update total stock out card
                    updateExportLinkMonth();
                },
                error: function() { 
                    showError('report-table-body-month'); 
                    $('#total-stock-in-month, #total-stock-out-month').text('N/A');
                }
            });
        }

        function fetchYearData(page = 1) {
            let year = $('#year').val();
            let search = $('#search-year').val();
            showLoading('report-table-body-year');
            $('#pagination-links-year').empty();
            $('#total-stock-in-year, #total-stock-out-year').text('...'); // Show loading for cards

            $.ajax({
                url: `{{ route('report.stock.by_year') }}?page=${page}`,
                type: 'GET',
                data: { year: year, search: search, perPage: 15 },
                success: function(response) {
                    $('#report-table-body-year').html(response.table);
                    $('#pagination-links-year').html(response.pagination);
                    $('#report-title-year').text(response.formattedDate);
                    $('#total-stock-in-year').text(response.totalStockIn); // Update total stock in card
                    $('#total-stock-out-year').text(response.totalStockOut); // Update total stock out card
                    updateExportLinkYear();
                },
                error: function() { 
                    showError('report-table-body-year'); 
                    $('#total-stock-in-year, #total-stock-out-year').text('N/A');
                }
            });
        }

        // --- Export Link Updaters ---
        function updateExportLinkDay() {
            let url = new URL("{{ route('report.stock.export.day') }}");
            url.searchParams.set('date', $('#date').val());
            if ($('#search-day').val()) {
                url.searchParams.set('search', $('#search-day').val());
            }
            $('#exportBtn-day').attr('href', url.href);
        }

        function updateExportLinkMonth() {
            let url = new URL("{{ route('report.stock.export.month') }}");
            url.searchParams.set('month', $('#month').val());
            if ($('#search-month').val()) {
                url.searchParams.set('search', $('#search-month').val());
            }
            $('#exportBtn-month').attr('href', url.href);
        }

        function updateExportLinkYear() {
            let url = new URL("{{ route('report.stock.export.year') }}");
            url.searchParams.set('year', $('#year').val());
            if ($('#search-year').val()) {
                url.searchParams.set('search', $('#search-year').val());
            }
            $('#exportBtn-year').attr('href', url.href);
        }

        // --- Tab Switching Logic ---
        tabs.on('click', function() {
            const target = $(this).data('tab-target');
            
            // Update button styles
            tabs.removeClass('border-red-500 text-red-600 dark:border-red-500 dark:text-red-500')
                .addClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600');
            $(this).removeClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600')
                .addClass('border-red-500 text-red-600 dark:border-red-500 dark:text-red-500');

            // Show/hide panes
            panes.addClass('hidden');
            $(target).removeClass('hidden');

            activeTab = target.replace('#', '').replace('-tab-content', '');

            // Lazy load data for tabs that haven't been loaded yet
            // Ensure initial load for each tab only once on first click
            if (!$(this).data('loaded')) {
                if (activeTab === 'month') fetchMonthData();
                else if (activeTab === 'year') fetchYearData();
                $(this).data('loaded', true);
            }
        });

        // Set the initial active tab and load its data
        tabs.first().addClass('border-red-500 text-red-600 dark:border-red-500 dark:text-red-500').data('loaded', true);
        fetchDayData(); // Initial data load for the default tab

        // --- Event Handlers for Filters (Applied to all tabs) ---
        $('#date').on('change', function() { fetchDayData(1); });
        $('#search-day').on('keyup', function() { 
            clearTimeout(searchTimeout); 
            searchTimeout = setTimeout(() => fetchDayData(1), 500); 
        });

        $('#month').on('change', function() { fetchMonthData(1); });
        $('#search-month').on('keyup', function() { 
            clearTimeout(searchTimeout); 
            searchTimeout = setTimeout(() => fetchMonthData(1), 500); 
        });

        $('#year').on('change', function() { fetchYearData(1); });
        $('#search-year').on('keyup', function() { 
            clearTimeout(searchTimeout); 
            searchTimeout = setTimeout(() => fetchYearData(1), 500); 
        });

        // --- Delegated Event Handlers for Pagination & Modal ---
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
    let activeTabForModal = $(this).data('active-tab'); // Get the active tab from data-attribute
    let ajaxUrl, data, title;

    // Prepare data based on the active tab
    if (activeTabForModal === 'day') {
        ajaxUrl = "{{ route('report.stock.details.day') }}";
        data = { productId: productId, date: $('#date').val() };
        title = `Details for: ${productName} (${$('#report-title-day').text()})`;
    } else if (activeTabForModal === 'month') {
        // Correctly target the month-specific details route and pass the month value
        ajaxUrl = "{{ route('report.stock.details.month') }}";
        data = { productId: productId, month: $('#month').val() };
        title = `Details for: ${productName} (${$('#report-title-month').text()})`;
    } else if (activeTabForModal === 'year') {
        // Correctly target the year-specific details route and pass the year value
        ajaxUrl = "{{ route('report.stock.details.year') }}"; // This is the route name you provided for year
        data = { productId: productId, year: $('#year').val() };
        title = `Details for: ${productName} (${$('#report-title-year').text()})`;
    }
    
    // Show modal and fetch details
    $('#modal-title').text(title);
    $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-4 text-gray-500 dark:text-gray-400">Loading details...</td></tr>');
    
    // Add transition classes for modal appearance
    $('#detailsModal').removeClass('hidden').css({opacity: 0});
    setTimeout(() => {
        $('#detailsModal').css({opacity: 1});
        $('#detailsModal > div').removeClass('scale-95').addClass('scale-100');
    }, 10); // Small delay for transition to kick in

    $.ajax({
        url: ajaxUrl,
        type: 'GET',
        data: data,
        success: function(transactions) {
            let detailsHtml = '';
            if (transactions.length > 0) {
                transactions.forEach(function(trx) {
                    let dateObj = new Date(trx.transaction_date);
                    let formattedDate;

                    // Use specific formatting based on the tab that triggered the modal
                    if (activeTabForModal === 'day') {
                        formattedDate = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + dateObj.toLocaleTimeString('en-GB');
                    } else {
                        // For month and year, we can keep the full date for clarity in details,
                        // or adjust if you only want month/year in the modal itself.
                        formattedDate = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                    }
                    
                    let quantityClass = trx.transaction_type === 'Stock In' ? 'text-green-600' : 'text-red-600';
                    let quantityPrefix = trx.transaction_type === 'Stock In' ? '+' : '-';
                    detailsHtml += `
                        <tr>
                            <td class="p-3 whitespace-nowrap">${formattedDate}</td>
                            <td class="p-3">${trx.transaction_type}</td>
                            <td class="p-3 text-right font-semibold ${quantityClass}">${quantityPrefix}${trx.quantity}</td>
                            <td class="p-3">${htmlspecialchars(trx.reference || 'N/A')}</td>
                        </tr>`;
                });
            } else {
                detailsHtml = '<tr><td colspan="4" class="text-center p-4 text-gray-500 dark:text-gray-400">No transactions found for this product in the selected period.</td></tr>';
            }
            $('#modal-table-body').html(detailsHtml);
        },
        error: function() {
            $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-4">Failed to load details. Please check the console for more information.</td></tr>');
            console.error("AJAX error fetching stock movement details.");
        }
    });
});
        // Close Modal
        $('#closeModal, #closeModalBtn').on('click', function() {
            $('#detailsModal').css({opacity: 0});
            $('#detailsModal > div').removeClass('scale-100').addClass('scale-95');
            setTimeout(() => {
                $('#detailsModal').addClass('hidden');
            }, 300); // Wait for transition to finish
        });

        // Function to escape HTML entities for safer output
        function htmlspecialchars(str) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return str.replace(/[&<>"']/g, function(m) { return map[m]; });
        }





        // --- Tab Switching Logic ---
        const activeTabClasses = 'bg-red-600 text-white shadow-md';
        const inactiveTabClasses = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700';

        tabs.on('click', function() {
            const target = $(this).data('tab-target');
            
            // Update button styles with the new pill-style classes
            tabs.removeClass(activeTabClasses).addClass(inactiveTabClasses);
            $(this).removeClass(inactiveTabClasses).addClass(activeTabClasses);

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

        // Set the initial active tab on page load
        tabs.first().trigger('click');
    });
</script>
@endsection