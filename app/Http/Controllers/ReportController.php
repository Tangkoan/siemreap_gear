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
use App\Models\Expense;







use Carbon\Carbon;
use DateTime;


use App\Exports\ProductExport;


// បន្ថែម use statement នេះនៅផ្នែកខាងលើនៃឯកសារ
use App\Exports\StockByDayExport;
use App\Exports\StockByMonthExport;
use App\Exports\StockByYearExport;

// Report Purchase
use App\Exports\PurchasesByDateExport;
use App\Exports\PurchasesByMonthExport;
use App\Exports\PurchasesByYearExport;

// New
use App\Exports\PurchasesReportExport;

// Report Income & Outcome
use App\Exports\IncomeExpenseReportExport;


use App\Exports\IncomeExpenseExport;
use Barryvdh\DomPDF\Facade\Pdf;


// Export Sale(Order)
use App\Exports\OrdersByDateExport;
use App\Exports\OrdersByMonthExport;
use App\Exports\OrdersByYearExport; 

use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    
    public function AllReports()
    {
        return view('admin.report.sale.order_report');
    }

    /**
     * Get order details for the modal
     */
    public function getOrderDetails(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::with(['customer', 'orderDetails.product'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => $order,
            'orderDetails' => $order->orderDetails
        ]);
    }

    // ===============================================
    // ✨ NEW REFACTORED REPORT GENERATION METHODS
    // ===============================================

    public function orderReportByDate(Request $request)
    {
        return $this->generateReport($request, 'day');
    }

    public function orderReportByMonth(Request $request)
    {
        return $this->generateReport($request, 'month');
    }

    public function orderReportByYear(Request $request)
    {
        return $this->generateReport($request, 'year');
    }

    private function generateReport(Request $request, string $period)
    {
        $query = $this->buildReportQuery($request, $period);

        // --- Calculate KPIs before pagination ---
        $kpiQuery = clone $query;
        $kpiData = $kpiQuery->get();

        $totalRevenue = $kpiData->sum('total');
        $totalOrders = $kpiData->count();
        $orderIds = $kpiData->pluck('id');
        $itemsSold = OrderDetails::whereIn('order_id', $orderIds)->sum('quantity');
        $avgOrderValue = ($totalOrders > 0) ? $totalRevenue / $totalOrders : 0;
        // --- End KPI Calculation ---

        $orders = $query->paginate(15); // Show 15 items per page

        $tableHtml = '';
        if ($orders->isEmpty()) {
            $tableHtml = '<tr><td colspan="7" class="text-center p-8 text-slate-500">No orders found for this period.</td></tr>';
        } else {
            foreach ($orders as $key => $item) {
                $tableHtml .= view('admin.report.sale.partials._order_row', ['item' => $item, 'key' => $key, 'orders' => $orders])->render();
            }
        }

        $footerHtml = view('admin.report.sale.partials._report_footer', ['totalOrders' => $orders->total(), 'totalAmount' => $totalRevenue])->render();

        return response()->json([
            'table' => $tableHtml,
            'footer' => $footerHtml,
            'pagination' => $orders->links()->toHtml(),
            'formattedDate' => $this->getFormattedDate($request, $period),
            'kpis' => [ // ✨ NEW: Sending KPI data to the frontend
                'revenue' => '$' . number_format($totalRevenue, 2),
                'orders' => number_format($totalOrders),
                'items' => number_format($itemsSold),
                'avg' => '$' . number_format($avgOrderValue, 2),
            ]
        ]);
    }

    private function buildReportQuery(Request $request, string $period)
    {
        $query = Order::with('customer')->latest();
        $search = $request->input('search');

        switch ($period) {
            case 'day':
                $date = $request->input('date', Carbon::now()->format('Y-m-d'));
                $query->whereDate('order_date', $date);
                break;
            case 'month':
                $monthInput = $request->input('month', Carbon::now()->format('Y-m'));
                $query->whereYear('order_date', Carbon::parse($monthInput)->year)
                      ->whereMonth('order_date', Carbon::parse($monthInput)->month);
                break;
            case 'year':
                $year = $request->input('year', Carbon::now()->format('Y'));
                $query->whereYear('order_date', $year);
                break;
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    private function getFormattedDate(Request $request, string $period)
    {
        switch ($period) {
            case 'day':
                return Carbon::parse($request->input('date', Carbon::now()->format('Y-m-d')))->format('d F Y');
            case 'month':
                return Carbon::parse($request->input('month', Carbon::now()->format('Y-m')))->format('F Y');
            case 'year':
                return $request->input('year', Carbon::now()->format('Y'));
        }
    }

    // ===============================================
    // EXPORT METHODS (Unchanged)
    // ===============================================
    public function exportOrderByDate(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $search = $request->input('search', null);
        return Excel::download(new OrdersByDateExport($date, $search), 'orders-report-' . $date . '.xlsx');
    }

    public function exportOrderByMonth(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $search = $request->input('search', null);
        return Excel::download(new OrdersByMonthExport($month, $search), 'orders-report-' . $month . '.xlsx');
    }

    public function exportOrderByYear(Request $request)
    {
        $year = $request->input('year', Carbon::now()->format('Y'));
        $search = $request->input('search', null);
        return Excel::download(new OrdersByYearExport($year, $search), 'orders-report-' . $year . '.xlsx');
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

    // បន្ថែម Method ថ្មីនេះទៅក្នុង ReportController
    public function exportStockByDay(Request $request)
    {
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $search = $request->input('search', null);

        $fileName = 'stock-report-' . $date . '.xlsx';

        return Excel::download(new StockByDayExport($date, $search), $fileName);
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

    // បន្ថែម Method ថ្មីនេះទៅក្នុង ReportController
    public function exportStockByMonth(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $search = $request->input('search', null);

        $fileName = 'stock-report-' . $month . '.xlsx';

        return Excel::download(new StockByMonthExport($month, $search), $fileName);
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

    // ✅ Method ថ្មីសម្រាប់ Export
    public function exportStockByYear(Request $request)
    {
        $year = $request->input('year', Carbon::now()->format('Y'));
        $search = $request->input('search', null);
        $fileName = 'stock-report-' . $year . '.xlsx';
        return Excel::download(new StockByYearExport($year, $search), $fileName);
    }


    // Start Purchase Function
        /**
         * Display the main view for the purchase report.
         */
        public function purchaseReportView()
        {
            return view('admin.report.purchase.purchase_report');
        }

        /**
         * Fetch purchase report data by a specific date.
         */
        public function getPurchaseReportByDate(Request $request)
        {
            $date = $request->input('date', now()->format('Y-m-d'));
            $search = $request->input('search');

            $query = Purchase::with('supplier')
                ->whereDate('purchase_date', $date);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                });
            }
            
            $purchases = $query->latest()->get();

            $tableHtml = view('admin.report.purchase.partials._purchase_table_rows', ['purchases' => $purchases])->render();
            $footerHtml = view('admin.report.purchase.partials._purchase_table_footer', ['purchases' => $purchases])->render();

            return response()->json([
                'table' => $tableHtml,
                'footer' => $footerHtml,
                'formattedDate' => Carbon::parse($date)->format('d F Y')
            ]);
        }

        /**
         * Fetch purchase report data by a specific month.
         */
        public function getPurchaseReportByMonth(Request $request)
        {
            $month = $request->input('month', now()->format('Y-m'));
            $carbonMonth = Carbon::parse($month);
            $search = $request->input('search');

            $query = Purchase::with('supplier')
                ->whereYear('purchase_date', $carbonMonth->year)
                ->whereMonth('purchase_date', $carbonMonth->month);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                });
            }
            
            $purchases = $query->latest()->get();

            $tableHtml = view('admin.report.purchase.partials._purchase_table_rows', ['purchases' => $purchases])->render();
            $footerHtml = view('admin.report.purchase.partials._purchase_table_footer', ['purchases' => $purchases])->render();

            return response()->json([
                'table' => $tableHtml,
                'footer' => $footerHtml,
                'formattedDate' => $carbonMonth->format('F Y')
            ]);
        }

        /**
         * Fetch purchase report data by a specific year.
         */
        public function getPurchaseReportByYear(Request $request)
        {
            $year = $request->input('year', now()->year);
            $search = $request->input('search');

            $query = Purchase::with('supplier')
                ->whereYear('purchase_date', $year);

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                });
            }
            
            $purchases = $query->latest()->get();

            $tableHtml = view('admin.report.purchase.partials._purchase_table_rows', ['purchases' => $purchases])->render();
            $footerHtml = view('admin.report.purchase.partials._purchase_table_footer', ['purchases' => $purchases])->render();

            return response()->json([
                'table' => $tableHtml,
                'footer' => $footerHtml,
                'formattedDate' => $year
            ]);
        }

        /**
         * Fetch details for a single purchase for the modal view.
         */
        public function getPurchaseDetails(Request $request)
        {
            $purchaseId = $request->input('purchase_id');
            
            $purchase = Purchase::with(['supplier', 'purchaseDetails.product'])->find($purchaseId);

            if (!$purchase) {
                return response()->json(['error' => 'Purchase not found'], 404);
            }

            return response()->json([
                'purchase' => $purchase,
                'purchaseDetails' => $purchase->purchaseDetails
            ]);
        }
        // Export
        public function exportPurchasesByDate(Request $request)
        {
            $filters = $request->only(['date', 'search']);
            $filename = 'purchases-report-' . ($filters['date'] ?? now()->format('Y-m-d')) . '.xlsx';
            return Excel::download(new PurchasesReportExport($filters), $filename);
        }

        public function exportPurchasesByMonth(Request $request)
        {
            $filters = $request->only(['month', 'search']);
            $filename = 'purchases-report-' . ($filters['month'] ?? now()->format('Y-m')) . '.xlsx';
            return Excel::download(new PurchasesReportExport($filters), $filename);
        }

        public function exportPurchasesByYear(Request $request)
        {
            $filters = $request->only(['year', 'search']);
            $filename = 'purchases-report-' . ($filters['year'] ?? now()->format('Y')) . '.xlsx';
            return Excel::download(new PurchasesReportExport($filters), $filename);
        }

    // End 

    // ======================== Income & OutCome =======================
        public function incomeExpenseReportView()
        {
            return view('admin.report.income_expense.income_expense_report');
        }

        /**
         * Fetch and calculate income, expense, and profit data via AJAX.
         */
        // នៅក្នុងឯកសារ app/Http/Controllers/ReportController.php

        // 3. បង្កើត Private Function ដើម្បីទាញទិន្នន័យ (Refactor)
        private function getFilteredData(Request $request)
        {
            $type = $request->input('type', 'daily');
            $startValue = $request->input('start_value');
            $endValue = $request->input('end_value', $startValue);
        
            if (!$startValue) {
                if ($type === 'monthly') {
                    $startValue = $endValue = now()->format('Y-m');
                } else if ($type === 'yearly') {
                    $startValue = $endValue = now()->year;
                } else {
                    $startValue = $endValue = now()->format('Y-m-d');
                }
            }
            
            if (Carbon::parse($startValue)->gt(Carbon::parse($endValue))) {
                [$startValue, $endValue] = [$endValue, $startValue];
            }
        
            $salesQuery = OrderDetails::with('product', 'order');
            $purchasesQuery = purchase_details::with('product', 'purchase.supplier');
            $expensesQuery = Expense::query();
        
            $formattedDate = '';
        
            try {
                switch ($type) {
                    case 'monthly':
                        $startMonth = Carbon::parse($startValue)->startOfMonth();
                        $endMonth = Carbon::parse($endValue)->endOfMonth();
                        // សម្រាប់ Monthly, whereBetween គឺត្រឹមត្រូវហើយ
                        $salesQuery->whereHas('order', fn($q) => $q->whereBetween('order_date', [$startMonth, $endMonth]));
                        $purchasesQuery->whereHas('purchase', fn($q) => $q->whereBetween('purchase_date', [$startMonth, $endMonth]));
                        $expensesQuery->whereBetween('date', [$startMonth, $endMonth]);
                        $formattedDate = $startMonth->isSameMonth($endMonth)
                            ? $startMonth->format('F Y')
                            : $startMonth->format('F Y') . ' to ' . $endMonth->format('F Y');
                        break;
        
                    case 'yearly':
                        $startYear = Carbon::createFromDate($startValue)->startOfYear();
                        $endYear = Carbon::createFromDate($endValue)->endOfYear();
                        // សម្រាប់ Yearly, whereBetween ក៏ត្រឹមត្រូវដែរ
                        $salesQuery->whereHas('order', fn($q) => $q->whereBetween('order_date', [$startYear, $endYear]));
                        $purchasesQuery->whereHas('purchase', fn($q) => $q->whereBetween('purchase_date', [$startYear, $endYear]));
                        $expensesQuery->whereBetween('date', [$startYear, $endYear]);
                        $formattedDate = $startYear->format('Y') . ($startYear->format('Y') != $endYear->format('Y') ? ' to ' . $endYear->format('Y') : '');
                        break;
        
                    default: // daily
                        $startDate = Carbon::parse($startValue);
                        $endDate = Carbon::parse($endValue);
        
                        // ✅ កែប្រែនៅទីនេះ៖ ប្រើ whereDate សម្រាប់ความแม่นยำสูงสุด
                        $salesQuery->whereHas('order', fn($q) => $q->whereDate('order_date', '>=', $startDate)->whereDate('order_date', '<=', $endDate));
                        $purchasesQuery->whereHas('purchase', fn($q) => $q->whereDate('purchase_date', '>=', $startDate)->whereDate('purchase_date', '<=', $endDate));
                        $expensesQuery->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate);
                        
                        $formattedDate = $startDate->isSameDay($endDate)
                            ? $startDate->format('d F Y')
                            : $startDate->format('d M Y') . ' to ' . $endDate->format('d M Y');
                        break;
                }
            } catch (\Exception $e) {
                \Log::error('Income Expense Report Error: ' . $e->getMessage());
                return ['error' => 'An error occurred while processing dates.'];
            }
        
            $sales_details = $salesQuery->get();
            $purchase_details = $purchasesQuery->get();
            $other_expenses = $expensesQuery->get();
        
            $total_revenue = $sales_details->sum('total');
            $total_purchases = $purchase_details->sum('total');
            $total_other_expenses_sum = $other_expenses->sum('amount');
            $total_expenses = $total_purchases + $total_other_expenses_sum;
            $profit_or_loss = $total_revenue - $total_expenses;
        
            return [
                'sales_details' => $sales_details,
                'purchase_details' => $purchase_details,
                'other_expenses' => $other_expenses,
                'summary' => [
                    'total_revenue' => number_format($total_revenue, 2),
                    'total_purchases' => number_format($total_purchases, 2),
                    'total_other_expenses' => number_format($total_other_expenses_sum, 2),
                    'total_expenses' => number_format($total_expenses, 2),
                    'profit_or_loss' => number_format($profit_or_loss, 2),
                    'is_profit' => $profit_or_loss >= 0,
                    'formattedDate' => $formattedDate,
                ]
            ];
        }

    // កែប្រែ Function ចាស់ឲ្យប្រើ Private Function
    public function getIncomeExpenseData(Request $request)
    {
        $data = $this->getFilteredData($request);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 400);
        }

        $incomeTableHtml = view('admin.report.income_expense.partials._income_table', ['sales_details' => $data['sales_details']])->render();
        $expenseTableHtml = view('admin.report.income_expense.partials._expense_table', [
            'purchase_details' => $data['purchase_details'],
            'other_expenses' => $data['other_expenses']
        ])->render();

        // បញ្ចូល HTML ទៅក្នុង Response
        $response_data = array_merge($data['summary'], [
            'income_table_html' => $incomeTableHtml,
            'expense_table_html' => $expenseTableHtml,
        ]);
        
        return response()->json($response_data);
    }
    
    // 4. បង្កើត Function ថ្មីសម្រាប់ Export
    public function exportIncomeExpense(Request $request)
    {
        // ប្រើ private function ដដែលដើម្បីទាញយកទិន្នន័យ
        $data = $this->getFilteredData($request);

        if (isset($data['error'])) {
            // Handle error, maybe redirect back with a message
            return redirect()->back()->with('error', $data['error']);
        }

        // បង្កើតឈ្មោះไฟล์ແບບ Dynamic
        $fileName = 'Income-Expense-Report-' . str_replace(' ', '-', $data['summary']['formattedDate']) . '.xlsx';

        // ហៅ Export class ហើយបញ្ជូនទិន្នន័យទៅឲ្យវា
        return Excel::download(new IncomeExpenseExport(
            $data['sales_details'],
            $data['purchase_details'],
            $data['other_expenses'],
            $data['summary']
        ), $fileName);
    }

