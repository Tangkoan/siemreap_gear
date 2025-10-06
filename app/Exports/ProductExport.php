<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // 1. ទាញ​យក​ទិន្នន័យ​ពី Database ជា​មុន​សិន
        $products = Product::select(
            'product_name',
            'category_id',
            'supplier_id',
            'condition_id',
            'stock_alert',
            'product_detail',
            'product_code',
            'product_image',
            'product_store',
            'buying_price',
            'selling_price',
            'status', // នៅ​តែ select status ជា​ធម្មតា
            'created_at',
            'updated_at'
        )->get();

        // ✅ START: កែប្រែ​ទិន្នន័យ​មុន​ពេល Export
        // 2. ប្រើ map() ដើម្បី​ដើរ​កាត់​គ្រប់ product នីមួយៗ
        return $products->map(function ($product) {
            
            // 3. ពិនិត្យ​មើល status ហើយ​ប្តូរ​តម្លៃ​ពី 1/0 ទៅ​ជា​អក្សរ
            $product->status = $product->status == '1' ? 'Active' : 'Disable';
            
            return $product;
        });
        // ✅ END
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Product Name',
            'Category ID',
            'Supplier ID',
            'Condition ID',
            'Stock Alert',
            'Product Detail',
            'Product Code',
            'Product Image',
            'Product Store',
            'Buying Price',
            'Selling Price',
            'Status', // ឈ្មោះ Column នៅ​ដដែល
            'Created At',
            'Updated At',
        ];
    }
}