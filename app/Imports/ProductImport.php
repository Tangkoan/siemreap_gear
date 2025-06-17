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
            
            'product_detail' => $row[3],
            'product_code' => $row[4],
            'product_image' => $row[5],
            'product_store' => $row[6],
            'buying_date' => $row[7],
            'buying_price' => $row[8],
            'selling_price' => $row[9], 
        ]);
    }
}
