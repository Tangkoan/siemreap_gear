<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\purchase_details;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockByYearExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $year;
    protected $search;

    public function __construct(string $year, ?string $search)
    {
        $this->year = $year;
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $startDate = Carbon::create($this->year)->startOfYear();
        $endDate = Carbon::create($this->year)->endOfYear();
        
        $query = Product::query()
            ->select('id', 'product_name', 'product_code')
            ->addSelect([
                'stock_in' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')->whereBetween('purchases.purchase_date', [$startDate, $endDate])->where('purchases.purchase_status', 'complete'),
                'stock_out' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')->whereBetween('orders.order_date', [$startDate, $endDate])->where('orders.order_status', 'complete'),
                'total_purchased_before' => purchase_details::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                    ->whereColumn('purchase_details.product_id', 'products.id')->where('purchases.purchase_date', '<', $startDate)->where('purchases.purchase_status', 'complete'),
                'total_sold_before' => OrderDetails::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')->join('orders', 'orders.id', '=', 'orderdetails.order_id')
                    ->whereColumn('orderdetails.product_id', 'products.id')->where('orders.order_date', '<', $startDate)->where('orders.order_status', 'complete'),
            ])
            ->havingRaw('stock_in > 0 OR stock_out > 0 OR (total_purchased_before - total_sold_before) > 0');

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
            'Stock In',
            'Stock Out',
            'Closing Stock',
        ];
    }

    public function map($product): array
    {
        // $openingStock = (int)$product->total_purchased_before - (int)$product->total_sold_before;
        $openingStock = (int)$product->total_purchased_before ;
        $stockIn = (int)$product->stock_in;
        $stockOut = (int)$product->stock_out;
        $closingStock = $openingStock + $stockIn - $stockOut;

        return [
            $product->product_code,
            $product->product_name,
            $openingStock,
            $stockIn,
            $stockOut,
            $closingStock,
        ];
    }
}