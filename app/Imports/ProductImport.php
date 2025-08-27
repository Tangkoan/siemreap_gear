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
            'condition_id' => $row[3],
            'product_detail' => $row[4],
            'product_code' => $row[5],
            'product_image' => $row[6],
            'product_store' => 0,
            'buying_price' => $row[8],
            'selling_price' => $row[9], 
        ]);
    }
}
