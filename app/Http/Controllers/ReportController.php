<?php

namespace App\Http\Controllers;

use App\Exports\OrderReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 

use App\Models\Order;
use App\Models\Orderdetails;
use App\Models\purchase;
use App\Models\purchase_details;


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

    public function SaleReportExport(Request $request){
        $date = $request->date;
        $month = $request->month;
        $year = $request->year;
    
        return Excel::download(new OrderReportExport($date, $month, $year), 'filtered_report.xlsx');
        // return Excel::download(new OrderReportExport,'sale_report.xlsx');
    }
    // End Export
    
    public function AdminSearchByMonth(Request $request)
    {
        // ... (កូដខាងដើមដូចមុន) ...
        $monthName = $request->month;
        $year = $request->year_name;
        $monthNumber = date_parse($monthName)['month'];
        $month = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);
        $search = $request->search;
        $perPage = $request->perPage ?? 10;

        $query = Order::with('customer')
            ->whereRaw('DATE_FORMAT(order_date, "%Y") = ?', [$year])
            ->whereRaw('DATE_FORMAT(order_date, "%m") = ?', [$month])
            ->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // $totalQuery = clone $query;
        // $totalAmount = $totalQuery->sum('total');
        // $totalOrdersCount = $totalQuery->count();

        if ($perPage === 'all') {
            $orders = $query->get();
            $paginationHtml = '';
        } else {
            $orders = $query->paginate((int)$perPage);
            $paginationHtml = $orders->links()->toHtml();
        }

        if ($request->ajax()) {
            $table = '';
            $totalAmount = 0;
            if ($orders->isEmpty()) {
                $table .= '<tr><td colspan="7" class="text-center py-4">No data found.</td></tr>';
            } else {
                foreach ($orders as $key => $item) {
                    // ✅✅✅ កែតម្រូវចំណុចនេះ ✅✅✅
                    // ពិនិត្យមើលថាតើត្រូវប្រើ
                    $totalAmount += $item->total;
                    $rowNumber = ($perPage === 'all')
                        ? ($key + 1) // សម្រាប់ 'all', លេខរៀងគឺ index + 1
                        : ($orders->firstItem() + $key); // សម្រាប់ pagination, ប្រើ firstItem()

                    $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
                    $table .= '<td class="px-4 py-3">' . $rowNumber . '</td>'; // ប្រើអញ្ញត្តិ $rowNumber ដែលបានគណនា
                    $table .= '<td class="px-4 py-3">' . $item->order_date . '</td>';
                    $table .= '<td class="px-4 py-3">' . $item->invoice_no . '</td>';
                    $table .= '<td class="px-4 py-3">$' . number_format($item->total, 2) . '</td>';
                    $table .= '<td class="px-4 py-3">' . $item->payment_status . '</td>';
                    $table .= '<td class="px-4 py-3"><span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">' . $item->order_status . '</span></td>';
                    $table .= '<td class="px-4 py-4 text-sm whitespace-nowrap">
                                <div class="flex items-center gap-x-6">
                                    <a href="' . route('order.details.due', $item->id) . '" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>';
                    $table .= '</tr>';
                }
            }
            // ... (កូដខាងក្រោមគឺដូចមុន) ...
            $footer = '<tr>';
            $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Orders:</td>';
            $footer .= '<td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400">' . $orders->count() . '</td>';
            $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
            $footer .= '</tr>';


            return response()->json([
                'table' => $table,
                'footer' => $footer,
                
            ]);
        }

        return view('admin.report.search_by_month', compact('monthName', 'year'));
    }
    public function AdminSearchByYear(Request $request){ 
        
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
                            <a href="' . route('order.details.due', $item->id) . '" >
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

        
            return response()->json([
                'table' => $table,
                'footer' => $footer,
                
            ]);
        }

        return view('admin.report.search_by_year', compact('year'));
    }

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
                                    <a href="' . route('order.details.due', $item->id) . '" >
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

    




