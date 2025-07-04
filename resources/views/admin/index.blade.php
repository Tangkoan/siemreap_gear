@extends('admin/admin_dashboard')
@section('admin')
@php
    $date = date('d-F-Y');
    $today_paid = App\Models\Order::where('order_date', $date)->sum('pay');
    $total_paid = App\Models\Order::sum('pay');
    $total_due = App\Models\Order::sum('due');
    $completeorder = App\Models\Order::where('order_status', 'complete')->get();
    $pendingorder = App\Models\Order::where('order_status', 'pending')->get();
@endphp

<main class="flex-1 p-6 space-y-8 transition-colors duration-300">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">📈 Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Paid -->
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Paid</h3>
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">New</span>
            </div>
            <p class="text-4xl font-bold text-green-600">${{ $total_paid }}</p>
        </div>

        <!-- Total Due -->
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Total Due</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">New</span>
            </div>
            <p class="text-4xl font-bold text-blue-600">${{ $total_due }}</p>
        </div>

        <!-- Complete Order -->
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Complete Order</h3>
                <span class="bg-teal-100 text-teal-800 text-xs font-semibold px-2 py-1 rounded-full">New</span>
            </div>
            <p class="text-4xl font-bold text-teal-600">{{ count($completeorder) }}</p>
        </div>

        <!-- Pending Order -->
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pending Order</h3>
                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">New</span>
            </div>
            <p class="text-4xl font-bold text-red-600">{{ count($pendingorder) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Sales by Category</h3>
            <div class="h-64">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
        <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Weekly Sales</h3>
            <div class="h-64">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="p-6 rounded-lg shadow bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Recent Sales</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-gray-800 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase">
                    <tr>
                        <th class="py-3 px-4">Sale ID</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Seller</th>
                        <th class="py-3 px-4">Sub Total</th>
                        <th class="py-3 px-4">Grand Total</th>
                        <th class="py-3 px-4">Total Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                        <td class="py-3 px-4">#001</td>
                        <td class="py-3 px-4">Mr. Sarun</td>
                        <td class="py-3 px-4">Sokchea</td>
                        <td class="py-3 px-4">$100.00</td>
                        <td class="py-3 px-4">$110.00</td>
                        <td class="py-3 px-4 text-green-600">$110.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                        <td class="py-3 px-4">#002</td>
                        <td class="py-3 px-4">Ms. SreyLeak</td>
                        <td class="py-3 px-4">Dara</td>
                        <td class="py-3 px-4">$75.50</td>
                        <td class="py-3 px-4">$80.00</td>
                        <td class="py-3 px-4 text-red-600">$70.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-4">#003</td>
                        <td class="py-3 px-4">Mr. Sovann</td>
                        <td class="py-3 px-4">Sokchea</td>
                        <td class="py-3 px-4">$250.00</td>
                        <td class="py-3 px-4">$270.00</td>
                        <td class="py-3 px-4 text-green-600">$270.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
