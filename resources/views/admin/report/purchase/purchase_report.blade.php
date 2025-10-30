@extends('admin.admin_dashboard')
@section('admin')



    <style>
        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>


    {{-- jQuery is required for the logic --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-4 min-h-screen">
        
        <div class="container mx-auto py-8 px-4 md:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
                <h1 class="text-3xl lg:text-4xl font-bold text-defalut flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 " fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    {{ __('messages.purchase_report') }}
                </h1>
            </div>

            {{-- Pill-style Tabs --}}
            <div class="mb-6">
                <div class="inline-block card-dynamic-bg  p-1.5 rounded-xl shadow-sm">
                    <ul class="flex items-center space-x-2" id="reportTab" role="tablist">
                        <li role="presentation"><button class="tab-button text-sm  px-6 py-2.5 rounded-lg"
                                type="button" role="tab" data-tab-target="#day-tab-content">
                                {{ __('messages.by_day') }}</button></li>
                        <li role="presentation"><button class="tab-button text-sm  px-6 py-2.5 rounded-lg"
                                type="button" role="tab" data-tab-target="#month-tab-content">
                                {{ __('messages.by_month') }}</button></li>
                        <li role="presentation"><button class="tab-button text-sm  px-6 py-2.5 rounded-lg"
                                type="button" role="tab" data-tab-target="#year-tab-content">
                                {{ __('messages.by_year') }}</button></li>
                    </ul>
                </div>
            </div>

            {{-- Tab Content Area --}}
            <div id="reportTabContent">
                {{-- TAB PANE: BY DAY --}}
                <div class="tab-pane" id="day-tab-content" role="tabpanel">
                    @php $date = date('Y-m-d'); @endphp
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_spending') }}</h3>
                                <p id="kpi-spending-day" class="text-3xl font-bold text-defalut mt-2">
                                    $0.00</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_purchases') }}</h3>
                                <p id="kpi-purchases-day" class="text-3xl font-bold text-defalut mt-2">0
                                </p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.items_purchased') }}</h3>
                                <p id="kpi-items-day" class="text-3xl font-bold text-defalut mt-2">0</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.avg_purchase_value') }}</h3>
                                <p id="kpi-avg-day" class="text-3xl font-bold text-defalut mt-2">$0.00</p>
                            </div>
                        </div>
                        <div
                            class="card-dynamic-bg p-4 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">

                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <input type="date" id="date-day"
                                    class="form-input w-full md:w-auto card-dynamic-bg border-primary rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $date }}">
                                <div class="relative w-full md:w-64">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg
                                            class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg></div>
                                    <input
                                        class="form-input w-full pl-10 card-dynamic-bg border-primary rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Search..." id="search-purchase-day" type="text" />
                                </div>
                            </div>




                            <a id="exportBtn-day" href="{{ route('report.purchases.export.date', ['date' => $date]) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl 
                                    bg-gradient-to-r bg-primary text-defalut font-medium 
                                    shadow-md hover:shadow-lg 
                                    transition duration-300 ease-in-out w-full md:w-auto">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>

                                <span>{{ __('messages.export') }}</span>
                            </a>
                        </div>
                        <div class="card-dynamic-bg shadow-sm rounded-2xl overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead
                                        class="text-xs  uppercase card-dynamic-bg text-defalut">
                                        <tr>
                                            <th class="px-6 py-4">{{ __('messages.no') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.date') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.invoice') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.supplier_name') }}</th>
                                            <th class="px-6 py-4 text-right">{{ __('messages.total') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.status') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.table_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-table-body-day" class="tbody">
                                    </tbody>
                                    <tfoot id="report-table-footer-day"
                                        class="text-sm text-defalut card-dynamic-bg">
                                    </tfoot>
                                </table>
                            </div>
                            <div id="pagination-links-day" class="p-4">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB PANE: BY MONTH --}}
                <div class="tab-pane hidden" id="month-tab-content" role="tabpanel">
                    @php $month = date('Y-m'); @endphp
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_spending') }}</h3>
                                <p id="kpi-spending-month" class="text-3xl font-bold text-defalut mt-2">
                                    $0.00</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_purchases') }}</h3>
                                <p id="kpi-purchases-month"
                                    class="text-3xl font-bold text-defalut mt-2">0</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.items_purchased') }}</h3>
                                <p id="kpi-items-month" class="text-3xl font-bold text-defalut mt-2">0
                                </p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.avg_purchase_value') }}</h3>
                                <p id="kpi-avg-month" class="text-3xl font-bold text-defalut mt-2">$0.00
                                </p>
                            </div>
                        </div>
                        <div
                            class="card-dynamic-bg p-4 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">

                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <input type="month" id="month-month"
                                    class="form-input w-full md:w-auto card-dynamic-bg rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $month }}">
                                <div class="relative w-full md:w-64">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg
                                            class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg></div>
                                    <input
                                        class="form-input w-full pl-10 card-dynamic-bg  rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Search..." id="search-purchase-month" type="text" />
                                </div>
                            </div>

                            <a id="exportBtn-month"
                                href="{{ route('report.purchases.export.month', ['month' => $month]) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl 
                                    bg-gradient-to-r bg-green-600 to-green-700 text-white font-medium 
                                    shadow-md hover:shadow-lg hover:bg-green-700 hover:to-green-800 
                                    transition duration-300 ease-in-out w-full md:w-auto">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>

                                <span>{{ __('messages.export') }}</span>
                            </a>
                        </div>
                        <div class="card-dynamic-bg shadow-sm rounded-2xl overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead
                                        class="text-xs text-defalut uppercase card-dynamic-bg">
                                        <tr>
                                            <th class="px-6 py-4">{{ __('messages.no') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.date') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.invoice') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.supplier_name') }}</th>
                                            <th class="px-6 py-4 text-right">{{ __('messages.total') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.status') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.table_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-table-body-month" class="tbody"></tbody>
                                    <tfoot id="report-table-footer-month"
                                        class="text-sm  text-defalut card-dynamic-bg">
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>

                {{-- TAB PANE: BY YEAR --}}
                <div class="tab-pane hidden" id="year-tab-content" role="tabpanel">
                    @php $year = date('Y'); @endphp
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_spending') }}</h3>
                                <p id="kpi-spending-year" class="text-3xl font-bold text-defalut mt-2">
                                    $0.00</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.total_purchases') }}</h3>
                                <p id="kpi-purchases-year" class="text-3xl font-bold text-defalut mt-2">
                                    0</p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.items_purchased') }}</h3>
                                <p id="kpi-items-year" class="text-3xl font-bold text-defalut mt-2">0
                                </p>
                            </div>
                            <div class="card-dynamic-bg p-6 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-medium text-defalut">
                                    {{ __('messages.avg_purchase_value') }}</h3>
                                <p id="kpi-avg-year" class="text-3xl font-bold text-defalut mt-2">$0.00
                                </p>
                            </div>
                        </div>
                        <div
                            class="card-dynamic-bg p-4 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">

                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <input type="number" id="year-year"
                                    class="form-input w-full md:w-auto card-dynamic-bg  rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ $year }}">
                                <div class="relative w-full md:w-64">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg
                                            class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg></div>
                                    <input
                                        class="form-input w-full pl-10 card-dynamic-bg  rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Search..." id="search-purchase-year" type="text" />
                                </div>
                            </div>


                            <a id="exportBtn-year" href="{{ route('report.purchases.export.year', ['year' => $year]) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl 
                                    bg-gradient-to-r bg-green-600 to-green-700 text-white font-medium 
                                    shadow-md hover:shadow-lg hover:bg-green-700 hover:to-green-800 
                                    transition duration-300 ease-in-out w-full md:w-auto">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>

                                <span>{{ __('messages.export') }}</span>
                            </a>
                        </div>
                        <div class="card-dynamic-bg shadow-sm rounded-2xl overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead
                                        class="text-xs text-defalut card-dynamic-bg">
                                        <tr>
                                            <th class="px-6 py-4">{{ __('messages.no') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.date') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.invoice') }}</th>
                                            <th class="px-6 py-4">{{ __('messages.supplier_name') }}</th>
                                            <th class="px-6 py-4 text-right">{{ __('messages.total') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.status') }}</th>
                                            <th class="px-6 py-4 text-center">{{ __('messages.table_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="report-table-body-year" class="tbody"></tbody>
                                    <tfoot id="report-table-footer-year"
                                        class="text-sm  text-defalut card-dynamic-bg">
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="purchaseDetailsModal"
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl mx-auto">
            <div id="voucher-print-area"
                class="shadow-2xl rounded-2xl card-dynamic-bg transform transition-all">
                <div class="px-8 pt-8 pb-4 flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-primary">PURCHASE VOUCHER</h1>
                        <p class="text-sm text-defalut mt-1">Invoice: <span id="purchase-invoice-no"
                                class=" text-defalut"></span></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="printPurchaseBtn" title="Print"
                            class="p-2 text-defalut rounded-full"><svg
                                class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0" />
                            </svg></button>
                        <button id="closePurchaseModalBtn" title="Close"
                            class="p-2 text-defalut rounded-full"><svg
                                class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-sm  text-defalut">SUPPLIER</p>
                            <p id="supplier-name" class="text-lg font-bold text-defalut mt-1"></p>
                            <p id="supplier-phone" class="text-sm text-defalut"></p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-sm  text-defalut">DATE</p>
                            <p id="purchase-date" class="font-medium mt-1"></p>
                            <p class="text-sm  text-defalut mt-4">Payment Method</p><span
                                id="purchase-status-badge"
                                class="px-3 py-1 text-xs font-bold rounded-full mt-1 inline-block"></span>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border dark:border-slate-700">
                        <table class="min-w-full">
                            <thead class="card-dynamic-bg">
                                <tr>
                                    <th class="p-4 text-left">PRODUCT</th>
                                    <th class="p-4 text-center">QTY</th>
                                    <th class="p-4 text-right">PRICE</th>
                                    <th class="p-4 text-right">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody id="modal-purchase-table-body" class="divide-y divide-slate-200 dark:divide-slate-600">
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-8">
                        <div class="w-full max-w-xs space-y-3">
                            <div class="flex justify-between"><span>Subtotal:</span><span
                                    id="summary-subtotal">$0.00</span></div>
                            <div class="flex justify-between"><span>Discount:</span><span id="summary-discount"
                                    class="text-red-500">-$0.00</span></div>
                            {{-- <div class="flex justify-between"><span>Shipping:</span><span
                                    id="summary-shipping">$0.00</span></div> --}}
                            <div class="border-t border-dashed my-2"></div>
                            <div class="flex justify-between font-bold text-xl"><span>Grand Total:</span><span
                                    id="summary-grandtotal">$0.00</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inline Styles and All JavaScript --}}
    <style>
        .form-input {
            @apply h-10 border card-dynamic-bg text-defalut border-slate-300 dark:border-slate-600 rounded-lg text-sm w-full focus:ring-2 focus:ring-red-500/50 focus:border-red-500;
        }

        .badge-success {
            @apply inline-block px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300;
        }

        .badge-danger {
            @apply inline-block px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300;
        }
    </style>

    <script>
        $(document).ready(function() {
            let searchTimeout;
            const activeTabClasses = 'bg-primary text-white';
            const inactiveTabClasses =
                'text-defalut hover:bg-primary';

            function showLoading(tableBody) {
                tableBody.html(
                    `<tr><td colspan="7" class="text-center p-8"><div class="flex justify-center items-center gap-2 text-defalut"><svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Loading...</span></div></td></tr>`
                    );
            }

            function updateKPIs(period, kpis) {
                if (kpis) {
                    $(`#kpi-spending-${period}`).text(kpis.spending);
                    $(`#kpi-purchases-${period}`).text(kpis.purchases);
                    $(`#kpi-items-${period}`).text(kpis.items);
                    $(`#kpi-avg-${period}`).text(kpis.avg);
                }
            }

            function updateExportLink(period) {
                const routes = {
                    day: "{{ route('report.purchases.export.date') }}",
                    month: "{{ route('report.purchases.export.month') }}",
                    year: "{{ route('report.purchases.export.year') }}"
                };
                const params = new URLSearchParams();
                if (period === 'day') params.set('date', $(`#date-day`).val());
                if (period === 'month') params.set('month', $(`#month-month`).val());
                if (period === 'year') params.set('year', $(`#year-year`).val());
                params.set('search', $(`#search-purchase-${period}`).val());

                $(`#exportBtn-${period}`).attr('href', `${routes[period]}?${params.toString()}`);
            }

            // ✅ FIXED: Correctly builds the data object for the AJAX request.
            const fetchData = (period, page = 1) => {
                const routes = {
                    day: "{{ route('report.purchases.by_date') }}",
                    month: "{{ route('report.purchases.by_month') }}",
                    year: "{{ route('report.purchases.by_year') }}"
                };

                // This is the corrected part
                let data = {
                    page: page,
                    search: $(`#search-purchase-${period}`).val()
                };
                if (period === 'day') {
                    data.date = $('#date-day').val();
                } else if (period === 'month') {
                    data.month = $('#month-month').val();
                } else if (period === 'year') {
                    data.year = $('#year-year').val();
                }

                showLoading($(`#report-table-body-${period}`));

                $.ajax({
                    url: routes[period],
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $(`#report-table-body-${period}`).html(response.table);
                        $(`#report-table-footer-${period}`).html(response.footer);
                        $(`#pagination-links-${period}`).html(response.pagination);
                        updateKPIs(period, response.kpis);
                        updateExportLink(period);
                    },
                    error: function() {
                        $(`#report-table-body-${period}`).html(
                            `<tr><td colspan="7" class="text-center p-8 text-red-500">Failed to load data.</td></tr>`
                            );
                    }
                });
            }

            $('#reportTab .tab-button').on('click', function() {
                const target = $(this).data('tab-target');
                $('#reportTab .tab-button').removeClass(activeTabClasses).addClass(inactiveTabClasses);
                $(this).removeClass(inactiveTabClasses).addClass(activeTabClasses);
                $('.tab-pane').addClass('hidden');
                $(target).removeClass('hidden');
                const period = target.replace(/#|-tab-content/g, '');
                if (!$(this).data('loaded')) {
                    fetchData(period);
                    $(this).data('loaded', true);
                }
            });

            ['day', 'month', 'year'].forEach(p => {
                $(`#date-${p}, #month-${p}, #year-${p}`).on('change', () => fetchData(p, 1));
                $(`#search-purchase-${p}`).on('keyup', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => fetchData(p, 1), 500);
                });
                $(document).on('click', `#pagination-links-${p} .pagination a`, function(e) {
                    e.preventDefault();
                    fetchData(p, $(this).attr('href').split('page=')[1]);
                });
            });

            $(document).on('click', '.view-details-btn', function() {
                $.ajax({
                    url: "{{ route('report.purchases.details') }}",
                    data: {
                        purchase_id: $(this).data('purchase-id')
                    },
                    success: function(response) {
                        const {
                            purchase,
                            purchaseDetails,
                            assetBaseUrl, 
                        } = response;
                        
                        $('#purchase-invoice-no').text(purchase.invoice_no);
                        $('#purchase-date').text(new Date(purchase.purchase_date)
                            .toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            }));
                        $('#supplier-name').text(purchase.supplier.name);
                        $('#supplier-phone').text(purchase.supplier.phone);
                        $('#purchase-status-badge').text(purchase.payment_status).attr('class',
                            'px-3 py-1 text-xs font-bold rounded-full mt-1 inline-block ' +
                            (purchase.payment_status === 'Paid' ? 'badge-success' :
                                'badge-danger'));

                        $('#summary-subtotal').text(
                            `$${parseFloat(purchase.sub_total || 0).toFixed(2)}`);
                        $('#summary-discount').text(
                            `-$${parseFloat(purchase.discount || 0).toFixed(2)}`);
                        $('#summary-shipping').text(
                            `$${parseFloat(purchase.shipping || 0).toFixed(2)}`);
                        $('#summary-grandtotal').text(
                            `$${parseFloat(purchase.total).toFixed(2)}`);

                        let detailsHtml = purchaseDetails?.length ? '' :
                            '<tr><td colspan="4" class="text-center p-6 text-defalut">No items found for this purchase.</td></tr>';
                        purchaseDetails?.forEach(item => {
                            // ✅ បង្កើត URL រូបភាពដោយប្រើ assetBaseUrl
                            const imageUrl = `${assetBaseUrl}${item.product.product_image}`;

                            detailsHtml +=
                                    `<tr class="border-b last:border-b-0 border-slate-200 dark:border-slate-700">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <img src="${imageUrl}" class="w-12 h-12 object-cover rounded-lg">
                                                <div class=" text-defalut">${item.product.product_name}</div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center">${item.quantity}</td>
                                        <td class="p-4 text-right">$${parseFloat(item.purchase_price).toFixed(2)}</td>
                                        <td class="p-4 text-right font-medium text-defalut">$${parseFloat(item.total).toFixed(2)}</td>
                                    </tr>`;
                            });

                        $('#modal-purchase-table-body').html(detailsHtml);
                        $('#purchaseDetailsModal').removeClass('hidden');
                    }
                });
            });

            const closeModal = () => $('#purchaseDetailsModal').addClass('hidden');
            $('#closePurchaseModalBtn, #purchaseDetailsModal').on('click', function(e) {
                if (this === e.target) closeModal();
            });
            $(document).on('keydown', e => e.key === "Escape" ? closeModal() : '');

            $('#printPurchaseBtn').on('click', function() {
                const content = document.getElementById('voucher-print-area').innerHTML;
                const newWindow = window.open('', 'Print', 'height=800,width=800');
                newWindow.document.write('<html><head><title>Print Purchase Voucher</title>');
                // Make sure the path to your compiled CSS is correct
                newWindow.document.write(
                    '<link href="{{ asset('build/assets/app-*.css') }}" rel="stylesheet">');
                newWindow.document.write(
                    '<style>body{background:white; padding:1rem;} #printPurchaseBtn, #closePurchaseModalBtn{display:none !important;}</style>'
                    );
                newWindow.document.write('</head><body class="print-body">');
                newWindow.document.write(content);
                newWindow.document.write('</body></html>');
                newWindow.document.close();
                newWindow.focus();
                setTimeout(() => newWindow.print(), 1000);
            });

            $('#reportTab .tab-button').first().trigger('click');



        });
    </script>
@endsection
