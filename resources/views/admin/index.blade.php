@extends('admin.admin_dashboard')
@section('admin')

{{-- ហៅប្រើ Library ApexCharts ក្រាហ្វិក --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="page-content bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto p-4 md:p-6 lg:p-8 space-y-8">

        {{-- បឋមកថា (Header) របស់ទំព័រ --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ __('messages.dashboard') }}</h1>
                {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">An overview of your business performance.</p> --}}
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ date('F j, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- ប្លុកបង្ហាញកាត KPI (Key Performance Indicator) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
            {{-- កាត៖ ចំណូលថ្ងៃនេះ --}}
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.today_is_revenue') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">${{ number_format($todays_revenue, 2) }}</p>
                    </div>
                    <div class="bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm">
                    @if($revenue_change >= 0)
                        <span class="flex items-center gap-1 text-green-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L6.22 8.64a.75.75 0 1 1-1.06-1.06l4.25-4.25a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10.75 5.612V16.25a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg>
                            {{ number_format($revenue_change, 1) }}%
                        </span>
                    @else
                        <span class="flex items-center gap-1 text-red-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.03-3.03a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.47 12.47a.75.75 0 1 1 1.06-1.06l3.03 3.03V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" /></svg>
                            {{ number_format(abs($revenue_change), 1) }}%
                        </span>
                    @endif
                    <span class="text-gray-500 dark:text-gray-400">{{ __('messages.today_is_revenue') }}{{ __('messages.vs_yesterday') }}</span>
                </div>
            </div>
            {{-- កាត៖ ចំណូលខែនេះ --}}
                {{-- <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">This Month's Revenue</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">${{ number_format($this_month_revenue, 2) }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" /></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Total for {{ date('F') }}</p>
                </div> --}}
            {{-- កាត៖ ចំណូលឆ្នាំនេះ --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.this_year_is_revenue') }}</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">${{ number_format($this_year_revenue, 2) }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.total_for') }} {{ date('Y') }}</p>
                </div>
            {{-- កាត៖ Total Paid --}}
             {{-- <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Paid</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">${{ number_format($total_paid, 2) }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V6.375c0-.621.504-1.125 1.125-1.125h.375m18 3.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75m0 0h.375c.621 0 1.125.504 1.125 1.125v.75c0 .621-.504 1.125-1.125 1.125h-.375m-1.5-3H15a.75.75 0 0 0-.75.75v.75m0 0a.75.75 0 0 0 .75.75h.75a.75.75 0 0 0 .75-.75v-.75M3.75 12h1.5m-1.5 0h.375c.621 0 1.125.504 1.125 1.125v.75c0 .621-.504 1.125-1.125 1.125h-.375m-1.5-3h1.5m-1.5 0h.375c.621 0 1.125.504 1.125 1.125v.75c0 .621-.504 1.125-1.125 1.125h-.375" /></svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">All-time paid amount</p>
            </div> --}}
            {{-- កាត៖ Total Due --}}
                {{-- <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Due</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">${{ number_format($total_due, 2) }}</p>
                        </div>
                        <div class="bg-amber-100 dark:bg-amber-900/50 p-3 rounded-full">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">All-time outstanding</p>
                </div> --}}
            {{-- កាត៖ Complete Orders --}}
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.complete_orders') }}</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ number_format($complete_orders_count) }}</p>
                        </div>
                        <div class="bg-teal-100 dark:bg-teal-900/50 p-3 rounded-full">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.all_time_completed') }}</p>
                </div>
        </div>

        {{-- ប្លុកមេសម្រាប់តារាងក្រាហ្វិក និងតារាងទិន្នន័យ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- ក្រឡោនខាងឆ្វេង (ធំជាង) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- តារាងក្រាហ្វិក៖ Sales Trend (30 Days) --}}
                <div class="bg-white dark:bg-gray-900 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg  text-gray-800 dark:text-white mb-4">{{ __('messages.sales_trend_last_30_days') }}</h3>
                    <div id="sales-line-chart"></div>
                </div>

                {{-- តារាងក្រាហ្វិក៖ Monthly Sales Trend (This Year) --}}
                <div class="bg-white dark:bg-gray-900 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg  text-gray-800 dark:text-white mb-4">{{ __('messages.monthly_sales_trend') }} {{ $this_year_monthly_chart_data['year'] }}</h3>
                    <div id="this-year-monthly-sales-chart"></div>
                </div>

                {{-- តារាងទិន្នន័យ៖ Recent Orders --}}
                    {{-- <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg  text-gray-800 dark:text-white">Recent Orders</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Customer</th>
                                        <th scope="col" class="px-6 py-3">Invoice #</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recent_orders as $order)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($order->customer->name ?? 'N A') }}&background=random&color=fff" alt="avatar">
                                                <span class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $order->customer->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs">{{ $order->invoice_no }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($order->order_status == 'complete')
                                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">Complete</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right  text-gray-800 dark:text-white">${{ number_format($order->total, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-gray-500">No recent orders found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
            </div>

            {{-- ក្រឡោនខាងស្តាំ (តូចជាង) --}}
            <div class="lg:col-span-1 space-y-8">
                
                {{-- តារាងក្រាហ្វិក៖ Top 5 Best-Selling Products --}}
                <div class="bg-white dark:bg-gray-900 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg  text-gray-800 dark:text-white mb-4">{{ __('messages.top5_best_selling_products') }}</h3>
                    <div id="best-selling-chart"></div>
                </div>

                {{-- តារាងក្រាហ្វិក៖ Order Distribution --}}
                <div class="bg-white dark:bg-gray-900 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg  text-gray-800 dark:text-white mb-4">{{ __('messages.order_distribution') }}</h3>
                    <div id="order-status-doughnut-chart" class="flex justify-center"></div>
                </div>

                {{-- ប្លុកថ្មីសម្រាប់បង្ហាញតារាងស្តុកទាប --}}
                    {{-- <div class="bg-white dark:bg-gray-900 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg  text-gray-800 dark:text-white mb-4">Products almost out of stock (Top 5)</h3>
                        {{-- កន្លែងនេះនឹងត្រូវបង្ហាញ Chart ដែលបានបង្កើតដោយ JavaScript --}}
                        {{-- <div id="low-stock-chart"></div> 
                    </div> --}} 

            </div>
        </div>
    </div>
</div>

<script>
// រង់ចាំឲ្យទំព័រទាំងមូលផ្ទុកទិន្នន័យរួចរាល់សិន មុននឹងដំណើរការ JavaScript
document.addEventListener("DOMContentLoaded", function() {

    // ពិនិត្យមើលថាតើគេហទំព័រកំពុងស្ថិតនៅក្នុង Dark Mode ឬអត់
    const isDarkMode = document.documentElement.classList.contains('dark');

    // === តារាងក្រាហ្វិកខ្សែប (Sales Line Chart) ===
    var salesLineChartOptions = {
        series: [{ name: "Sales", data: @json($sales_chart_data['data'] ?? []) }],
        chart: { height: 350, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        grid: { borderColor: isDarkMode ? '#374151' : '#e5e7eb', strokeDashArray: 5 },
        xaxis: {
            type: 'category',
            categories: @json($sales_chart_data['labels'] ?? []),
            labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' },
                formatter: (value) => `$${Math.round(value)}`
            }
        },
        tooltip: { theme: isDarkMode ? 'dark' : 'light' },
        fill: {
            type: 'gradient',
            gradient: { shade: isDarkMode ? 'dark' : 'light', type: "vertical", opacityFrom: 0.6, opacityTo: 0.1, stops: [0, 100] }
        },
        colors: ['#4f46e5'] // ពណ៌ Indigo
    };
    var salesLineChart = new ApexCharts(document.querySelector("#sales-line-chart"), salesLineChartOptions);
    salesLineChart.render();

    // === តារាងក្រាហ្វិកแท่ง (Best Selling Products Bar Chart) ===
    var bestSellingChartOptions = {
        series: [{ name: 'Quantity Sold', data: @json($best_selling_chart_data['data'] ?? []) }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, horizontal: true, } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($best_selling_chart_data['labels'] ?? []),
            labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } }
        },
        yaxis: { labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } } },
        colors: ['#22c55e'], // ពណ៌ Green
        tooltip: { theme: isDarkMode ? 'dark' : 'light' },
        grid: { borderColor: isDarkMode ? '#374151' : '#e5e7eb' },
    };
    var bestSellingChart = new ApexCharts(document.querySelector("#best-selling-chart"), bestSellingChartOptions);
    bestSellingChart.render();

    // === តារាងក្រាហ្វិកแท่งប្រចាំខែ (Monthly Sales Bar Chart) ===
    var thisYearSalesChartOptions = {
        series: [{ name: 'Sales', data: @json($this_year_monthly_chart_data['data'] ?? []) }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($this_year_monthly_chart_data['labels'] ?? []),
            labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } }
        },
        yaxis: {
            labels: {
                style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' },
                formatter: (value) => `$${Math.round(value)}`
            }
        },
        colors: ['#34d399'], // ពណ៌ Emerald
        tooltip: { theme: isDarkMode ? 'dark' : 'light' },
        grid: { borderColor: isDarkMode ? '#374151' : '#e5e7eb' },
    };
    var thisYearSalesChart = new ApexCharts(document.querySelector("#this-year-monthly-sales-chart"), thisYearSalesChartOptions);
    thisYearSalesChart.render();

    // === តារាងក្រាហ្វិកวงกลม (Order Status Doughnut Chart) ===
    var doughnutChartOptions = {
        series: @json($order_status_distribution['data'] ?? []),
        labels: @json($order_status_distribution['labels'] ?? []),
        chart: { type: 'donut', height: 350, foreColor: isDarkMode ? '#9ca3af' : '#6b7280' },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true, label: 'Total Orders',
                            formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        colors: ['#10b981', '#f59e0b', '#3b82f6'], // ពណ៌សម្រាប់ Complete, Pending, Pre-Orders
        legend: { position: 'bottom', horizontalAlign: 'center', markers: { fillColors: ['#10b981', '#f59e0b', '#3b82f6'] } }
    };
    var doughnutChart = new ApexCharts(document.querySelector("#order-status-doughnut-chart"), doughnutChartOptions);
    doughnutChart.render();

    // === តារាងផលិតផលជិតអស់ពីស្តុក (Low Stock Products Chart) ===
    var lowStockChartOptions = {
        series: [{
            name: 'QTY IN Stock',
            data: @json($low_stock_products_chart_data['data'] ?? []) // ទាញទិន្នន័យស្តុកពី Controller
        }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: {
            bar: { borderRadius: 4, horizontal: true, } // បង្ហាញជាក្រាហ្វិកแท่งផ្តេក
        },
        dataLabels: { 
            enabled: true, // បង្ហាញตัวเลขจำนวนនៅលើแท่งกราฟิก
            style: { colors: ['#fff'] } 
        },
        xaxis: {
            categories: @json($low_stock_products_chart_data['labels'] ?? []), // ទាញឈ្មោះផលិតផលពី Controller
            labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } }
        },
        yaxis: {
            labels: { style: { colors: isDarkMode ? '#9ca3af' : '#6b7280' } }
        },
        colors: ['#ef4444'], // ប្រើពណ៌ក្រហម (red-500) ដើម្បីជាសញ្ញាឲ្យប្រយ័ត្ន
        tooltip: { theme: isDarkMode ? 'dark' : 'light' },
        grid: { borderColor: isDarkMode ? '#374151' : '#e5e7eb' },
    };
    var lowStockChart = new ApexCharts(document.querySelector("#low-stock-chart"), lowStockChartOptions);
    lowStockChart.render(); // បង្ហាញ Chart នេះ

    // === កូដសម្រាប់គ្រប់គ្រង Dark Mode ===
    // កូដនេះនឹងដំណើរការនៅពេលមានការផ្លាស់ប្តូរ Theme (ពី Light ទៅ Dark ឬពី Dark ទៅ Light)
    const observer = new MutationObserver(() => {
        const isDarkModeNow = document.documentElement.classList.contains('dark');
        const newForeColor = isDarkModeNow ? '#9ca3af' : '#6b7280';
        const newGridColor = isDarkModeNow ? '#374151' : '#e5e7eb';

        // Update ពណ៌សម្រាប់ Chart ទាំងអស់
        salesLineChart.updateOptions({
            grid: { borderColor: newGridColor },
            xaxis: { labels: { style: { colors: newForeColor }}},
            // ✅ START: កែប្រែចំណុចនេះ
            yaxis: {
                labels: {
                    style: { colors: newForeColor },
                    formatter: (value) => `$${Math.round(value)}` // <-- បន្ថែម formatter មកវិញ
                }
            },
            // ✅ END: កែប្រែចំណុចនេះ
            tooltip: { theme: isDarkModeNow ? 'dark' : 'light' }
        });

        bestSellingChart.updateOptions({
            grid: { borderColor: newGridColor },
            xaxis: { labels: { style: { colors: newForeColor }}},
            yaxis: { labels: { style: { colors: newForeColor }}},
            tooltip: { theme: isDarkModeNow ? 'dark' : 'light' }
        });
        
        thisYearSalesChart.updateOptions({
            grid: { borderColor: newGridColor },
            xaxis: { labels: { style: { colors: newForeColor }}},
            // ✅ START: កែប្រែចំណុចនេះ
            yaxis: {
                labels: {
                    style: { colors: newForeColor },
                    formatter: (value) => `$${Math.round(value)}` // <-- បន្ថែម formatter មកវិញ
                }
            },
            // ✅ END: កែប្រែចំណុចនេះ
            tooltip: { theme: isDarkModeNow ? 'dark' : 'light' }
        });

        // Update ពណ៌សម្រាប់ Low Stock Chart ពេលប្តូរ Theme
        lowStockChart.updateOptions({
            grid: { borderColor: newGridColor },
            xaxis: { labels: { style: { colors: newForeColor }}},
            yaxis: { labels: { style: { colors: newForeColor }}},
            tooltip: { theme: isDarkModeNow ? 'dark' : 'light' }
        });

        doughnutChart.updateOptions({
            chart: { foreColor: newForeColor },
        });
    });
    // ចាប់ផ្តើមពិនិត្យមើលការផ្លាស់ប្តូរនៅលើ HTML element
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
</script>

@endsection