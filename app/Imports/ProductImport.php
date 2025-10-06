<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductImport implements ToModel, WithStartRow
{
    private $currentCodeNumber = 0;
    private $codePrefix = 'SR-'; // កំណត់ Prefix

    public function __construct()
    {
        // 1. រក Product Code ដែលមានលេខរៀងខ្ពស់បំផុតក្នុង Database
        $latestProduct = Product::orderBy('id', 'desc')
                                ->where('product_code', 'like', $this->codePrefix . '%')
                                ->first();

        // 2. ដកយកលេខរៀងចុងក្រោយ
        if ($latestProduct) {
            // ឧទាហរណ៍: ពី 'SR-44' យកបាន '44'
            $numberPart = str_replace($this->codePrefix, '', $latestProduct->product_code);
            
            // ធានាថាតម្លៃនោះជាលេខគត់ មុនពេលចាប់ផ្តើមបូក
            if (is_numeric($numberPart)) {
                $this->currentCodeNumber = (int) $numberPart;
            }
        }
    }
    
    /**
     * កំណត់ថាត្រូវចាប់ផ្តើមអានពីជួរទីប៉ុន្មាន
     * @return int
     */
    public function startRow(): int
    {
        return 2; // រំលងជួរទី 1 (Header Row)
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. បង្កើនលេខរៀងបន្ត (44 => 45)
        $this->currentCodeNumber++;

        // 2. បង្កើត Product Code ថ្មី (SR-45)
        $finalProductCode = $this->codePrefix . $this->currentCodeNumber;
        
        // 3. យកតម្លៃពី Excel មកប្រើតែជាទិន្នន័យផ្សេងៗ តែមិនយក Product Code ពី Excel ទេ
        return new Product([
            'product_name' => $row[0],
            'category_id' => $row[1],
            'supplier_id' => $row[2],
            'condition_id' => $row[3],
            'stock_alert' => $row[4],
            'product_detail' => $row[5],
            'product_code' => $finalProductCode, // <-- ប្រើកូដដែលបាន Generate ថ្មី
            'product_image' => $row[7],
            'product_store' => 0,
            'buying_price' => $row[9],
            'selling_price' => $row[10], 
        ]);
    }
}