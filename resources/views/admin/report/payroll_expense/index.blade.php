@extends('admin/admin_dashboard')
@section('admin')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
    .tbody tr:hover { background-color: #cacaca61; }
    .dark .tbody tr:hover { background-color: #6d6d6d61; }
</style>

<div class="container mx-auto p-6">
    <div class="lg:col-span-full p-0">
        
        {{-- 1. HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h2 class="text-xl text-defalut flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <div class="px-2 text-3xl font-bold text-defalut">{{ __('messages.payroll_report') }}</div> 
            </h2>
        </div>
        
        {{-- 2. 🟢 UPGRADE: FILTERS (Month Range) --}}
        <div class="mb-4 card-dynamic-bg p-4 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="start_month" class="block text-sm font-medium text-defalut">{{ __('messages.sart_month') }}</label>
                    <input type="month" id="start_month" name="start_month" 
                           value="{{ now()->format('Y-m') }}" 
                           class="mt-1 form-input rounded-lg card-dynamic-bg border-primary text-defalut report-filter">
                </div>
                <div>
                    <label for="end_month" class="block text-sm font-medium text-defalut">{{ __('messages.to_month') }}</label>
                    <input type="month" id="end_month" name="end_month" 
                           value="{{ now()->format('Y-m') }}"
                           class="mt-1 form-input rounded-lg card-dynamic-bg border-primary text-defalut report-filter">
                </div>
                <div class="py-6 px-4">
                    <a href="#" id="exportExcelBtn" target="_blank" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor" class="size-5">
                            <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM155.7 250.2L192 302.1l36.3-51.9c7.6-10.9 22.6-13.5 33.4-5.9s13.5 22.6 5.9 33.4L221.3 344l46.4 66.2c7.6 10.9 5 25.8-5.9 33.4s-25.8 5-33.4-5.9L192 385.8l-36.3 51.9c-7.6 10.9-22.6 13.5-33.4 5.9s-13.5-22.6-5.9-33.4L162.7 344l-46.4-66.2c-7.6-10.9-5-25.8 5.9-33.4s25.8-5 33.4 5.9z"/>
                        </svg>
                            Excel
                        </a>
                        
                        <a href="#" id="exportPdfBtn" target="_blank" class="bg-red-600 hover:bg-red-700 text-white py-3 px-6 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            PDF
                        </a>
                </div>

                {{-- <div class="flex items-center gap-2">
                        <button id="exportExcelBtn" class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path> <polyline points="14 2 14 8 20 8"></polyline> <line x1="16" y1="13" x2="8" y2="13"></line> <line x1="16" y1="17" x2="8" y2="17"></line> <polyline points="10 9 9 9 8 9"></polyline> </svg> Excel
                        </button>
                        <button id="exportPdfBtn" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path> <polyline points="14 2 14 8 20 8"></polyline> <path d="M10.29 13.71a2.43 2.43 0 0 1-2.43-2.43 2.43 2.43 0 0 1 2.43-2.43c1.34 0 2.43.95 2.43 2.1 0 .59-.22 1.16-.64 1.57"> </path> <path d="M14.71 13.71a2.43 2.43 0 0 1-2.43-2.43 2.43 2.43 0 0 1 2.43-2.43c1.34 0 2.43.95 2.43 2.1 0 .59-.22 1.16-.64 1.57"> </path> </svg> PDF
                        </button>
                </div> --}}


            </div>
        </div>
        
        {{-- 3. KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="card-dynamic-bg p-6 rounded-lg shadow-md">
                <h4 class="text-sm font-medium text-gray-500 uppercase">{{ __('messages.net_salary') }}</h4>
                <p id="kpi-total-spending" class="text-4xl font-bold text-red-600 mt-2">$0.00</p>
            </div>
            <div class="card-dynamic-bg p-6 rounded-lg shadow-md">
                <h4 class="text-sm font-medium text-gray-500 uppercase">{{ __('messages.total_payments') }}</h4>
                <p id="kpi-total-payments" class="text-4xl font-bold text-defalut mt-2">0 payments</p>
            </div>
        </div>

        {{-- 4. TABLE --}}
        <h3 id="report-title" class="text-xl font-bold text-defalut mb-4">{{ __('messages.report_for') }}...</h3>
        <div class="table-wrapper overflow-x-auto rounded-md card-dynamic-bg">
            <table class="w-full text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{!! __('messages.table_no') !!}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{{ __('messages.payment_day') }}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{{ __('messages.employees') }}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{{ __('messages.for_month') }}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{{ __('messages.basic_salary') }}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">{{ __('messages.bonus') }}</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary text-right">{{ __('messages.net_salary_th') }}</p></th>
                    </tr>
                </thead>
                <tbody class="tbody" id="report-table-body">
                    {{-- ទិន្នន័យមកពី AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- 5. PAGINATION --}}
        <div class="pagination-wrapper mt-4"></div>
    </div>
</div>

{{-- 🟢 UPGRADE: JavaScript ថ្មី --}}
<script type="text/javascript">
$(document).ready(function() {
    
    // CSRF Token Setup
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // --- 1. Function to fetch data ---
    function fetchReportData(page = 1) {
        let startMonth = $('#start_month').val(); // ឧ: "2025-11"
        let endMonth = $('#end_month').val();     // ឧ: "2025-11"
        
        let ajaxUrl = `{{ route('report.payroll_expense.data') }}?page=${page}`;
        
        $('#report-table-body').html('<tr><td colspan="7" class="text-center p-8 text-slate-500">Loading...</td></tr>');

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: {
                start_month: startMonth,
                end_month: endMonth
            },
            success: function(response) {
                $('#report-table-body').html(response.table || '<tr><td colspan="7" class="text-center p-8 text-slate-500">No data found.</td></tr>');
                $('.pagination-wrapper').html(response.pagination);
                
                // Update KPIs
                $('#report-title').text('{!! __("messages.report_for") !!} ' + response.formattedDate);
                $('#kpi-total-spending').text(response.kpis.totalSpending);
                $('#kpi-total-payments').text(response.kpis.totalPayments);
                
                // Update Export Links
                let exportParams = `?start_month=${startMonth}&end_month=${endMonth}`;
                $('#exportExcelBtn').attr('href', `{{ route('report.payroll_expense.exportExcel') }}${exportParams}`);
                $('#exportPdfBtn').attr('href', `{{ route('report.payroll_expense.exportPdf') }}${exportParams}`);
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Failed to load data.';
                $('#report-table-body').html(`<tr><td colspan="7" class="text-center p-8 text-red-500">${errorMsg}</td></tr>`);
            }
        });
    }

    // --- 2. Event Handlers ---

    // Initial load (ពេលបើកទំព័រ)
    fetchReportData();

    // 🟢 UPGRADE: លុប Filter Button, ប្រើ "change" វិញ
    // ពេលប្តូរខែ គឺ Filter ភ្លាម
    $('.report-filter').on('change', function() {
        fetchReportData(1); // Reset to page 1
    });

    // Pagination click (នៅដដែល)
    $(document).on('click', '.pagination-wrapper a', function(e) {
        e.preventDefault();
        let pageUrl = new URL($(this).attr('href'));
        let page = pageUrl.searchParams.get('page');
        fetchReportData(page);
    });

});
</script>

@endsection