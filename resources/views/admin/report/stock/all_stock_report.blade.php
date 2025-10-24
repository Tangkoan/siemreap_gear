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
                {{ __('messages.stock_movement_report') }}
            </h1>
        </div>

        {{-- Tab Buttons --}}
        <div class="mb-6">
            <div class="inline-block bg-white dark:bg-slate-800 p-1.5 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <ul class="flex items-center space-x-1" id="reportTab" role="tablist">
                    <li role="presentation">
                        <button class="tab-button text-sm  px-6 py-2.5 rounded-lg transition-colors duration-300" 
                                type="button" role="tab" data-tab-target="#day-tab-content">
                            {{ __('messages.by_day') }}
                        </button>
                    </li>
                    <li role="presentation">
                        <button class="tab-button text-sm  px-6 py-2.5 rounded-lg transition-colors duration-300" 
                                type="button" role="tab" data-tab-target="#month-tab-content">
                           {{ __('messages.by_month') }}
                        </button>
                    </li>
                    <li role="presentation">
                        <button class="tab-button text-sm  px-6 py-2.5 rounded-lg transition-colors duration-300" 
                                type="button" role="tab" data-tab-target="#year-tab-content">
                            {{ __('messages.by_year') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Tab Content Containers --}}
        <div id="reportTabContent">
            {{-- #1. By Day Tab Content --}}
            <div class="tab-pane p-6 rounded-lg shadow-lg bg-white dark:bg-gray-900" id="day-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_day')
            </div>

            {{-- #2. By Month Tab Content --}}
            <div class="tab-pane hidden p-6 rounded-lg shadow-lg bg-white dark:bg-gray-900" id="month-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_month')
            </div>

            {{-- #3. By Year Tab Content --}}
            <div class="tab-pane hidden p-6 rounded-lg shadow-lg bg-white dark:bg-gray-900" id="year-tab-content" role="tabpanel">
                @include('admin.report.stock.partials._report_by_year')
            </div>
        </div>
    </div>
</div>

{{-- Modal (Popup) - Shared for all tabs --}}
<div id="detailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-1 border border-gray-700 rounded-lg w-full max-w-3xl shadow-2xl bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 scale-95 transition-transform duration-300">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4 px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl " id="modal-title">Transaction Details</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-700 dark:hover:text-white transition p-2 rounded-full -mr-2 text-2xl font-bold">&times;</button>
            </div>
            
            <div class="px-5 py-3">
                <div class="overflow-y-auto max-h-[60vh] border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full text-left">
                        <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="p-3">Date</th>
                                <th class="p-3">Type Of Stock</th>
                                <th class="p-3 text-right">QTY</th>
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
                <button id="closeModalBtn" class="px-6 py-2 bg-red-600 dark:bg-red-700 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 dark:hover:bg-red-800 transition duration-150 ease-in-out">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>


{{-- ✅ ==================== START: JAVASCRIPT ដែលបានកែសម្រួល ==================== --}}
<script>
$(document).ready(function() {
    const tabs = $('.tab-button');
    const panes = $('.tab-pane');
    const activeTabClasses = 'bg-red-600 text-white shadow-md';
    const inactiveTabClasses = 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700';
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
        $('#total-stock-in-day, #total-stock-out-day').text('...');

        $.ajax({
            url: `{{ route('report.stock.by_day') }}?page=${page}`,
            type: 'GET',
            data: { date: date, search: search, perPage: 100 },
            success: function(response) {
                $('#report-table-body-day').html(response.table);
                $('#pagination-links-day').html(response.pagination);
                $('#report-title-day').text(response.formattedDate);
                $('#total-stock-in-day').text(response.totalStockIn);
                $('#total-stock-out-day').text(response.totalStockOut);
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
        $('#total-stock-in-month, #total-stock-out-month').text('...');

        $.ajax({
            url: `{{ route('report.stock.by_month') }}?page=${page}`,
            type: 'GET',
            data: { month: month, search: search, perPage: 100 },
            success: function(response) {
                $('#report-table-body-month').html(response.table);
                $('#pagination-links-month').html(response.pagination);
                $('#report-title-month').text(response.formattedDate);
                $('#total-stock-in-month').text(response.totalStockIn);
                $('#total-stock-out-month').text(response.totalStockOut);
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
        $('#total-stock-in-year, #total-stock-out-year').text('...');

        $.ajax({
            url: `{{ route('report.stock.by_year') }}?page=${page}`,
            type: 'GET',
            data: { year: year, search: search, perPage: 100 },
            success: function(response) {
                $('#report-table-body-year').html(response.table);
                $('#pagination-links-year').html(response.pagination);
                $('#report-title-year').text(response.formattedDate);
                $('#total-stock-in-year').text(response.totalStockIn);
                $('#total-stock-out-year').text(response.totalStockOut);
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
        
        tabs.removeClass(activeTabClasses).addClass(inactiveTabClasses);
        $(this).removeClass(inactiveTabClasses).addClass(activeTabClasses);

        panes.addClass('hidden');
        $(target).removeClass('hidden');

        activeTab = target.replace('#', '').replace('-tab-content', '');

        if (!$(this).data('loaded')) {
            if (activeTab === 'month') fetchMonthData();
            else if (activeTab === 'year') fetchYearData();
            $(this).data('loaded', true);
        }
    });

    // --- Event Handlers for Filters ---
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

    // --- Delegated Event Handlers for Pagination ---
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        if (activeTab === 'day') fetchDayData(page);
        else if (activeTab === 'month') fetchMonthData(page);
        else if (activeTab === 'year') fetchYearData(page);
    });
    
    // --- CORRECTED MODAL TRIGGER LOGIC ---
    $(document).on('click', '.stock-row', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const tabType = $(this).data('active-tab'); // 'day', 'month', or 'year'

        let data = {
            productId: productId,
            type: tabType,
            _token: "{{ csrf_token() }}"
        };
        let title;

        // Prepare data and title based on the active tab
        if (tabType === 'day') {
            data.value = $('#date').val();
            title = `Details for: ${productName} (${$('#report-title-day').text()})`;
        } else if (tabType === 'month') {
            data.value = $('#month').val();
            title = `Details for: ${productName} (${$('#report-title-month').text()})`;
        } else if (tabType === 'year') {
            data.value = $('#year').val();
            title = `Details for: ${productName} (${$('#report-title-year').text()})`;
        }

        // Show modal and set initial state
        $('#modal-title').text(title);
        $('#modal-table-body').html('<tr><td colspan="4" class="text-center p-4 text-gray-500 dark:text-gray-400">Loading details...</td></tr>');
        
        const modal = $('#detailsModal');
        modal.removeClass('hidden').css({opacity: 0, scrollTop:0});
        setTimeout(() => {
            modal.css({opacity: 1});
            modal.find('> div').removeClass('scale-95').addClass('scale-100');
        }, 10);

        // Fetch details using the single, unified route
        $.ajax({
            url: "{{ route('report.stock.details') }}", // Use the single, unified route
            type: 'GET',
            data: data,
            success: function(transactions) {
                let detailsHtml = '';
                if (transactions.length > 0) {
                    transactions.forEach(function(trx) {
                        let dateObj = new Date(trx.transaction_date);
                        let formattedDate = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        
                        // ✅✅✅ START: កូដដែលបានកែតម្រូវ ✅✅✅
                        // ប្រើ trx.movement_type ជំនួស trx.transaction_type ដើម្បីកំណត់ពណ៌
                        let quantityClass = trx.movement_type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                        let quantityPrefix = trx.movement_type === 'in' ? '+' : '-';
                        // ✅✅✅ END: កូដដែលបានកែតម្រូវ ✅✅✅
                        
                        detailsHtml += `
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                <td class="p-3 whitespace-nowrap">${formattedDate}</td>
                                <td class="p-3">${htmlspecialchars(trx.transaction_type)}</td>
                                <td class="p-3 text-right font-semibold ${quantityClass}">${quantityPrefix}${trx.quantity}</td>
                                <td class="p-3">${htmlspecialchars(trx.reference || 'N/A')}</td>
                            </tr>`;
                    });
                } else {
                    detailsHtml = '<tr><td colspan="4" class="text-center p-4 text-gray-500 dark:text-gray-400">No transactions found for this product in the selected period.</td></tr>';
                }
                $('#modal-table-body').html(detailsHtml);
            },
            error: function(jqXHR) {
                $('#modal-table-body').html('<tr><td colspan="4" class="text-center text-red-500 p-4">Failed to load details. Please check the route and controller.</td></tr>');
                console.error("AJAX Error:", jqXHR.status, jqXHR.responseText);
            }
        });
    });

    // --- Close Modal Logic ---
    $('#closeModal, #closeModalBtn').on('click', function() {
        const modal = $('#detailsModal');
        modal.css({opacity: 0});
        modal.find('> div').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => {
            modal.addClass('hidden');
        }, 300); // Wait for transition to finish
    });

    // --- Initial page load ---
    tabs.first().trigger('click'); // Set the initial active tab
    fetchDayData(); // Initial data load for the default tab

    // Helper function to escape HTML
    function htmlspecialchars(str) {
        if (typeof str !== 'string') return '';
        var map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return str.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>
{{-- ❌ ==================== END: JAVASCRIPT ដែលបានកែសម្រួល ====================== --}}
@endsection