@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        /* Custom style for table rows for better readability */
        #income-details-body tr:hover,
        #expense-details-body tr:hover {
            background-color: #f9fafb; /* gray-50 */
        }
        .dark #income-details-body tr:hover,
        .dark #expense-details-body tr:hover {
            background-color: #1e293b; /* slate-800 */
        }
        #income-details-body tr td,
        #expense-details-body tr td {
            padding: 0.75rem;
            vertical-align: middle;
        }
    </style>

    {{-- <div class="page-content bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 w-full"> --}}
    <div class="container mx-auto p-4 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-defalut flex items-center gap-3">
                    <span>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                            </svg>
                        </div>
                    </span>
                    <span>{{ __('messages.income_expense_report') }}</span>
                </h1>
            </div>

            <div class="p-4 sm:p-6 card-dynamic-bg rounded-xl shadow-md mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="card-dynamic-bg p-1 rounded-lg flex items-center">
                        <button class="report-tab w-full px-4 py-2 text-sm rounded-md transition-colors duration-300" data-type="daily">{{ __('messages.day') }}</button>
                        <button class="report-tab w-full px-4 py-2 text-sm rounded-md transition-colors duration-300" data-type="monthly">{{ __('messages.month') }}</button>
                        <button class="report-tab w-full px-4 py-2 text-sm rounded-md transition-colors duration-300" data-type="yearly">{{ __('messages.year') }}</button>
                    </div>

                    <div id="filter-container" class="flex items-center gap-4">
                        <div id="daily-filter-group" class="filter-group flex items-center gap-2 hidden">
                            <div class="flex flex-col">
                                <label for="daily_start_date" class="text-xs mb-1 text-primary">{{ __('messages.start_date') }}</label>
                                <input type="date" id="daily_start_date" class="filter-input-start h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="flex flex-col">
                                <label for="daily_end_date" class="text-xs mb-1 text-primary">{{ __('messages.end_date') }}</label>
                                <input type="date" id="daily_end_date" class="filter-input-end h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        <div id="monthly-filter-group" class="filter-group flex items-center gap-2 hidden">
                            <div class="flex flex-col">
                                <label for="monthly_start_date" class="text-xs mb-1 text-primary">{{ __('messages.start_month') }}</label>
                                <input type="month" id="monthly_start_date" class="filter-input-start h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div class="flex flex-col">
                                <label for="monthly_end_date" class="text-xs mb-1 text-primary">{{ __('messages.end_month') }}</label>
                                <input type="month" id="monthly_end_date" class="filter-input-end h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                        <div id="yearly-filter-group" class="filter-group flex items-center gap-2 hidden">
                            <div class="flex flex-col">
                                <label for="yearly_start_date" class="text-xs mb-1 text-primary">{{ __('messages.start_year') }}</label>
                                <input type="number" id="yearly_start_date" class="filter-input-start h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="YYYY" min="2000">
                            </div>
                            <div class="flex flex-col">
                                <label for="yearly_end_date" class="text-xs mb-1 text-primary">{{ __('messages.end_year') }}</label>
                                <input type="number" id="yearly_end_date" class="filter-input-end h-10 border-primary card-dynamic-bg text-primary rounded-lg text-sm shadow-sm focus:ring-red-500 focus:border-red-500" placeholder="YYYY" min="2000">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="export-excel-btn" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path> <polyline points="14 2 14 8 20 8"></polyline> <line x1="16" y1="13" x2="8" y2="13"></line> <line x1="16" y1="17" x2="8" y2="17"></line> <polyline points="10 9 9 9 8 9"></polyline> </svg> Excel
                        </button>
                        <button id="export-pdf-btn" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path> <polyline points="14 2 14 8 20 8"></polyline> <path d="M10.29 13.71a2.43 2.43 0 0 1-2.43-2.43 2.43 2.43 0 0 1 2.43-2.43c1.34 0 2.43.95 2.43 2.1 0 .59-.22 1.16-.64 1.57"> </path> <path d="M14.71 13.71a2.43 2.43 0 0 1-2.43-2.43 2.43 2.43 0 0 1 2.43-2.43c1.34 0 2.43.95 2.43 2.1 0 .59-.22 1.16-.64 1.57"> </path> </svg> PDF
                        </button>
                    </div>
                </div>
            </div>

            <div id="loading-spinner" class="text-center p-10">
                <svg class="animate-spin h-8 w-8 text-red-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"> </path> </svg>
                <p class="mt-2 text-defalut">Loading Report...</p>
            </div>

            <div id="report-content" style="display: none;">
                <div id="report-summary" class="text-center mb-8">
                    <h2 class="text-2xl mb-4 text-gray-700 dark:text-gray-300">
                        {{ __('messages.report_for') }}: <span id="report-title-date" class="text-primary font-bold"></span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Total Revenue --}}
                        <div class="flex items-center space-x-4 card-dynamic-bg p-6 rounded-xl shadow-md border-l-4 border-green-500 transform hover:scale-105 transition-transform duration-300">
                            <div class="flex-shrink-0 p-6 rounded-full">
                                <img src="{{ asset('images/icons/business-icon.png') }}" class="h-8 w-8" alt="Revenue Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-defalut">{{ __('messages.total_revenue') }}</h3>
                                <p id="total-revenue" class="text-3xl font-bold mt-1 text-green-600">$0.00</p>
                            </div>
                        </div>

                        {{-- Total Expense --}}
                        <div class="flex items-center space-x-4 card-dynamic-bg p-6 rounded-xl shadow-md border-l-4 border-red-500 transform hover:scale-105 transition-transform duration-300">
                            <div class="flex-shrink-0 p-6 rounded-full">
                                <img src="{{ asset('images/icons/expense.png') }}" class="h-8 w-8" alt="Expense Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-defalut">{{ __('messages.total_expense') }}</h3>
                                <p id="total-expenses" class="text-3xl font-bold mt-1 text-red-600">$0.00</p>
                            </div>
                        </div>

                        {{-- ✅✅✅ START:  ✅✅✅ --}}
                        {{-- Profit / Loss --}}
                        <div id="profit-loss-card" {{-- << Id --}}
                            class="flex items-center space-x-4 card-dynamic-bg p-6 rounded-xl shadow-md border-l-4 border-red-500 transform hover:scale-105 transition-transform duration-300">
                            <div class="flex-shrink-0 p-6 rounded-full">
                                <img src="{{ asset('images/icons/money.png') }}" class="h-8 w-8" alt="Profit/Loss Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-defalut">{{ __('messages.profit_loss') }}</h3>
                                <p id="profit-loss" class="text-3xl font-bold mt-2">$0.00</p>
                            </div>
                        </div>
                        {{-- ✅✅✅ END: ✅✅✅ --}}
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Income Details Table --}}
                    <div class="card-dynamic-bg rounded-xl shadow-md p-4 overflow-hidden">
                        <h3 class="text-xl font-bold mb-4 text-green-500 px-2">{{ __('messages.income_details') }}</h3>
                        <div class="overflow-y-auto max-h-[60vh]">
                            <table class="w-full text-sm text-left">
                                <thead class="sticky top-0 card-dynamic-bg text-green-500 uppercase text-xs">
                                    <tr>
                                        <th class="p-3">{{ __('messages.date') }}</th>
                                        <th class="p-3">{{ __('messages.details') }}</th>
                                        <th class="p-3 text-center">{{ __('messages.qty') }}</th>
                                        <th class="p-3 text-right">{{ __('messages.price') }}</th>
                                        <th class="p-3 text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="income-details-body" class="t divide-y divide-gray-200 dark:divide-slate-700"></tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Expense Details Table --}}
                    <div class="card-dynamic-bg rounded-xl shadow-md p-4 overflow-hidden">
                        <h3 class="text-xl font-bold mb-4 text-red-500 px-2">{{ __('messages.expense_details') }}</h3>
                        <div class="overflow-y-auto max-h-[60vh]">
                            <table class="w-full text-sm text-left">
                                <thead class="sticky top-0 card-dynamic-bg text-red-500 px-2 uppercase text-xs">
                                    <tr>
                                        <th class="p-3">{{ __('messages.date') }}</th>
                                        <th class="p-3">{{ __('messages.details') }}</th>
                                        <th class="p-3 text-center">{{ __('messages.qty') }}</th>
                                        <th class="p-3 text-right">{{ __('messages.price') }}</th>
                                        <th class="p-3 text-right">{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="expense-details-body" class=" tbody divide-y divide-gray-200 dark:divide-slate-700"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentReportType = 'daily';
            const today = new Date().toISOString().slice(0, 10);
            const todayMonth = today.slice(0, 7);
            const todayYear = today.slice(0, 4);

            function fetchData() {
                const activeGroup = `.filter-group:not(.hidden)`;
                const startValue = $(activeGroup).find('.filter-input-start').val();
                const endValue = $(activeGroup).find('.filter-input-end').val();

                if (!startValue || !endValue) {
                    return;
                }

                $.ajax({
                    url: `{{ route('report.income_expense.data') }}`,
                    type: 'GET',
                    data: {
                        type: currentReportType,
                        start_value: startValue,
                        end_value: endValue
                    },
                    beforeSend: () => {
                        $('#report-content').hide();
                        $('#loading-spinner').show();
                    },
                    // Find this part in your Blade file's script tag
success: function(response) {
    // ✅✅✅ START: Replace with this corrected code ✅✅✅
    $('#report-title-date').text(response.formattedDate);

    // Update the summary cards with the correct currency format
    $('#total-revenue').text('$' + response.total_revenue);
    $('#total-expenses').text('$' + response.total_expenses);
    $('#profit-loss').text('$' + response.profit_or_loss);

    const profitCard = $('#profit-loss-card');
    const profitText = $('#profit-loss');

    profitCard.removeClass('border-green-500 border-red-500');
    profitText.removeClass('text-green-600 text-red-600');

    // Check if the profit_or_loss value is negative
    // parseFloat will convert "1,400.00" to 1400 and "-51.00" to -51
    const profitValue = parseFloat(response.profit_or_loss.replace(/,/g, ''));

    if (profitValue >= 0) {
        profitCard.addClass('border-green-500');
        profitText.addClass('text-green-600');
    } else {
        profitCard.addClass('border-red-500');
        profitText.addClass('text-red-600');
    }

    $('#income-details-body').html(response.income_table_html);
    $('#expense-details-body').html(response.expense_table_html);

    $('#loading-spinner').hide();
    $('#report-content').fadeIn(300);
    // ✅✅✅ END: Replacement code ✅✅✅
},
                    error: (err) => {
                        $('#loading-spinner').html(
                            '<p class="text-red-500">Error: Failed to load report data.</p>'
                        );
                        console.error("AJAX Error:", err);
                    }
                });
            }

            function switchTab(type) {
                currentReportType = type;
                $('.report-tab').removeClass('bg-red-500 text-white').addClass('text-gray-600 dark:text-gray-300');
                $(`.report-tab[data-type="${type}"]`).removeClass('text-gray-600 dark:text-gray-300').addClass('bg-red-500 text-white');
                $('.filter-group').addClass('hidden');
                $(`#${type}-filter-group`).removeClass('hidden');
                fetchData();
            }

            function initializePage() {
                $('#daily_start_date').val(today);
                $('#daily_end_date').val(today);
                $('#monthly_start_date').val(todayMonth);
                $('#monthly_end_date').val(todayMonth);
                $('#yearly_start_date').val(todayYear);
                $('#yearly_end_date').val(todayYear);
                switchTab('daily');
            }

            $('.report-tab').on('click', function() {
                switchTab($(this).data('type'));
            });

            $('#filter-container').on('change', 'input', function() {
                fetchData();
            });

            initializePage();

            function handleExport(format) {
                const activeGroup = `.filter-group:not(.hidden)`;
                const startValue = $(activeGroup).find('.filter-input-start').val();
                const endValue = $(activeGroup).find('.filter-input-end').val();

                if (!startValue || !endValue) {
                    alert('Please select a start and end value first.');
                    return;
                }
                
                let exportUrl;
                if (format === 'excel') {
                    exportUrl = new URL("{{ route('report.income_expense.export') }}");
                } else if (format === 'pdf') {
                    exportUrl = new URL("{{ route('report.income_expense.export_pdf') }}");
                } else {
                    return;
                }

                exportUrl.searchParams.append('type', currentReportType);
                exportUrl.searchParams.append('start_value', startValue);
                exportUrl.searchParams.append('end_value', endValue);
                window.location.href = exportUrl.href;
            }

            $('#export-excel-btn').on('click', function() {
                handleExport('excel');
            });
            $('#export-pdf-btn').on('click', function() {
                handleExport('pdf');
            });
        });
    </script>
@endsection