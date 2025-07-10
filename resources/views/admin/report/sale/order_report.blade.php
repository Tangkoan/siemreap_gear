@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content py-8 px-4 dark:bg-gray-800 min-h-screen w-full">
    <div class="container mx-auto">

        {{-- Page Title --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-6 ">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                Orders Report
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
        <div id="reportTabContent">
            {{-- By Day Tab Content --}}
            <div class="tab-pane p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="day-tab-content" role="tabpanel">
                @include('admin.report.sale.partials._order_by_day')
            </div>

            {{-- By Month Tab Content --}}
            <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="month-tab-content" role="tabpanel">
                @include('admin.report.sale.partials._order_by_month')
            </div>

            {{-- By Year Tab Content --}}
            <div class="tab-pane hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-900" id="year-tab-content" role="tabpanel">
                 @include('admin.report.sale.partials._order_by_year')
            </div>
        </div>
    </div>
</div>

{{-- Modal HTML (include once) --}}
@include('admin.report.sale.partials._order_details_modal')

<script>
    $(document).ready(function() {
        const tabs = $('.tab-button');
        const panes = $('.tab-pane');
        let activeTab = 'day';
        let searchTimeout;

        function showLoading(tableBody, tableFooter) {
            tableBody.html('<tr><td colspan="7" class="text-center p-6"><span>Loading...</span></td></tr>');
            if (tableFooter) tableFooter.html('');
        }

        function showError(tableBody) {
            tableBody.html('<tr><td colspan="7" class="text-center text-red-500 p-6">Failed to load data.</td></tr>');
        }

        // --- By Day Logic ---
        function fetchDayData(page = 1) {
            let date = $('#date-day').val();
            let search = $('#search-day').val();
            showLoading($('#report-table-body-day'), $('#report-table-footer-day'));

            $.ajax({
                url: `{{ route('report.orders.by_date') }}?page=${page}`,
                type: 'GET',
                data: { date: date, search: search },
                success: function(data) {
                    $('#report-table-body-day').html(data.table);
                    $('#report-table-footer-day').html(data.footer);
                    $('#report-title-day').text(data.formattedDate);
                    updateExportLinkDay();
                },
                error: function() { showError($('#report-table-body-day')); }
            });
        }
        function updateExportLinkDay() {
            let url = new URL("{{ route('report.orders.export.date') }}");
            url.searchParams.set('date', $('#date-day').val());
            if ($('#search-day').val()) url.searchParams.set('search', $('#search-day').val());
            $('#exportBtn-day').attr('href', url.href);
        }

        // --- By Month Logic ---
        function fetchMonthData(page = 1) {
            let month = $('#month-month').val();
            let search = $('#search-month').val();
            showLoading($('#report-table-body-month'), $('#report-table-footer-month'));

            $.ajax({
                url: `{{ route('report.orders.by_month') }}?page=${page}`,
                type: 'GET',
                data: { month: month, search: search },
                success: function(data) {
                    $('#report-table-body-month').html(data.table);
                    $('#report-table-footer-month').html(data.footer);
                    $('#report-title-month').text(data.formattedDate);
                    updateExportLinkMonth();
                },
                error: function() { showError($('#report-table-body-month')); }
            });
        }
        function updateExportLinkMonth() {
            let url = new URL("{{ route('report.orders.export.month') }}");
            url.searchParams.set('month', $('#month-month').val());
            if ($('#search-month').val()) url.searchParams.set('search', $('#search-month').val());
            $('#exportBtn-month').attr('href', url.href);
        }

        // --- By Year Logic ---
        function fetchYearData(page = 1) {
            let year = $('#year-year').val();
            let search = $('#search-year').val();
            showLoading($('#report-table-body-year'), $('#report-table-footer-year'));

            $.ajax({
                url: `{{ route('report.orders.by_year') }}?page=${page}`,
                type: 'GET',
                data: { year: year, search: search },
                success: function(data) {
                    $('#report-table-body-year').html(data.table);
                    $('#report-table-footer-year').html(data.footer);
                    $('#report-title-year').text(data.formattedDate);
                    updateExportLinkYear();
                },
                error: function() { showError($('#report-table-body-year')); }
            });
        }
        function updateExportLinkYear() {
            let url = new URL("{{ route('report.orders.export.year') }}");
            url.searchParams.set('year', $('#year-year').val());
            if ($('#search-year').val()) url.searchParams.set('search', $('#search-year').val());
            $('#exportBtn-year').attr('href', url.href);
        }

        // --- Tab Switching & Event Handlers ---
        tabs.on('click', function() {
            const target = $(this).data('tab-target');
            tabs.removeClass('border-blue-500 text-blue-600').addClass('border-transparent hover:text-gray-600');
            $(this).addClass('border-blue-500 text-blue-600');
            panes.addClass('hidden');
            $(target).removeClass('hidden');
            activeTab = target.replace('#', '').replace('-tab-content', '');

            if (!$(this).data('loaded')) {
                if (activeTab === 'month') fetchMonthData();
                else if (activeTab === 'year') fetchYearData();
                $(this).data('loaded', true);
            }
        });

        $('#date-day').on('change', function() { fetchDayData(1); });
        $('#search-day').on('keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchDayData(1), 500); });

        $('#month-month').on('change', function() { fetchMonthData(1); });
        $('#search-month').on('keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchMonthData(1), 500); });

        $('#year-year').on('change', function() { fetchYearData(1); });
        $('#search-year').on('keyup', function() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchYearData(1), 500); });

        // Initial Load
        tabs.first().trigger('click');
        fetchDayData();
    });
</script>

{{-- Modal Script (include once) --}}


@endsection