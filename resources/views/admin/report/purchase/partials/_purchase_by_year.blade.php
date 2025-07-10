@php
    $year = date('Y');
@endphp
<h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-6">
    Report Year: <span id="report-title-year" class="text-blue-600 dark:text-blue-400">{{ $year }}</span>
</h2>

<div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
    <div class="flex items-end gap-4">
        <input type="number" id="year-year" class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm" value="{{ $year }}" min="2000">
        <input class="h-10 dark:text-white dark:bg-gray-800 w-full pr-11 pl-3 placeholder:text-slate-400 border border-slate-200 rounded" placeholder="Search Invoice or Supplier..." id="search-purchase-year" type="text" />
    </div>
    <div>
        <a href="#" class="export-btn h-10 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export
        </a>
    </div>
</div>

<div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="table-wrapper overflow-y-auto max-h-[70vh]">
        <table class="w-full text-left table-auto min-w-max">
            <thead class="sticky top-0 bg-slate-200 dark:bg-slate-900"><tr><th class="p-3">No.</th><th class="p-3">Date</th><th class="p-3">Invoice</th><th class="p-3">Supplier</th><th class="p-3 text-right">Total</th><th class="p-3 text-center">Status</th><th class="p-3 text-center">Action</th></tr></thead>
            <tbody id="report-table-body-year" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm"></tbody>
            <tfoot id="report-table-footer-year" class="bg-slate-200 dark:bg-slate-900 font-bold"></tfoot>
        </table>
    </div>
</div>