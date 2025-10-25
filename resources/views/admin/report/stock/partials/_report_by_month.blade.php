@php
    $month = $month ?? date('Y-m');
    $formattedDate = $formattedDate ?? \Carbon\Carbon::parse($month)->format('F Y');
@endphp


    <style>
        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>

{{-- Report Title --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
        {{ __('messages.stock_movement_for') }} : 
        <span id="report-title-month" class="text-emerald-600 dark:text-emerald-400">{{ $formattedDate }}</span>
    </h2>
</div>

{{-- KPI Summary Cards --}}
{{-- ✨ NEW 2025 KPI Cards Design --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    {{-- KPI Card: Total Stock In --}}
    <div class="
        bg-white/80 dark:bg-gray-900/80 
        p-6 rounded-2xl shadow-md 
        border-l-4 border-emerald-500 
        flex items-center gap-6 
        transition-all duration-300 hover:shadow-xl hover:-translate-y-1
    ">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            <div class="bg-emerald-100 dark:bg-emerald-500/10 p-3 rounded-full">
                <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
        </div>
        {{-- Text Content --}}
        <div class="flex-grow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('messages.total_stock_in') }}</p>
            <p id="total-stock-in-month" class="text-3xl font-bold text-slate-800 dark:text-white mt-1">0</p>
        </div>
    </div>

    {{-- KPI Card: Total Stock Out --}}
    <div class="
        bg-white/80 dark:bg-gray-900/80 
        p-6 rounded-2xl shadow-md 
        border-l-4 border-rose-500 
        flex items-center gap-6 
        transition-all duration-300 hover:shadow-xl hover:-translate-y-1
    ">
        {{-- Icon --}}
        <div class="flex-shrink-0">
            <div class="bg-rose-100 dark:bg-rose-500/10 p-3 rounded-full">
                <svg class="w-8 h-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                </svg>
            </div>
        </div>
        {{-- Text Content --}}
        <div class="flex-grow">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('messages.total_stock_out') }}</p>
            <p id="total-stock-out-month" class="text-3xl font-bold text-slate-800 dark:text-white mt-1">0</p>
        </div>
    </div>

</div>

{{-- Control Bar: Filters & Export --}}
<div class="bg-white/80 dark:bg-gray-900/80 p-4 rounded-2xl shadow-md mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            
            {{-- ✨ NEW Redesigned Month Picker --}}
            <div class="flex items-center gap-4 w-full md:w-auto">
                <input type="month" name="month" id="month" class="form-input w-full md:w-auto bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 rounded-lg focus:ring-red-500 focus:border-red-500" value="{{ $month }}">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                    <input class="form-input w-full pl-10 bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Search..." id="search-month" type="text" />
                </div>
            </div>

        </div>
        {{-- Export Button --}}
        <a id="exportBtn-month" 
                href="{{ route('report.stock.export.month', ['month' => $month]) }}" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl 
                         bg-gradient-to-r bg-green-600 to-green-700 text-white font-medium 
                         shadow-md hover:shadow-lg hover:bg-green-700 hover:to-green-800 
                         transition duration-300 ease-in-out w-full md:w-auto">
                
                <svg xmlns="http://www.w3.org/2000/svg" 
                     fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                     stroke="currentColor" class="w-5 h-5">
                     <path stroke-linecap="round" stroke-linejoin="round" 
                         d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                
                <span>{{ __('messages.export') }}</span>
        </a>
    </div>
</div>

{{-- Table --}}
<div class="bg-white/80 dark:bg-gray-900/80 shadow-md rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
            <thead class="text-xs text-slate-700 uppercase bg-slate-100 dark:bg-slate-700 dark:text-slate-300">
                <tr>
                    <th scope="col" class="px-6 py-4">{{ __('messages.product_name') }}</th>
                    <th scope="col" class="px-6 py-4 text-center">{{ __('messages.opening_stock') }}</th>
                    <th scope="col" class="px-6 py-4 text-center text-emerald-600 dark:text-emerald-400">{{ __('messages.stock_in') }}</th>
                    <th scope="col" class="px-6 py-4 text-center text-rose-600 dark:text-rose-400">{{ __('messages.stock_out') }}</th>
                    <th scope="col" class="px-6 py-4 text-center">{{ __('messages.closing_stock') }}</th>
                </tr>
            </thead>
            <tbody id="report-table-body-month" class="tbody divide-y divide-slate-200 dark:divide-slate-700"></tbody>
        </table>
    </div>
    <div id="pagination-links-month" class="p-4 border-t border-slate-200 dark:border-slate-700"></div>
</div>