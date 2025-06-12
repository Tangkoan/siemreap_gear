<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\customer;

class PosController extends Controller
{
    //

    public function PosPage(){
        $product = Product::latest()->get();
        $customer = Customer::latest()->get();
        return view('admin.pos.pos', compact('product', 'customer'));
    }// End Method


    // (AJAX/Fetch)
    public function getProductsForPos()
    {
        
        $products = Product::with('category')->latest()->get()->map(function($product) {
            
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => (float)$product->selling_price, 
                'category' => $product->category ? $product->category->category_name : 'No Category', 
                'imageUrl' => asset($product->product_image) 
            ];
        });

        
        return response()->json([
            'products' => $products,
        ]);
    }
}
