<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockByMonthExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $month;
    protected $search;

    public function __construct(string $month, ?string $search)
    {
        $this->month = $month; // e.g., "2025-07"
        $this->search = $search;
    }

    public function collection()
    {
        $startDate = Carbon::parse($this->month)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($this->month)->endOfMonth()->format('Y-m-d');

        // Copy same logic as Day export but change date filters to BETWEEN startDate & endDate
        $query = Product::query()
            ->select('id','product_name','product_code')
            ->addSelect([
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM purchase_details INNER JOIN purchases ON purchases.id = purchase_details.purchase_id WHERE purchase_details.product_id=products.id AND DATE(purchases.purchase_date) BETWEEN '{$startDate}' AND '{$endDate}' AND purchases.purchase_status='complete') as purchase_in"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='sale_return' AND DATE(created_at) BETWEEN '{$startDate}' AND '{$endDate}') as sale_return_in"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM orderdetails INNER JOIN orders ON orders.id = orderdetails.order_id WHERE orderdetails.product_id=products.id AND DATE(orders.order_date) BETWEEN '{$startDate}' AND '{$endDate}' AND orders.order_status='complete') as sale_out"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='purchase_return' AND DATE(created_at) BETWEEN '{$startDate}' AND '{$endDate}') as purchase_return_out"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='clear_stock' AND DATE(created_at) BETWEEN '{$startDate}' AND '{$endDate}') as clear_stock_out"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM purchase_details INNER JOIN purchases ON purchases.id = purchase_details.purchase_id WHERE purchase_details.product_id=products.id AND DATE(purchases.purchase_date)< '{$startDate}' AND purchases.purchase_status='complete') as total_purchased_before"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM orderdetails INNER JOIN orders ON orders.id = orderdetails.order_id WHERE orderdetails.product_id=products.id AND DATE(orders.order_date)< '{$startDate}' AND orders.order_status='complete') as total_sold_before"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='sale_return' AND DATE(created_at)< '{$startDate}') as total_sale_return_before"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='purchase_return' AND DATE(created_at)< '{$startDate}') as total_purchase_return_before"),
                DB::raw("(SELECT COALESCE(SUM(quantity),0) FROM stock_adjustments WHERE product_id=products.id AND type='clear_stock' AND DATE(created_at)< '{$startDate}') as total_clear_stock_before"),
            ])
            ->havingRaw('
                (purchase_in + sale_return_in) > 0
                OR (sale_out + purchase_return_out + clear_stock_out) > 0
                OR ((total_purchased_before + total_sale_return_before) - (total_sold_before + total_purchase_return_before + total_clear_stock_before)) <> 0
            ');

        if ($this->search) {
            $query->where(function($q){
                $q->where('product_name','like',"%{$this->search}%")
                  ->orWhere('product_code','like',"%{$this->search}%");
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
        $totalIn = (int)$product->purchase_in + (int)$product->sale_return_in;
        $totalOut = (int)$product->sale_out + (int)$product->purchase_return_out + (int)$product->clear_stock_out;
        $closingStock = $openingStock + $totalIn - $totalOut;

        return [
            $product->product_code,
            $product->product_name,
            $openingStock,
            (int)$product->purchase_in,
            (int)$product->sale_return_in,
            $totalIn,
            (int)$product->sale_out,
            (int)$product->purchase_return_out,
            (int)$product->clear_stock_out,
            $totalOut,
            $closingStock,
        ];
    }
}