public function exportReport(Request $request)
    {
        // 1. ទទួល Input ពី URL
        $format = $request->query('format'); // 'excel' or 'pdf'
        $type = $request->query('type');
        $value = $request->query('value');

        // 2. ទាញទិន្នន័យពី Database (កែសម្រួលส่วนนี้ឲ្យត្រូវនឹងโครงสร้างរបស់អ្នក)
        $incomeQuery = Order::query();
        $expenseQuery = Expense::query();
        $formattedDate = '';
        

        switch ($type) {
            case 'daily':
                $incomeQuery->whereDate('created_at', $value);
                $expenseQuery->whereDate('created_at', $value);
                $formattedDate = Carbon::parse($value)->format('F j, Y');
                break;
            case 'monthly':
                $year = substr($value, 0, 4);
                $month = substr($value, 5, 2);
                $incomeQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                $expenseQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                $formattedDate = Carbon::createFromFormat('Y-m', $value)->format('F Y');
                break;
            case 'yearly':
                $incomeQuery->whereYear('created_at', $value);
                $expenseQuery->whereYear('created_at', $value);
                $formattedDate = 'Year ' . $value;
                break;
        }

        $incomes = $incomeQuery->get();
        $expenses = $expenseQuery->get();
        
        // 3. រៀបចំទិន្នន័យជា Array
        $data = [
            'incomes'       => $incomes,
            'expenses'      => $expenses,
            'totalRevenue'  => $incomes->sum('total_price'), // កែ field ឲ្យត្រូវ
            'totalExpenses' => $expenses->sum('total_price'), // កែ field ឲ្យត្រូវ
            'profitOrLoss'  => $incomes->sum('total_price') - $expenses->sum('total_price'),
            'formattedDate' => $formattedDate,
        ];
        
        $fileName = 'Report-' . str_replace([' ', ','], '-', strtolower($formattedDate));

        // 4. ពិនិត្យ Format ហើយបង្កើតไฟล์
        if ($format == 'excel') {
            return Excel::download(new IncomeExpenseReportExport($data), $fileName . '.xlsx');
        } 
        
        if ($format == 'pdf') {
            // សម្រាប់ PDF យើង render view ដោយផ្ទាល់
            $pdf = Pdf::loadView('admin.report.income_expense.export_template', ['data' => $data]);
            
            return $pdf->download($fileName . '.pdf');
        }

        return redirect()->back()->with('error', 'Invalid Format.');
    }

    // Function ថ្មីសម្រាប់ Export ជា PDF
    public function exportIncomeExpensePdf(Request $request)
    {
        // 1. ប្រើ private function ដដែលដើម្បីទាញយកទិន្នន័យ
        $data = $this->getFilteredData($request);

        if (isset($data['error'])) {
            return redirect()->back()->with('error', $data['error']);
        }
        
        // 2. បង្កើតឈ្មោះไฟล์ແບບ Dynamic
        $fileName = 'Income-Expense-Report-' . str_replace([' ', 'to'], ['-', ''], $data['summary']['formattedDate']) . '.pdf';

        // 3. Load View សម្រាប់ PDF ហើយបញ្ជូនទិន្នន័យទៅឲ្យវា
        $pdf = Pdf::loadView('admin.report.income_expense.income_expense_pdf', $data);
        

        // 4. បញ្ជាឲ្យ Browser ទាញយកไฟล์ PDF
        return $pdf->download($fileName);
    }
}