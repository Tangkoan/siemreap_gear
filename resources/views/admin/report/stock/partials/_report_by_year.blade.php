@php
    // Set a default value for year if it's not passed, e.g., current year
    $year = $year ?? date('Y');
    $formattedDate = $formattedDate ?? $year;
@endphp

{{-- Title --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200 flex items-center">
        <span class="px-2">Report for:</span>
        <span id="report-title-year" class="px-2 text-blue-600 dark:text-blue-400">{{ $formattedDate }}</span>
    </h2>
</div>

{{-- Filters & Export --}}
<div class="w-full flex flex-wrap justify-between items-end gap-4 mb-4">
    <div class="flex items-end gap-4">
        {{-- Year Picker --}}
        <div>
            <input type="number" name="year" id="year" class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm w-full" value="{{ $year }}" placeholder="Enter Year" min="2000">
        </div>
        {{-- Search Input --}}
        <div class="relative">
            <input class="dark:text-white dark:bg-gray-800 w-full pr-11 h-10 pl-3 placeholder:text-slate-400 text-sm border border-slate-300 rounded" placeholder="Search for name" id="search-year" name="search" type="text" />
        </div>
    </div>
    <div>
        <a id="exportBtn-year" href="{{ route('report.stock.export.year', ['year' => $year]) }}" class="h-10 inline-flex items-center px-4 py-2 bg-green-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
            Export to Excel
        </a>
    </div>
</div>

{{-- Table --}}
<div class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="table-wrapper overflow-y-auto max-h-[60vh]">
        <table class="w-full text-left table-auto min-w-max">
            <thead class="sticky top-0 bg-slate-50 dark:bg-gray-900">
                <tr>
                    <th class="p-4 border-b border-slate-200"><p class="font-semibold text-slate-500">Product Name</p></th>
                    <th class="p-4 border-b border-slate-200"><p class="font-semibold text-slate-500">Opening</p></th>
                    <th class="p-4 border-b border-slate-200"><p class="font-semibold text-slate-500">Stock In</p></th>
                    <th class="p-4 border-b border-slate-200"><p class="font-semibold text-slate-500">Stock out</p></th>
                    <th class="p-4 border-b border-slate-200"><p class="font-semibold text-slate-500">Closing</p></th>
                </tr>
            </thead>
            {{-- ID has been changed to "report-table-body-year" --}}
            <tbody id="report-table-body-year" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm"></tbody>
        </table>
    </div>
    {{-- ID has been changed to "pagination-links-year" --}}
    <div id="pagination-links-year" class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700"></div>
</div>