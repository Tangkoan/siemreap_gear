<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
     * Return data to export
     */
    public function collection()
    {
        return Product::select(
            'product_name',
            'category_id',
            'supplier_id',
            'product_detail',
            'product_code',
            'product_image',
            'product_store',
            'buying_price',
            'selling_price',
            'created_at',
            'updated_at'
        )->get();
    }

    /**
     * Return headers for Excel file
     */
    public function headings(): array
    {
        return [
            'Product Name',
            'Category ID',
            'Supplier ID',
            'Product Detail',
            'Product Code',
            'Product Image',
            'Product Store',
            'Buying Price',
            'Selling Price',
            'Created At',
            'Updated At',
        ];
    }
}