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


// Export Sale(Order)
use App\Exports\OrdersByDateExport;
use App\Exports\OrdersByMonthExport;
use App\Exports\OrdersByYearExport; 

use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    
    public function AllReports(){
        return view('admin.report.sale.order_report');
    }
    // End Method 

    public function getOrderDetails(Request $request)
    {
        $orderId = $request->input('order_id');

        // ✅ ទាញយកព័ត៌មាន Order ទាំងមូល រួមទាំង Customer និង orderDetails ជាមួយ Product
        $order = Order::with(['customer', 'orderDetails.product'])
                    ->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // ✅ បញ្ជូនទិន្នន័យជា JSON ដែលមាន  order និង orderItems
        return response()->json([
            'order' => $order,
            'orderDetails' => $order->orderDetails
        ]);
    }

    public function SaleReportExport(Request $request){
        $date = $request->date;
        $month = $request->month;
        $year = $request->year;
    
        return Excel::download(new OrderReportExport($date, $month, $year), 'filtered_report.xlsx');
        // return Excel::download(new OrderReportExport,'sale_report.xlsx');
    }
    
    // Order Report Function
        public function orderReportByDate(Request $request)
        {
            // សម្រាប់ Initial page load
            if (!$request->ajax() && !$request->has('export')) {
                $date = $request->input('date', Carbon::now()->format('Y-m-d'));
                $formattedDate = Carbon::parse($date)->format('d F Y');
                return view('admin.report.sale.order_report_by_date', compact('date', 'formattedDate'));
            }

            $date = $request->input('date', Carbon::now()->format('Y-m-d'));
            $search = $request->input('search');
            $perPage = $request->input('perPage', 10);

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
            
            // --- AJAX Response ---
            $orders = ($perPage === 'all') ? $query->get() : $query->paginate((int)$perPage);
            
            $table = '';
            $totalAmount = 0;

            if ($orders->isEmpty()) {
                $table = '<tr><td colspan="7" class="text-center p-4">No orders found for this date.</td></tr>';
            } else {
                foreach ($orders as $key => $item) {
                    $totalAmount += $item->total;
                    $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
                    $table .= '<td class="p-4">' . ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator ? $orders->firstItem() + $key : $key + 1) . '</td>';
                    $table .= '<td class="p-4">' . Carbon::parse($item->order_date)->format('d-m-Y') . '</td>';
                    $table .= '<td class="p-4">' . $item->invoice_no . '</td>';
                    $table .= '<td class="p-4">' . ($item->customer->name ?? 'N/A') . '</td>';
                    $table .= '<td class="p-4">$' . number_format($item->total, 2) . '</td>';
                    $table .= '<td class="p-4">' . $item->payment_status . '</td>';
                    
                    // ✅ កែសម្រួលត្រង់នេះ៖ ប្តូរពី <a> tag ទៅជា <button> សម្រាប់បើក Modal
                    $table .= '<td class="p-4 text-center">
                                <button type="button" 
                                        class="view-details-btn text-blue-500 hover:text-blue-700 font-semibold"
                                        data-order-id="' . $item->id . '">
                                    View
                                </button>
                            </td>';
                    $table .= '</tr>';
                }
            }

            $footer = '<tr>';
            $footer .= '<td colspan="4" class="px-4 py-3 text-right font-semibold">Total Orders: ' . $orders->total() . '</td>';
            $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
            $footer .= '</tr>';

            return response()->json([
                'table' => $table,
                'footer' => $footer,
                'pagination' => ($perPage === 'all') ? '' : $orders->links()->toHtml(),
                'formattedDate' => Carbon::parse($date)->format('d F Y')
            ]);
        }
        // ✅ Method ថ្មីសម្រាប់ Export
        public function exportOrderByDate(Request $request)
        {
            $date = $request->input('date', Carbon::now()->format('Y-m-d'));
            $search = $request->input('search', null);
            $fileName = 'orders-report-' . $date . '.xlsx';
            return Excel::download(new OrdersByDateExport($date, $search), $fileName);
        }
        public function getOrderDetailsForModal($id)
        {
            // Eager load a relations to get all data in one query
            $order = Order::with('customer', 'orderDetails.product')
                        ->findOrFail($id);
            
            return response()->json($order);
        }
        public function orderReportByMonth(Request $request)
        {
            // សម្រាប់ Initial page load
            if (!$request->ajax()) {
                $month = $request->input('month', Carbon::now()->format('Y-m'));
                $formattedDate = Carbon::parse($month)->format('F Y');
                return view('admin.report.sale.order_report_by_month', compact('month', 'formattedDate'));
            }

            // --- AJAX Response ---
            $monthInput = $request->input('month', Carbon::now()->format('Y-m'));
            $search = $request->input('search');

            // ប្រើ whereBetween ដើម្បីឱ្យ Query លឿន
            $startDate = Carbon::parse($monthInput)->startOfMonth();
            $endDate = Carbon::parse($monthInput)->endOfMonth();

            $query = Order::with('customer')
                ->whereBetween('order_date', [$startDate, $endDate])
                ->orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // គណនា Total Amount ពីទិន្នន័យដែលបាន filter
            $totalAmountQuery = clone $query;
            $totalAmount = $totalAmountQuery->sum('total');
            
            // ប្រើ get() ដើម្បីទាញយកទិន្នន័យទាំងអស់
            $orders = $query->get();
            
            $table = '';
            if ($orders->isEmpty()) {
                $table = '<tr><td colspan="7" class="text-center p-4">No orders found for this month.</td></tr>';
            } else {
                foreach ($orders as $key => $item) {
                    $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
                    $table .= '<td class="p-4">' . ($key + 1) . '</td>';
                    $table .= '<td class="p-4">' . Carbon::parse($item->order_date)->format('d-m-Y') . '</td>';
                    $table .= '<td class="p-4">' . e($item->invoice_no) . '</td>';
                    $table .= '<td class="p-4">' . e($item->customer->name ?? 'N/A') . '</td>';
                    $table .= '<td class="p-4">$' . number_format($item->total, 2) . '</td>';
                    $table .= '<td class="p-4">' . e($item->payment_status) . '</td>';
                    $table .= '<td class="p-4 text-center"><button type="button" class="view-details-btn text-blue-500 hover:text-blue-700 font-semibold" data-order-id="' . $item->id . '">View</button></td>';
                    $table .= '</tr>';
                }
            }

            // បង្កើត Footer ឡើងវិញ
            $footer = '<tr>';
            $footer .= '<td colspan="4" class="px-4 py-3 text-right font-semibold">Total Orders: ' . $orders->count() . '</td>';
            $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
            $footer .= '</tr>';

            return response()->json([
                'table' => $table,
                'footer' => $footer,
                'formattedDate' => Carbon::parse($monthInput)->format('F Y')
            ]);
        }
        // ✅ Method ថ្មីសម្រាប់ Export
        public function exportOrderByMonth(Request $request)
        {
            $month = $request->input('month', Carbon::now()->format('Y-m'));
            $search = $request->input('search', null);
            $fileName = 'orders-report-' . $month . '.xlsx';
            return Excel::download(new OrdersByMonthExport($month, $search), $fileName);
        }

        public function orderReportByYear(Request $request)
        {
            if (!$request->ajax()) {
                $year = $request->input('year', Carbon::now()->format('Y'));
                return view('admin.report.sale.order_report_by_year', compact('year'));
            }

            $year = $request->input('year', Carbon::now()->format('Y'));
            $search = $request->input('search');

            $startDate = Carbon::createFromDate($year)->startOfYear();
            $endDate = Carbon::createFromDate($year)->endOfYear();

            $query = Order::with('customer')
                ->whereBetween('order_date', [$startDate, $endDate])
                ->orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            }

            $totalAmountQuery = clone $query;
            $totalAmount = $totalAmountQuery->sum('total');
            $orders = $query->get();
            
            $table = '';
            if ($orders->isEmpty()) {
                $table = '<tr><td colspan="7" class="text-center p-4">No orders found for this year.</td></tr>';
            } else {
                foreach ($orders as $key => $item) {
                    $table .= '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800">';
                    $table .= '<td class="p-4">' . ($key + 1) . '</td>';
                    $table .= '<td class="p-4">' . Carbon::parse($item->order_date)->format('d-m-Y') . '</td>';
                    $table .= '<td class="p-4">' . e($item->invoice_no) . '</td>';
                    $table .= '<td class="p-4">' . e($item->customer->name ?? 'N/A') . '</td>';
                    $table .= '<td class="p-4">$' . number_format($item->total, 2) . '</td>';
                    $table .= '<td class="p-4">' . e($item->payment_status) . '</td>';
                    $table .= '<td class="p-4 text-center"><button type="button" class="view-details-btn text-blue-500 hover:text-blue-700 font-semibold" data-order-id="' . $item->id . '">View</button></td>';
                    $table .= '</tr>';
                }
            }

            $footer = '<tr>';
            $footer .= '<td colspan="4" class="px-4 py-3 text-right font-semibold">Total Orders: ' . $orders->count() . '</td>';
            $footer .= '<td colspan="3" class="px-4 py-3 text-right font-semibold">Total Amount: $' . number_format($totalAmount, 2) . '</td>';
            $footer .= '</tr>';

            return response()->json(['table' => $table, 'footer' => $footer, 'formattedDate' => $year]);
        }

        public function exportOrderByYear(Request $request)
        {
            $year = $request->input('year', Carbon::now()->format('Y'));
            $search = $request->input('search', null);
            $fileName = 'orders-report-' . $year . '.xlsx';
            return Excel::download(new OrdersByYearExport($year, $search), $fileName);
        }// End
    
    




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


















}