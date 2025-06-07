<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'product_name' => $row[0],
            'category_id' => $row[1],
            'supplier_id' => $row[2],
            'brand_id' => $row[3],
            'unit_id' => $row[4],
            'product_detail' => $row[5],
            'product_code' => $row[6],
            'product_image' => $row[7],
            'product_store' => $row[8],
            'buying_date' => $row[9],
            'buying_price' => $row[10],
            'selling_price' => $row[11], 
        ]);
    }
}