////////////////////////////////////////// Stock ////////////////////////////////
    public function AllStockReports(){
        return view('admin.report.stock.all_stock_report');
    }
    // End Method

    public function stockReportByDay(Request $request)
    {
        if (!$request->ajax()) {
            $date = $request->input('date', Carbon::now()->format('Y-m-d'));
            $formattedDate = Carbon::parse($date)->format('d F Y');
            return view('admin.report.stock.stock_report_by_day', compact('date', 'formattedDate'));
        }

        $date = Carbon::parse($request->date)->startOfDay();
        $search = $request->search;
        $perPage = $request->perPage ?? 15;

        $query = Product::query()
            ->select('id', 'product_name', 'product_code')
            ->addSelect([
                'stock_in' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->whereDate('purchases.purchase_date', $date)
                    ->where('purchases.purchase_status', 'complete'),
                'stock_out' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->whereDate('orders.order_date', $date)
                    ->where('orders.order_status', 'complete'),
                'total_purchased_before' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->where('purchases.purchase_date', '<', $date)
                    ->where('purchases.purchase_status', 'complete'),
                'total_sold_before' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->where('orders.order_date', '<', $date)
                    ->where('orders.order_status', 'complete'),
            ])
            ->havingRaw('stock_in > 0 OR stock_out > 0');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        $products = ($perPage === 'all') ? $query->get() : $query->paginate((int)$perPage);
        $table = '';
        if ($products->isEmpty()) {
            $table .= '<tr><td colspan="5" class="text-center p-4">No product movement found for this day.</td></tr>';
        } else {
            foreach ($products as $product) {
                $openingStock = (int)$product->total_purchased_before ;
                $stockIn = (int)$product->stock_in;
                $stockOut = (int)$product->stock_out;
                $closingStock = $openingStock + $stockIn - $stockOut;

                // ✅ កែសម្រួលត្រង់នេះ៖ បន្ថែម data-attributes និង class សម្រាប់ JavaScript
                $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer stock-row" 
                                data-product-id="'. $product->id .'" 
                                data-product-name="'. htmlspecialchars($product->product_name) .'">';
                $table .= '<td class="p-2">'. htmlspecialchars($product->product_name) .' <span class="text-xs text-gray-500">('.htmlspecialchars($product->product_code).')</span></td>';
                $table .= '<td class="p-2 px-8">'. $openingStock .'</td>';
                $table .= '<td class="p-2 px-8 text-green-600 font-semibold">+'. $stockIn .'</td>';
                $table .= '<td class="p-2 px-8 text-red-600 font-semibold">-'. $stockOut .'</td>';
                $table .= '<td class="p-2 px-8 font-bold text-blue-600">'. $closingStock .'</td>';
                $table .= '</tr>';
            }
        }
        
        $pagination = ($perPage === 'all' || $products->isEmpty()) ? '' : $products->links()->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
            'formattedDate' => Carbon::parse($date)->format('d F Y')
        ]);
    }

    /**
     * ✅ Method ថ្មី៖ សម្រាប់ទាញយកទិន្នន័យលម្អិតប្រចាំថ្ងៃ
     */
    public function getStockMovementDetailsByDay(Request $request)
    {
        $productId = $request->productId;
        $date = Carbon::parse($request->date)->startOfDay();

        $stockIn = \App\Models\purchase_details::join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->where('purchase_details.product_id', $productId)
            ->whereDate('purchases.purchase_date', $date)
            ->where('purchases.purchase_status', 'complete')
            ->select('purchases.purchase_date as transaction_date', 'purchase_details.quantity', 'purchases.invoice_no as reference')
            ->selectRaw("'Stock In' as transaction_type");

        $stockOut = \App\Models\OrderDetails::join('orders', 'orders.id', '=', 'orderdetails.order_id')
            ->where('orderdetails.product_id', $productId)
            ->whereDate('orders.order_date', $date)
            ->where('orders.order_status', 'complete')
            ->select('orders.order_date as transaction_date', 'orderdetails.quantity', 'orders.invoice_no as reference')
            ->selectRaw("'Stock Out' as transaction_type");

        $transactions = $stockIn->unionAll($stockOut)
                                 ->orderBy('transaction_date', 'asc')
                                 ->get();
        
        return response()->json($transactions);
    }


    public function stockReportByMonth(Request $request)
    {
        if (!$request->ajax()) {
            $month = $request->input('month', Carbon::now()->format('Y-m'));
            $formattedDate = Carbon::parse($month)->format('F Y');
            return view('admin.report.stock.stock_report_by_month', compact('month', 'formattedDate'));
        }

        $monthCarbon = Carbon::parse($request->month);
        $startDate = $monthCarbon->copy()->startOfMonth();
        $endDate = $monthCarbon->copy()->endOfMonth();
        $search = $request->search;
        $perPage = $request->perPage ?? 15;

        $query = Product::query()
            ->select('id', 'product_name', 'product_code')
            ->addSelect([
                'stock_in' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
                    ->where('purchases.purchase_status', 'complete'),
                'stock_out' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->whereBetween('orders.order_date', [$startDate, $endDate])
                    ->where('orders.order_status', 'complete'),
                'total_purchased_before' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->where('purchases.purchase_date', '<', $startDate)
                    ->where('purchases.purchase_status', 'complete'),
                'total_sold_before' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->where('orders.order_date', '<', $startDate)
                    ->where('orders.order_status', 'complete'),
            ])
            ->havingRaw('stock_in > 0 OR stock_out > 0 OR (total_purchased_before - total_sold_before) > 0');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        $products = ($perPage === 'all') ? $query->get() : $query->paginate((int)$perPage);

        $table = '';
        if ($products->isEmpty()) {
            $table .= '<tr><td colspan="5" class="text-center p-4">No product movement found for this month.</td></tr>';
        } else {
            foreach ($products as $product) {
                // $openingStock = (int)$product->total_purchased_before - (int)$product->total_sold_before;
                $openingStock = (int)$product->total_purchased_before;
                $stockIn = (int)$product->stock_in;
                $stockOut = (int)$product->stock_out;
                $closingStock = $openingStock + $stockIn - $stockOut;

                // ✅ កែសម្រួលត្រង់នេះ៖ បន្ថែម data-attributes សម្រាប់ JavaScript
                $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer stock-row" 
                                data-product-id="'. $product->id .'" 
                                data-product-name="'. htmlspecialchars($product->product_name) .'">';
                $table .= '<td class="p-2">'. htmlspecialchars($product->product_name) .' <span class="text-xs text-gray-500">('.htmlspecialchars($product->product_code).')</span></td>';
                $table .= '<td class="p-2 px-8">'. $openingStock .'</td>';
                $table .= '<td class="p-2 px-8 text-green-600 font-semibold">+'. $stockIn .'</td>';
                $table .= '<td class="p-2 px-8 text-red-600 font-semibold">-'. $stockOut .'</td>';
                $table .= '<td class="p-2 px-8 font-bold text-blue-600">'. $closingStock .'</td>';
                $table .= '</tr>';
            }
        }
        
        $pagination = ($perPage === 'all' || $products->isEmpty()) ? '' : $products->links()->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
            'formattedDate' => $monthCarbon->format('F Y')
        ]);
    }

    /**
     * ✅ Method ថ្មី៖ សម្រាប់ទាញយកទិន្នន័យលម្អិតប្រចាំខែ
     */
    public function getStockMovementDetailsByMonth(Request $request)
    {
        $productId = $request->productId;
        $month = $request->month; // e.g., "2025-07"

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $stockIn = \App\Models\purchase_details::join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->where('purchase_details.product_id', $productId)
            ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
            ->where('purchases.purchase_status', 'complete')
            ->select('purchases.purchase_date as transaction_date', 'purchase_details.quantity', 'purchases.invoice_no as reference')
            ->selectRaw("'Stock In' as transaction_type");

        $stockOut = \App\Models\OrderDetails::join('orders', 'orders.id', '=', 'orderdetails.order_id')
            ->where('orderdetails.product_id', $productId)
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('orders.order_status', 'complete')
            ->select('orders.order_date as transaction_date', 'orderdetails.quantity', 'orders.invoice_no as reference')
            ->selectRaw("'Stock Out' as transaction_type");

        $transactions = $stockIn->unionAll($stockOut)
                                 ->orderBy('transaction_date', 'asc')
                                 ->get();
        
        return response()->json($transactions);
    }

    public function stockReportByYear(Request $request)
    {
        
        if (!$request->ajax()) {
            $year = $request->input('year', Carbon::now()->format('Y'));
            $formattedDate = $year;
            return view('admin.report.stock.stock_report_by_year', compact('year', 'formattedDate'));
        }

        // ---  AJAX  ---
        $year = $request->year;
        $search = $request->search;
        $perPage = $request->perPage ?? 15;

        $startDate = Carbon::create($year)->startOfYear();
        $endDate = Carbon::create($year)->endOfYear();

        $query = Product::query()
            ->select('id', 'product_name', 'product_code')
            ->addSelect([
                'stock_in' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
                    ->where('purchases.purchase_status', 'complete'),
                'stock_out' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->whereBetween('orders.order_date', [$startDate, $endDate])
                    ->where('orders.order_status', 'complete'),
                'total_purchased_before' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')
                    ->where('purchases.purchase_date', '<', $startDate)
                    ->where('purchases.purchase_status', 'complete'),
                'total_sold_before' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')
                    ->where('orders.order_date', '<', $startDate)
                    ->where('orders.order_status', 'complete'),
            ])
            ->havingRaw('stock_in > 0 OR stock_out > 0 OR (total_purchased_before - total_sold_before) > 0');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        $products = ($perPage === 'all') ? $query->get() : $query->paginate((int)$perPage);

        $table = '';
        if ($products->isEmpty()) {
            $table .= '<tr><td colspan="5" class="text-center p-4">No product with stock movement found for this year.</td></tr>';
        } else {
            foreach ($products as $key => $product) {
                $openingStock = (int)$product->total_purchased_before ;
                // $openingStock = (int)$product->total_purchased_before - (int)$product->total_sold_before;
                $stockIn = (int)$product->stock_in;
                $stockOut = (int)$product->stock_out;
                $closingStock = $openingStock + $stockIn - $stockOut;

                // បន្ថែម data-attributes សម្រាប់ JavaScript ប្រើ
                $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer stock-row" 
                                data-product-id="'. $product->id .'" 
                                data-product-name="'. htmlspecialchars($product->product_name) .'">';
                $table .= '<td class="p-2">'. htmlspecialchars($product->product_name) .' <span class="text-xs text-gray-500">('.htmlspecialchars($product->product_code).')</span></td>';
                $table .= '<td class="p-2 px-8">'. $openingStock .'</td>';
                $table .= '<td class="p-2 px-8 text-green-600 font-semibold">+'. $stockIn .'</td>';
                $table .= '<td class="p-2 px-8 text-red-600 font-semibold">-'. $stockOut .'</td>';
                $table .= '<td class="p-2 px-8 font-bold text-blue-600">'. $closingStock .'</td>';
                $table .= '</tr>';
            }
        }

        $pagination = ($perPage === 'all' || $products->isEmpty()) ? '' : $products->links()->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
            'formattedDate' => $year
        ]);
    }

    /**
     * Detsils Modal
     */
    public function getStockMovementDetails(Request $request)
    {
        $productId = $request->productId;
        $year = $request->year;

        $startDate = Carbon::create($year)->startOfYear();
        $endDate = Carbon::create($year)->endOfYear();

        $stockIn = \App\Models\purchase_details::join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->where('purchase_details.product_id', $productId)
            ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
            ->where('purchases.purchase_status', 'complete')
            ->select('purchases.purchase_date as transaction_date', 'purchase_details.quantity', 'purchases.invoice_no as reference')
            ->selectRaw("'Stock In' as transaction_type");

        $stockOut = \App\Models\OrderDetails::join('orders', 'orders.id', '=', 'orderdetails.order_id')
            ->where('orderdetails.product_id', $productId)
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('orders.order_status', 'complete')
            ->select('orders.order_date as transaction_date', 'orderdetails.quantity', 'orders.invoice_no as reference')
            ->selectRaw("'Stock Out' as transaction_type");

        $transactions = $stockIn->unionAll($stockOut)
                                 ->orderBy('transaction_date', 'asc')
                                 ->get();
        
        return response()->json($transactions);
    }


}