<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product; // ត្រូវប្រាកដថាបានហៅប្រើ Model Product
use App\Models\Orderdetails;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    /**
     * បង្ហាញទំព័រ Dashboard ជាមួយនឹងទិន្នន័យទាំងអស់
     */
    public function index()
    {
        // === ផ្នែកទី១៖ ការគណនាសម្រាប់បង្ហាញលើកាត (KPI Cards) ===

        //--- កាតចំណូល (Revenue Cards) ---
        $todays_revenue = Order::whereDate('order_date', today())->sum('total'); // ចំណូលសរុបសម្រាប់ថ្ងៃនេះ
        $yesterdays_revenue = Order::whereDate('order_date', today()->subDay())->sum('total'); // ចំណូលសរុបសម្រាប់ម្សិលមិញ (ដើម្បីប្រៀបធៀប)
        $this_month_revenue = Order::whereYear('order_date', now()->year)->whereMonth('order_date', now()->month)->sum('total'); // ចំណូលសរុបខែនេះ
        $this_year_revenue = Order::whereYear('order_date', now()->year)->sum('total'); // ចំណូលសរុបឆ្នាំនេះ

        //--- កាតទូទាត់ (Payment Cards) ---
        $total_paid = Order::sum('pay'); // ចំនួនទឹកប្រាក់ដែលបានបង់សរុប
        $total_due = Order::sum('due');  // ចំនួនទឹកប្រាក់ដែលជំពាក់សរុប

        //--- កាតស្ថានភាពការកម្ម៉ង់ (Order Status Cards) ---
        $complete_orders_count = Order::where('order_status', 'complete')->count(); // ចំនួនការកម្ម៉ង់
        $pending_orders_count = Order::where('order_status', 'pending')->count();  // ចំនួនការកម្ម៉ង់កំពុងរង់ចាំ
        $pre_orders_count = Order::where('order_type', 'pre_order')->count(); // ចំនួនការកម្ម៉ង់ប្រភេទ Pre-order

        // គណនាភាគរយនៃការប្រែប្រួលចំណូលថ្ងៃនេះធៀបនឹងម្សិលមិញ
        $revenue_change = 0;
        if ($yesterdays_revenue > 0) {
            // បើម្សិលមិញមានចំណូល គណនាភាគរយតាមរូបមន្តធម្មតា
            $revenue_change = (($todays_revenue - $yesterdays_revenue) / $yesterdays_revenue) * 100;
        } elseif ($todays_revenue > 0) {
            // បើម្សិលមិញគ្មានចំណូល (0) តែថ្ងៃនេះមាន ឲ្យបង្ហាញកើន 100%
            $revenue_change = 100;
        }

        // === ផ្នែកទី២៖ ទិន្នន័យសម្រាប់តារាងការកម្ម៉ង់ថ្មីៗ (Recent Orders Table) ===
        $recent_orders = Order::with('customer') // 'with' គឺដើម្បីទាញយក Customer (Eager Loading)
                                ->latest('order_date') // តម្រៀបតាមថ្ងៃទីកម្ម៉ង់ថ្មីមុនគេ
                                ->take(5) // យកតែ 5 
                                ->get();


        // === ផ្នែកទី៣៖ ការរៀបចំទិន្នន័យសម្រាប់តារាងក្រាហ្វិក (Charts) ===

        // --- a) តារាងក្រាហ្វិកខ្សែរបង្ហាញនិន្នាការលក់ ៣០ថ្ងៃចុងក្រោយ (Sales Trend Line Chart) ---
        $sales_raw_data = Order::select(
            DB::raw('DATE(order_date) as date'),      // យក
            DB::raw('SUM(total) as total_sales') // បូកសរុប
        )
        ->where('order_date', '>=', now()->subDays(29)) // ទិន្នន័យចាប់ពី 29 ថ្ងៃមុនរហូតដល់ថ្ងៃនេះ
        ->groupBy('date')    // បូកសរុបតាមកាលបរិច្ឆេទនីមួយៗ
        ->orderBy('date', 'asc') // តម្រៀបតាមកាលបរិច្ឆេទ
        ->pluck('total_sales', 'date'); // បង្កើតជា Array (key: date, value: total_sales)

        // បង្កើត Label (ថ្ងៃខែ) និង Values (ទាញ5) សម្រាប់គ្រប់ថ្ងៃក្នុងរយៈពេល 30 ថ្ងៃ (ដើម្បីកុំឲ្យថ្ងៃបាត់ពី Chart)
        $period = CarbonPeriod::create(now()->subDays(29), now());
        $sales_chart_labels = [];
        $sales_chart_values = [];
        foreach ($period as $date) {
            $formatted_date_key = $date->format('Y-m-d'); // ទម្រង់ Y-m-d សម្រាប់រកមើលក្នុង $sales_raw_data
            $formatted_date_label = $date->format('M d');   // ទម្រង់ M d (e.g., Jul 28) សម្រាប់បង្ហាញលើ Chart
            $sales_chart_labels[] = $formatted_date_label;
            $sales_chart_values[] = $sales_raw_data->get($formatted_date_key, 0); // បើថ្ងៃ ឲ្យតម្លៃ0
        }
        $sales_chart_data = ['labels' => $sales_chart_labels, 'data' => $sales_chart_values];

        // --- b) តារាងក្រាហ្វិកបង្ហាញការបែងចែកស្ថានភាពការកម្ម៉ង់ (Order Distribution Doughnut Chart) ---
        $order_status_distribution = [
            'labels' => ['Complete', 'Pending', 'Pre-Orders'],
            'data' => [$complete_orders_count, $pending_orders_count, $pre_orders_count]
        ];

        // --- c) តារាងក្រាហ្វិកបង្ហាញផលិតផលលក់ដាច់ជាងគេ (Best Selling Products Bar Chart) ---
        $best_selling_products = DB::table('orderdetails')
            ->join('products', 'orderdetails.product_id', '=', 'products.id') // ភ្ជាប់ទៅតារាង products ដើម្បីយកឈ្មោះផលិតផល
            ->select('products.product_name', DB::raw('SUM(orderdetails.quantity) as total_quantity')) // បូកសរុបបាន
            ->groupBy('products.id', 'products.product_name')
            ->orderByDesc('total_quantity') // តម្រៀបតាមច្រើនជាងគេមុន
            ->take(5) // យកតែ 5 ផលិតផល
            ->get();
        $best_selling_chart_data = [
            'labels' => $best_selling_products->pluck('product_name'),
            'data' => $best_selling_products->pluck('total_quantity'),
        ];

        // --- d) តារាងក្រាហ្វិកបង្ហាញនិន្នាការលក់ប្រចាំខែក្នុងឆ្នាំនេះ (Monthly Sales Trend Bar Chart) ---
        $this_year = now()->year;
        $this_year_monthly_sales_raw = Order::select(
            DB::raw('MONTH(order_date) as month'),
            DB::raw('SUM(total) as total_sales')
        )
        ->whereYear('order_date', $this_year)
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->pluck('total_sales', 'month');

        $this_year_monthly_chart_labels = [];
        $this_year_monthly_chart_values = [];
        for ($month_num = 1; $month_num <= 12; $month_num++) {
            $this_year_monthly_chart_labels[] = Carbon::create()->month($month_num)->format('M'); // បង្កើត Label ខែ (Jan, Feb, ...)
            $this_year_monthly_chart_values[] = $this_year_monthly_sales_raw->get($month_num, 0);
        }
        $this_year_monthly_chart_data = [
            'labels' => $this_year_monthly_chart_labels,
            'data' => $this_year_monthly_chart_values,
            'year' => $this_year,
        ];

        // --- e) តារាងក្រាហ្វិកបង្ហាញផលិតផលដែលមានស្តុកទាប (Low Stock Products Bar Chart) ---
        // កំណត់ចំណាំ៖ សូមប្រាកដថា Model 'Product' ត្រូវបានហៅប្រើនៅផ្នែកខាងលើ (use App\Models\Product;)
        $low_stock_products = Product::where('product_store', '>', 0) // យកតែផលិតផលដែលនៅមានក្នុងស្តុក (ចំនួន > 0)
                                ->orderBy('product_store', 'asc')   // តម្រៀបតាមចំនួនស្តុកពីទាបទៅខ្ពស់
                                ->take(5) // យកតែ 5 ផលិតផល
                                ->get();
        
        // កំណត់ចំណាំ៖ សូមប្រាកដថា 'product_store' គឺជាឈ្មោះ column ត្រឹមត្រូវនៅក្នុងតារាង 'products' របស់អ្នក
        $low_stock_products_chart_data = [
            'labels' => $low_stock_products->pluck('product_name'),
            'data'   => $low_stock_products->pluck('product_store'),
        ];

        // === ផ្នែកទី៤៖ បញ្ជូនទិន្នន័យទាំងអស់ទៅកាន់ View ===
        return view('admin.index', compact(
            'todays_revenue',
            'revenue_change',
            'this_month_revenue',
            'this_year_revenue',
            'total_paid',
            'total_due',
            'complete_orders_count',
            'recent_orders',
            'sales_chart_data',
            'order_status_distribution',
            'best_selling_chart_data',
            'this_year_monthly_chart_data',
            'low_stock_products_chart_data' // បញ្ជូនទិន្នន័យស្តុកទាបទៅ View
        ));
    }
}