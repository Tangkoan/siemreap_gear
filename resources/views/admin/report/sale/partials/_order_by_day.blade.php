@php
    $date = $date ?? date('Y-m-d');
    $formattedDate = $formattedDate ?? \Carbon\Carbon::parse($date)->format('d F Y');
@endphp

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
        Report Date: <span id="report-title-day" class="text-blue-600 dark:text-blue-400">{{ $formattedDate }}</span>
    </h2>
</div>

<div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
    <div class="flex items-end gap-4">
        <input type="date" name="date" id="date-day" class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm w-full" value="{{ $date }}">
        <input class="h-10 dark:text-white dark:bg-gray-800 bg-white w-full pr-11 pl-3 placeholder:text-slate-400 border border-slate-200 rounded" placeholder="Search..." id="search-day" type="text" />
    </div>
    <div>
        <a id="exportBtn-day" href="{{ route('report.orders.export.date', ['date' => $date]) }}" class="h-10 inline-flex items-center px-4 py-2 bg-green-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
            Export
        </a>
    </div>
</div>

<div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="table-wrapper overflow-y-auto max-h-[70vh]">
        <table class="w-full text-left table-auto min-w-max">
            <thead class="sticky top-0 bg-slate-50 dark:bg-gray-900">
                <tr>
                    <th class="p-4">No.</th><th class="p-4">Date</th><th class="p-4">Invoice</th><th class="p-4">Customer</th><th class="p-4">Amount</th><th class="p-4">Payment</th><th class="p-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="report-table-body-day" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm"></tbody>
            <tfoot id="report-table-footer-day" class="bg-slate-50 dark:bg-gray-900 font-bold"></tfoot>
        </table>
    </div>
    <div id="pagination-links-day" class="p-4 bg-white dark:bg-gray-800 border-t"></div>
</div>