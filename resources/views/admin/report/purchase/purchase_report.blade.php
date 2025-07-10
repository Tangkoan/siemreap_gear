@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content py-8 px-4 dark:bg-gray-800 min-h-screen w-full">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            Purchases Report
        </h1>
        
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2"><button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" data-tab-target="#day-tab-content">By Day</button></li>
                <li class="mr-2"><button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" data-tab-target="#month-tab-content">By Month</button></li>
                <li><button class="tab-button inline-block p-4 border-b-2 rounded-t-lg" type="button" data-tab-target="#year-tab-content">By Year</button></li>
            </ul>
        </div>

        <div>
            <div class="tab-pane p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="day-tab-content"> @include('admin.report.purchase.partials._purchase_by_day') </div>
            <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="month-tab-content"> @include('admin.report.purchase.partials._purchase_by_month') </div>
            <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="year-tab-content"> @include('admin.report.purchase.partials._purchase_by_year') </div>
        </div>
    </div>
</div>

@include('admin.report.purchase.partials._purchase_details_modal')

<script>
$(document).ready(function() {
    const tabs = $('.tab-button');
    const panes = $('.tab-pane');
    let activeTab = 'day';
    let searchTimeout;

    // ✅ FUNCTION TO UPDATE EXPORT LINK
    function updateExportLink() {
        let params = new URLSearchParams();
        let baseUrl = '';
        
        switch (activeTab) {
            case 'month':
                params.set('month', $('#month-month').val());
                params.set('search', $('#search-purchase-month').val() || '');
                baseUrl = "{{ route('report.purchases.export.month') }}";
                break;
            case 'year':
                params.set('year', $('#year-year').val());
                params.set('search', $('#search-purchase-year').val() || '');
                baseUrl = "{{ route('report.purchases.export.year') }}";
                break;
            default: // day
                params.set('date', $('#date-day').val());
                params.set('search', $('#search-purchase-day').val() || '');
                baseUrl = "{{ route('report.purchases.export.date') }}";
                break;
        }
        // Update all export buttons at once
        $('.export-btn').attr('href', `${baseUrl}?${params.toString()}`);
    }

    function showLoading(tableBody, tableFooter) {
        tableBody.html('<tr><td colspan="7" class="text-center p-6"><span>Loading...</span></td></tr>');
        if (tableFooter) tableFooter.html('<tr><td colspan="7"></td></tr>');
    }

    function showError(tableBody) {
        tableBody.html('<tr><td colspan="7" class="text-center text-red-500 p-6">Failed to load data.</td></tr>');
    }

    function fetchDayData() {
        $.ajax({
            url: `{{ route('report.purchases.by_date') }}`, data: { date: $('#date-day').val(), search: $('#search-purchase-day').val() },
            beforeSend: () => showLoading($('#report-table-body-day'), $('#report-table-footer-day')),
            success: function(data) {
                $('#report-table-body-day').html(data.table);
                $('#report-table-footer-day').html(data.footer);
                $('#report-title-day').text(data.formattedDate);
                updateExportLink(); // ✅ UPDATE LINK ON SUCCESS
            },
            error: () => showError($('#report-table-body-day'))
        });
    }

    function fetchMonthData() {
        $.ajax({
            url: `{{ route('report.purchases.by_month') }}`, data: { month: $('#month-month').val(), search: $('#search-purchase-month').val() },
            beforeSend: () => showLoading($('#report-table-body-month'), $('#report-table-footer-month')),
            success: function(data) {
                $('#report-table-body-month').html(data.table);
                $('#report-table-footer-month').html(data.footer);
                $('#report-title-month').text(data.formattedDate);
                updateExportLink(); // ✅ UPDATE LINK ON SUCCESS
            },
            error: () => showError($('#report-table-body-month'))
        });
    }

    function fetchYearData() {
        $.ajax({
            url: `{{ route('report.purchases.by_year') }}`, data: { year: $('#year-year').val(), search: $('#search-purchase-year').val() },
            beforeSend: () => showLoading($('#report-table-body-year'), $('#report-table-footer-year')),
            success: function(data) {
                $('#report-table-body-year').html(data.table);
                $('#report-table-footer-year').html(data.footer);
                $('#report-title-year').text(data.formattedDate);
                updateExportLink(); // ✅ UPDATE LINK ON SUCCESS
            },
            error: () => showError($('#report-table-body-year'))
        });
    }

    tabs.on('click', function() {
        const target = $(this).data('tab-target');
        tabs.removeClass('border-blue-500 text-blue-600 dark:text-blue-500').addClass('border-transparent hover:text-gray-600 dark:hover:border-gray-300');
        $(this).addClass('border-blue-500 text-blue-600 dark:text-blue-500');
        panes.addClass('hidden');
        $(target).removeClass('hidden');
        activeTab = target.replace('#', '').replace('-tab-content', '');
        updateExportLink(); // ✅ UPDATE LINK ON TAB SWITCH

        if (!$(this).data('loaded')) {
            if (activeTab === 'month') fetchMonthData();
            else if (activeTab === 'year') fetchYearData();
            $(this).data('loaded', true);
        }
    });
    
    // Combined event handlers for filters
    $('#date-day, #month-month, #year-year').on('change', function() {
        if(activeTab === 'day') fetchDayData();
        if(activeTab === 'month') fetchMonthData();
        if(activeTab === 'year') fetchYearData();
    });

    $('#search-purchase-day, #search-purchase-month, #search-purchase-year').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if(activeTab === 'day') fetchDayData();
            if(activeTab === 'month') fetchMonthData();
            if(activeTab === 'year') fetchYearData();
        }, 500);
    });

    tabs.first().trigger('click');
    fetchDayData();
});
</script>
@endsection