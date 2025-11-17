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
                <div class="px-2 text-3xl font-bold text-defalut">របាយការណ៍ចំណាយប្រាក់ខែ</div> 
            </h2>
        </div>
        
        {{-- 2. 🟢 UPGRADE: FILTERS (Month Range) --}}
        <div class="mb-4 card-dynamic-bg p-4 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="start_month" class="block text-sm font-medium text-defalut">ចាប់ពីខែ</label>
                    <input type="month" id="start_month" name="start_month" 
                           value="{{ now()->format('Y-m') }}" 
                           class="mt-1 form-input rounded-lg card-dynamic-bg border-primary text-defalut report-filter">
                </div>
                <div>
                    <label for="end_month" class="block text-sm font-medium text-defalut">ដល់ខែ</label>
                    <input type="month" id="end_month" name="end_month" 
                           value="{{ now()->format('Y-m') }}"
                           class="mt-1 form-input rounded-lg card-dynamic-bg border-primary text-defalut report-filter">
                </div>
                <div class="ml-auto flex gap-2">
                    <a href="#" id="exportExcelBtn" target="_blank" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path d="M2 3a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1-1H3a1 1 0 0 1-1-1V3Z" /><path d="M6.75 15.5a.75.75 0 0 0 0-1.5H4.5V4a.75.75 0 0 0-1.5 0v10.75A.75.75 0 0 0 3.75 15.5h3Z" /><path d="M11 11.75a.75.75 0 0 0 1.5 0V8.362l1.64 2.187a.75.75 0 0 0 1.22-.914l-2.5-3.333a.75.75 0 0 0-1.22 0l-2.5 3.333a.75.75 0 1 0 1.22.914L11 8.362v3.388Z" /></svg>
                        Excel
                    </a>
                    <a href="#" id="exportPdfBtn" target="_blank" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M.99 5.24A2.25 2.25 0 0 1 3.25 3h13.5A2.25 2.25 0 0 1 19 5.25v9.5A2.25 2.25 0 0 1 16.75 17H3.25A2.25 2.25 0 0 1 .99 14.75v-9.5Zm8.25 8.25a.75.75 0 0 0 .75.75h5.25a.75.75 0 0 0 0-1.5H9.99a.75.75 0 0 0-.75.75Zm.75-3.25a.75.75 0 0 1-.75-.75V8.5a.75.75 0 0 1 1.5 0v1.5a.75.75 0 0 1-.75.75Zm-3-2.25a.75.75 0 0 0 0 1.5h.01a.75.75 0 0 0 0-1.5H6.99Z" clip-rule="evenodd" /><path d="M4.25 7.5a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z" /></svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>
        
        {{-- 3. KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="card-dynamic-bg p-6 rounded-lg shadow-md">
                <h4 class="text-sm font-medium text-gray-500 uppercase">ចំណាយសរុប (Net Salary)</h4>
                <p id="kpi-total-spending" class="text-4xl font-bold text-red-600 mt-2">$0.00</p>
            </div>
            <div class="card-dynamic-bg p-6 rounded-lg shadow-md">
                <h4 class="text-sm font-medium text-gray-500 uppercase">ចំនួនទូទាត់សរុប</h4>
                <p id="kpi-total-payments" class="text-4xl font-bold text-defalut mt-2">0 payments</p>
            </div>
        </div>

        {{-- 4. TABLE --}}
        <h3 id="report-title" class="text-xl font-bold text-defalut mb-4">របាយការណ៍សម្រាប់...</h3>
        <div class="table-wrapper overflow-x-auto rounded-md card-dynamic-bg">
            <table class="w-full text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">#</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">ថ្ងៃទូទាត់</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">បុគ្គលិក</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">សម្រាប់ខែ/ឆ្នាំ</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">ប្រាក់ខែគោល</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary">Bonus/កាត់</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200"><p class="text-sm font-normal leading-none text-primary text-right">ប្រាក់ខែសុទ្ធ (Net)</p></th>
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
                $('#report-title').text('របាយការណ៍សម្រាប់ ' + response.formattedDate);
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