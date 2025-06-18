<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\purchase;
use App\Models\supplier;
use App\Models\product;
use App\Models\purchase_details;

use Carbon\Carbon;

class PurchaseController extends Controller
{
    //
    public function PurchasePage(){
       
        $purchases = Purchase::latest()->get();
        return view('admin.purchases.all_purchase', compact('purchases'));
    }// End Method


    public function AddPurchase(){

        // $category = Category::latest()->get();
        // $supplier = Supplier::latest()->get();

        $product = Product::orderBy('product_name', 'asc')->get();
        $supplier = Supplier::orderBy('name', 'asc')->get();

        return view('admin.purchases.add_purchase',compact('product','supplier'));
       }// End Method 

    // PurchaseController.php
    
    // Get
    public function getProductPrice($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json(['price' => $product->buying_price]);
        }

        return response()->json(['price' => 0]);
    }




       public function CreatePurchase(Request $request){

        $data = array();
        $data['supplier_id'] = $request->supplier_id;
        $data['purchase_date'] = $request->purchase_date;
        $data['purchase_status'] = $request->purchase_status;
        $data['discount'] = $request->discount;
        // $data['total_products'] = $request->total_products;
        $data['total_products'] = count($request->product_id);
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;
        $data['invoice_no'] = 'PUR'.mt_rand(10000000,99999999);
        $data['total'] = $request->total;
        $data['payment_status'] = $request->payment_status;
        $data['pay'] = $request->pay;
        $data['due'] = $request->due;
        $data['created_at'] = Carbon::now();
    
        // បញ្ចូលទិន្នន័យទិញ
        $purchase_id = Purchase::insertGetId($data);
    
        // បញ្ចូលទំនិញក្នុង purchase_details
        if ($request->has('product_id')) {
            foreach($request->product_id as $index => $product_id){
                $pdata = array();
                $pdata['purchase_id'] = $purchase_id;
                $pdata['product_id'] = $product_id;
                $pdata['quantity'] = $request->quantity[$index];
                $pdata['unitcost'] = $request->unitcost[$index];
                $pdata['total'] = $request->quantity[$index] * $request->unitcost[$index];
    
                purchase_details::insert($pdata);
            }
        }
    
        // Notification
        $notification = array(
            'message' => 'Purchases Completed Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('pos')->with($notification);
    }




    public function PendingPurchase(){

        $purchases = purchase::where('purchases_status','pending')->get();
        return view('admin.purchases.all_purchase',compact('purchases'));

    }// End Method 
    
}
