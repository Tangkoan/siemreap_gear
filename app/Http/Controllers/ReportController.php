<?php

namespace App\Http\Controllers;

use App\Exports\OrderReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 

use App\Models\Order;
use App\Models\Orderdetails;
use Carbon\Carbon;
use DateTime;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function AllReports(){
        return view('admin.report.all_report');
    }
    // End Method 

    

public function AdminSearchByDate(Request $request)
{
    $date = $request->date;
    $search = $request->search;
    $perPage = $request->perPage ?? 10;

    $query = Order::with('customer')
        ->whereDate('order_date', $date)
        ->orderBy('id', 'desc');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('invoice_no', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($perPage === 'all') {
        $orders = $query->get();
    } else {
        $orders = $query->paginate((int) $perPage);
    }

    // Ajax response for JS
    if ($request->ajax()) {
        $table = '';
        $totalAmount = 0;

        foreach ($orders as $key => $item) {
            $totalAmount += $item->total;
            $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
            $table .= '<td class="px-4 py-3">' . ($key + 1) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->order_date . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->invoice_no . '</td>';
            $table .= '<td class="px-4 py-3">$' . number_format($item->total, 2) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->payment_status . '</td>';
            $table .= '<td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">' . $item->order_status . '</span></td>';
            $table .= '<td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('order.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>
                    
                    </div>

                    
                </td>';  // You can add action buttons here
            $table .= '</tr>';
        }

        $footer = '<tr>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Orders:</td>';
        $footer .= '<td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400">' . $orders->count() . '</td>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
        $footer .= '</tr>';

        $pagination = $perPage === 'all' ? '<div class="text-sm text-slate-500">Showing all results</div>' : $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'footer' => $footer,
            'pagination' => $pagination
        ]);
    }

    // First page load
    $formattedDate = date('d F Y', strtotime($date));
    return view('admin.report.search_by_date', compact('formattedDate', 'date'));
}


public function AminSearchByMonth(Request $request)
{
    $month = $request->month;         // eg: 6
    $year = $request->year_name;      // eg: 2025
    $search = $request->search;
    $perPage = $request->perPage ?? 10;

    // Use raw query in case 'order_date' is not real DATE type
    $query = Order::with('customer')
        ->whereRaw('DATE_FORMAT(order_date, "%Y") = ?', [$year])
        ->whereRaw('DATE_FORMAT(order_date, "%m") = ?', [str_pad($month, 2, '0', STR_PAD_LEFT)]) // '06'
        ->orderBy('id', 'desc');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('invoice_no', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($perPage === 'all') {
        $orders = $query->get();
    } else {
        $orders = $query->paginate((int)$perPage);
    }

    if ($request->ajax()) {
        $table = '';
        $totalAmount = 0;

        foreach ($orders as $key => $item) {
            $totalAmount += $item->total;
            $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
            $table .= '<td class="px-4 py-3">' . ($key + 1) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->order_date . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->invoice_no . '</td>';
            $table .= '<td class="px-4 py-3">$' . number_format($item->total, 2) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->payment_status . '</td>';
            $table .= '<td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">' . $item->order_status . '</span></td>';
            $table .= '<td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200 focus:outline-none">
                        <a href="' . route('order.details', $item->id) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        </a>
                    </button>
                    </div>
                </td>';
            $table .= '</tr>';
        }

        $footer = '<tr>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Orders:</td>';
        $footer .= '<td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400">' . $orders->count() . '</td>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
        $footer .= '</tr>';

        $pagination = $perPage === 'all'
            ? '<div class="text-sm text-slate-500">Showing all results</div>'
            : $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'footer' => $footer,
            'pagination' => $pagination
        ]);
    }

    return view('admin.report.search_by_month', compact('month', 'year'));
}



public function AminSearchByYear(Request $request){ 
    $year = $request->year;
    $search = $request->search;
    $perPage = $request->perPage ?? 10;

    $query = Order::with('customer')
        ->whereYear('order_date', $year)
        ->orderBy('id', 'desc');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('invoice_no', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($perPage === 'all') {
        $orders = $query->get();
    } else {
        $orders = $query->paginate((int) $perPage);
    }

    if ($request->ajax()) {
        $table = '';
        $totalAmount = 0;

        foreach ($orders as $key => $item) {
            $totalAmount += $item->total;
            $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
            $table .= '<td class="px-4 py-3">' . ($key + 1) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->order_date . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->invoice_no . '</td>';
            $table .= '<td class="px-4 py-3">$' . number_format($item->total, 2) . '</td>';
            $table .= '<td class="px-4 py-3">' . $item->payment_status . '</td>';
            $table .= '<td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">' . $item->order_status . '</span></td>';
            $table .= '<td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200 focus:outline-none">
                        <a href="' . route('order.details', $item->id) . '" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        </a>
                    </button>
                    </div>
                </td>';
            $table .= '</tr>';
        }

        $footer = '<tr>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Orders:</td>';
        $footer .= '<td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400">' . $orders->count() . '</td>';
        $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
        $footer .= '</tr>';

        $pagination = $perPage === 'all' ? 
            '<div class="text-sm text-slate-500">Showing all results</div>' : 
            $orders->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'footer' => $footer,
            'pagination' => $pagination
        ]);
    }

    return view('admin.report.search_by_year', compact('year'));
}



public function SaleReportExport(Request $request){
    $date = $request->date;
    $month = $request->month;
    $year = $request->year;

    return Excel::download(new OrderReportExport($date, $month, $year), 'filtered_report.xlsx');
    // return Excel::download(new OrderReportExport,'sale_report.xlsx');
}
// End Export


}