<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\purchase_details;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockByDayExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $date;
    protected $search;

    public function __construct(string $date, ?string $search)
    {
        $this->date = Carbon::parse($date)->startOfDay();
        $this->search = $search;
    }

    public function collection()
    {
        $dateString = $this->date->format('Y-m-d');

        $query = Product::query()
    ->select('id', 'product_name', 'product_code')
    ->addSelect([
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM purchase_details
            INNER JOIN purchases ON purchases.id = purchase_details.purchase_id
            WHERE purchase_details.product_id = products.id
              AND DATE(purchases.purchase_date) = '{$dateString}'
              AND purchases.purchase_status = 'complete'
        ) as purchase_in"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'sale_return'
              AND DATE(created_at) = '{$dateString}'
        ) as sale_return_in"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM orderdetails
            INNER JOIN orders ON orders.id = orderdetails.order_id
            WHERE orderdetails.product_id = products.id
              AND DATE(orders.order_date) = '{$dateString}'
              AND orders.order_status = 'complete'
        ) as sale_out"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'purchase_return'
              AND DATE(created_at) = '{$dateString}'
        ) as purchase_return_out"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'clear_stock'
              AND DATE(created_at) = '{$dateString}'
        ) as clear_stock_out"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM purchase_details
            INNER JOIN purchases ON purchases.id = purchase_details.purchase_id
            WHERE purchase_details.product_id = products.id
              AND DATE(purchases.purchase_date) < '{$dateString}'
              AND purchases.purchase_status = 'complete'
        ) as total_purchased_before"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM orderdetails
            INNER JOIN orders ON orders.id = orderdetails.order_id
            WHERE orderdetails.product_id = products.id
              AND DATE(orders.order_date) < '{$dateString}'
              AND orders.order_status = 'complete'
        ) as total_sold_before"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'sale_return'
              AND DATE(created_at) < '{$dateString}'
        ) as total_sale_return_before"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'purchase_return'
              AND DATE(created_at) < '{$dateString}'
        ) as total_purchase_return_before"),
        DB::raw("(
            SELECT COALESCE(SUM(quantity),0)
            FROM stock_adjustments
            WHERE product_id = products.id
              AND type = 'clear_stock'
              AND DATE(created_at) < '{$dateString}'
        ) as total_clear_stock_before"),
    ])
    // ប្រើ full expressions នៅក្នុង havingRaw មិន rely លើ alias
    ->havingRaw('
        (
            (SELECT COALESCE(SUM(quantity),0) FROM purchase_details INNER JOIN purchases ON purchases.id = purchase_details.purchase_id WHERE purchase_details.product_id = products.id AND DATE(purchases.purchase_date) = "'.$dateString.'" AND purchases.purchase_status = "complete")
            +
            (SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id = products.id AND type = "sale_return" AND DATE(created_at) = "'.$dateString.'")
        ) > 0
        OR
        (
            (SELECT COALESCE(SUM(quantity),0) FROM orderdetails INNER JOIN orders ON orders.id = orderdetails.order_id WHERE orderdetails.product_id = products.id AND DATE(orders.order_date) = "'.$dateString.'" AND orders.order_status = "complete")
            +
            (SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id = products.id AND type IN ("purchase_return","clear_stock") AND DATE(created_at) = "'.$dateString.'")
        ) > 0
        OR
        (
            (
                (SELECT COALESCE(SUM(quantity),0) FROM purchase_details INNER JOIN purchases ON purchases.id = purchase_details.purchase_id WHERE purchase_details.product_id = products.id AND DATE(purchases.purchase_date) < "'.$dateString.'" AND purchases.purchase_status = "complete")
                +
                (SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id = products.id AND type = "sale_return" AND DATE(created_at) < "'.$dateString.'")
            )
            -
            (
                (SELECT COALESCE(SUM(quantity),0) FROM orderdetails INNER JOIN orders ON orders.id = orderdetails.order_id WHERE orderdetails.product_id = products.id AND DATE(orders.order_date) < "'.$dateString.'" AND orders.order_status = "complete")
                +
                (SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id = products.id AND type IN ("purchase_return","clear_stock") AND DATE(created_at) < "'.$dateString.'")
            )
        ) <> 0
    ');

if ($this->search) {
    $query->where(function($q) {
        $q->where('product_name', 'like', "%{$this->search}%")
          ->orWhere('product_code', 'like', "%{$this->search}%");
    });
}

return $query->get();

    }

    public function headings(): array
    {
        return [
            'Product Code',
            'Product Name',
            'Opening Stock',
            'Purchase In',
            'Sale Return In',
            'Total In',
            'Sale Out',
            'Purchase Return Out',
            'Clear Stock Out',
            'Total Out',
            'Closing Stock',
        ];
    }

    public function map($product): array
    {
        $openingStock = (int)$product->total_purchased_before + (int)$product->total_sale_return_before - (int)$product->total_sold_before - (int)$product->total_purchase_return_before - (int)$product->total_clear_stock_before;

        $stockIn = (int)$product->purchase_in;
        $saleReturnIn = (int)$product->sale_return_in;
        $totalIn = $stockIn + $saleReturnIn;

        $saleOut = (int)$product->sale_out;
        $purchaseReturnOut = (int)$product->purchase_return_out;
        $clearStockOut = (int)$product->clear_stock_out;
        $totalOut = $saleOut + $purchaseReturnOut + $clearStockOut;

        $closingStock = $openingStock + $totalIn - $totalOut;

        return [
            $product->product_code,
            $product->product_name,
            $openingStock,
            $stockIn,
            $saleReturnIn,
            $totalIn,
            $saleOut,
            $purchaseReturnOut,
            $clearStockOut,
            $totalOut,
            $closingStock,
        ];
    }
}
