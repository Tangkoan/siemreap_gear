{{-- ✨ NEW 2025 UI/UX for Stock Report --}}

{{-- Report Title --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
        {{ __('messages.stock_movement_for') }} : 
        <span id="report-title-day" class="text-emerald-600 dark:text-emerald-400">{{ date('d F Y') }}</span>
    </h2>
</div>

{{-- KPI Summary Cards --}}
{{-- ✨ NEW 2025 KPI Cards Design --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    {{-- KPI Card: Total Stock In --}}
    <div class="
        bg-white dark:bg-slate-800 
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
            <p id="total-stock-in-day" class="text-3xl font-bold text-slate-800 dark:text-white mt-1">0</p>
        </div>
    </div>

    {{-- KPI Card: Total Stock Out --}}
    <div class="
        bg-white dark:bg-slate-800 
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
            <p id="total-stock-out-day" class="text-3xl font-bold text-slate-800 dark:text-white mt-1">0</p>
        </div>
    </div>

</div>

{{-- Control Bar: Filters & Export --}}
<div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-md mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">

            {{-- ✨ សម្រាប់ Pick Date && Search --}}
            <div class="relative">
                 <div class="flex items-center gap-4 w-full md:w-auto">
                    <input type="date" name="date" value="2025-09-12" id="date" class="form-input w-full md:w-auto bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 rounded-lg focus:ring-red-500 focus:border-red-500" value="{{ date('m/d/Y') }}">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                        <input class="form-input w-full pl-10 bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Search..." id="search-day" type="text" />
                    </div>
                </div>

            </div>

            
        </div>
        {{-- Redesigned Export Button --}}
        <a id="exportBtn-day" 
                href="{{ route('report.stock.export.day', ['date' => date('Y-m-d')]) }}" 
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

{{-- Redesigned Table --}}
<div class="bg-white dark:bg-slate-800 shadow-md rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
            <thead class="text-xs text-slate-700 uppercase bg-slate-100 dark:bg-slate-700 dark:text-slate-300">
                <tr>
                    <th scope="col" class="px-6 py-4 whitespace-nowrap">{{ __('messages.product_name') }}</th>
                    <th scope="col" class="px-6 py-4 text-center">{{ __('messages.opening_stock') }}</th>
                    <th scope="col" class="px-6 py-4 text-center text-emerald-600 dark:text-emerald-400">{{ __('messages.stock_in') }}</th>
                    <th scope="col" class="px-6 py-4 text-center text-rose-600 dark:text-rose-400">{{ __('messages.stock_out') }}</th>
                    <th scope="col" class="px-6 py-4 text-center">{{ __('messages.closing_stock') }}</th>
                </tr>
            </thead>
            <tbody id="report-table-body-day" class="divide-y divide-slate-200 dark:divide-slate-700">
                {{-- Data loaded by AJAX --}}
            </tbody>
        </table>
    </div>
    <div id="pagination-links-day" class="p-4 border-t border-slate-200 dark:border-slate-700">
        {{-- Pagination loaded by AJAX --}}
    </div>
</div>

{{-- Redesigned Modal --}}


{{-- Helper Styles for the new design --}}
<style>
.form-input { 
    @apply h-12 px-4 border bg-transparent text-slate-800 dark:text-white border-slate-300 dark:border-slate-600 rounded-xl text-sm w-full 
           focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-colors duration-300;
}
.btn-gradient {
    @apply inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r text-white  
           shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 ease-in-out;
}
/* We can remove the old date picker style as it's no longer needed */
/* input[type="date"]::-webkit-calendar-picker-indicator {
    @apply cursor-pointer rounded-full p-1; 
    filter: invert(0.5) sepia(1) saturate(5) hue-rotate(110deg);
}
.dark input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.8) brightness(1.2);
} 
*/
</style>
<script>
    // Set default date for consistency on initial load
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        if (!dateInput.value) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const dd = String(today.getDate()).padStart(2, '0');
            dateInput.value = `${yyyy}-${mm}-${dd}`;
        }
    });
</script>