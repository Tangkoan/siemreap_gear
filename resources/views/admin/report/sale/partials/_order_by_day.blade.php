@php $date = date('Y-m-d'); @endphp


    <style>
        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>


<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 ">
        <div class=" p-6 rounded-2xl card-dynamic-bg shadow-sm"><h3 class="text-sm font-medium text-defalut">{{ __('messages.total_revenue') }}</h3><p id="kpi-revenue-day" class="text-3xl font-bold text-primary mt-2">$0.00</p></div>
        <div class=" p-6 rounded-2xl card-dynamic-bg shadow-sm"><h3 class="text-sm font-medium text-defalut">{{ __('messages.total_orders') }}</h3><p id="kpi-orders-day" class="text-3xl font-bold text-primary mt-2">0</p></div>
        <div class=" p-6 rounded-2xl card-dynamic-bg shadow-sm"><h3 class="text-sm font-medium text-defalut">{{ __('messages.items_sold') }}</h3><p id="kpi-items-day" class="text-3xl font-bold text-primary mt-2">0</p></div>
        <div class=" p-6 rounded-2xl card-dynamic-bg shadow-sm"><h3 class="text-sm font-medium text-defalut">{{ __('messages.avg_order_value') }}</h3><p id="kpi-avg-day" class="text-3xl font-bold text-primary mt-2">$0.00</p></div>
        <div class=" p-6 rounded-2xl card-dynamic-bg shadow-sm">
            <h3 class="text-sm font-medium text-defalut">{{ __('messages.total_pre_orders') }}</h3>
            
            <p id="kpi-pre_orders-day" class="text-3xl font-bold text-primary mt-2">0</p>
        </div>
    </div>

    {{-- Control Bar --}}
    <div class=" p-4 rounded-2xl shadow-sm card-dynamic-bg">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <input type="date" id="date-day" class="form-input w-full md:w-auto card-dynamic-bg text-defalut border-primary rounded-lg " value="{{ $date }}">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                    <input class="form-input w-full pl-10 card-dynamic-bg text-defalut border-primary rounded-lg " placeholder="Search..." id="search-day" type="text" />
                </div>
            </div>
            <a id="exportBtn-day" 
                href="{{ route('report.orders.export.date', ['date' => $date]) }}" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl 
                        bg-gradient-to-r bg-primary text-defalut font-medium 
                        shadow-md hover:shadow-lg  
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

    {{-- Table Container --}}
    <div class="card-dynamic-bg shadow-sm rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-defalut">
                <thead class="text-xs uppercase card-dynamic-bg text-defalut">
                    <tr>
                        <th scope="col" class="px-6 py-4">{{ __('messages.no') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('messages.date') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('messages.invoice') }}</th>
                        <th scope="col" class="px-6 py-4">{{ __('messages.customer_name') }}</th>
                        <th scope="col" class="px-6 py-4 text-right">{{ __('messages.amount') }}</th>
                        <th scope="col" class="px-6 py-4 text-center">{{ __('messages.payment') }}</th>
                        <th scope="col" class="px-6 py-4 text-center">{{ __('messages.table_action') }}</th>
                    </tr>
                </thead>
                <tbody id="report-table-body-day" class="tbody"></tbody>
                <tfoot id="report-table-footer-day" class="text-sm  card-dynamic-bg text-defalut"></tfoot>
            </table>
        </div>
        {{-- <div id="pagination-links-day" class="p-4 border-t border-slate-200 dark:border-slate-700"></div> --}}
    </div>
</div>