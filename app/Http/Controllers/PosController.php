<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\customer;
use Gloudemans\Shoppingcart\Facades\Cart;




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

    public function AddCart(Request $request){

        Cart::add([
            'id' => $request->id, 
            'name' => $request->name, 
            'qty' => $request->qty, 
            'price' => $request->price, 
            'weight' => 20, 
            'options' => ['size' => 'large']]);


         $notification = array(
            'message' => 'Product Added Successfully',
            'alert-type' => 'success'
        );

        // return redirect()->back()->with($notification);
        return redirect()->back();


    } // End Method 

    public function AllItem(){

        $product_item = Cart::content();

        return view('admin.pos.text_item',compact('product_item'));

    } // End Method 


    public function CartUpdate(Request $request,$rowId){
        $qty = $request->qty;
        $update = Cart::update($rowId,$qty);
         
         $notification = array(
            'message' => 'Cart Updated Successfully',
            'alert-type' => 'success'
        );

        // return redirect()->back()->with($notification);
        return redirect()->back();

    } // End Method 


    public function CartRemove($rowId){
        Cart::remove($rowId);
        $notification = array(
            'message' => 'Cart Remove Successfully',
            'alert-type' => 'success'
        );
        // return redirect()->back()->with($notification);
        return redirect()->back();
    } // End Method 

    public function CreateInvoice(Request $request){

        $contents = Cart::content();
        $cust_id = $request->customer_id;
        $customer = Customer::where('id',$cust_id)->first();
        return view('admin.invoice.product_invoice',compact('contents','customer'));

   } // End Method 


   public function CreateInvoiceVI(Request $request){

    $contents = Cart::content();
    $cust_id = $request->customer_id;
    $customer = Customer::where('id',$cust_id)->first();
    return view('admin.pos.pos',compact('contents','customer'));

} // End Method 

   
}
