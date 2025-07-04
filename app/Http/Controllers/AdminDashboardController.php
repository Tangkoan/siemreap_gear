<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function Dashboard()
    {
        $date = date('d-F-Y');

        $today_paid = Order::where('order_date', $date)->sum('pay');
        $total_paid = Order::sum('pay');
        $total_due = Order::sum('due');
        $completeorder = Order::where('order_status', 'complete')->get();
        $pendingorder = Order::where('order_status', 'pending')->get();

        // Monthly Sales Report
        $currentMonth = Carbon::now()->format('F Y');

        $thisMonthSales = Order::whereMonth('order_date', Carbon::now()->month)
                               ->whereYear('order_date', Carbon::now()->year)
                               ->sum('total');

        $lastMonthSales = Order::whereMonth('order_date', Carbon::now()->subMonth()->month)
                               ->whereYear('order_date', Carbon::now()->subMonth()->year)
                               ->sum('total');

        $growth = 0;
        if ($lastMonthSales > 0) {
            $growth = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        }

        return view('admin.index', compact(
            'date',
            'today_paid',
            'total_paid',
            'total_due',
            'completeorder',
            'pendingorder',
            'currentMonth',
            'thisMonthSales',
            'lastMonthSales',
            'growth'
        ));
    }
}
