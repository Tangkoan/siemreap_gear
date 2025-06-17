<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\customer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Orderdetails;
use App\Models\Category;



class PosController extends Controller
{
    //

    

    public function PosPage()
    {
        $product = Product::latest()->get();
        $categories = Category::all(); // ទាញ category ទាំងអស់

        $walkInCustomer = Customer::where('name', 'Walk-In')->first(); // ឬ ->get() បើច្រើន
        $otherCustomers = Customer::where('name', '!=', 'Walk-In')->orderBy('name', 'ASC')->get();

        // បង្រួមជាមួយ nhau
        $customers = collect(); // ទទេ
        if ($walkInCustomer) {
            $customers->push($walkInCustomer);
        }
        $customers = $customers->merge($otherCustomers);

        return view('admin.pos.pos', compact('product', 'customers','categories'));
    }


    // (AJAX/Fetch)
    // public function getProductsForPos()
    // {
        
    //     $products = Product::with('category')->latest()->get()->map(function($product) {
            
    //         return [
    //             'id' => $product->id,
    //             'name' => $product->product_name,
    //             'price' => (float)$product->selling_price, 
    //             'category' => $product->category ? $product->category->category_name : 'No Category', 
    //             'imageUrl' => asset($product->product_image) 
    //         ];
    //     });

        
    //     return response()->json([
    //         'products' => $products,
    //     ]);
    // }

    // public function getProductsForPos(Request $request)
    // {
    //     $query = Product::with('category');

    //     if ($request->has('category_id') && $request->category_id != 'all') {
    //         $query->where('category_id', $request->category_id);
    //     }

    //     $products = $query->latest()->get()->map(function($product) {
    //         return [
    //             'id' => $product->id,
    //             'name' => $product->product_name,
    //             'price' => (float)$product->selling_price, 
    //             'category' => $product->category ? $product->category->category_name : 'No Category', 
    //             'imageUrl' => asset($product->product_image) 
    //         ];
    //     });

    //     return response()->json([
    //         'products' => $products,
    //     ]);
    // }

    public function getProductsByCategory(Request $request)
    {
        $categories = Category::all(); // ទាញ Category ទាំងអស់

        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get()->map(function($product) {
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
            'categories' => $categories
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

    // public function AddCart(Request $request)
    // {
    //     Cart::add([
    //         'id' => $request->id,
    //         'name' => $request->name,
    //         'qty' => $request->qty,
    //         'price' => $request->price,
    //         'weight' => 20,
    //         'options' => ['size' => 'large']
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Product Added Successfully'
    //     ]);
    // }


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


public function FinalInvoice(Request $request){

    $data = array();
    $data['customer_id'] = $request->customer_id;
    $data['order_date'] = $request->order_date;
    $data['order_status'] = $request->order_status;
    $data['total_products'] = $request->total_products;
    $data['sub_total'] = $request->sub_total;
    $data['vat'] = $request->vat;

    $data['invoice_no'] = 'EPOS'.mt_rand(10000000,99999999);
    $data['total'] = $request->total;
    $data['payment_status'] = $request->payment_status;
    $data['pay'] = $request->pay;
    $data['due'] = $request->due;
    $data['created_at'] = Carbon::now(); 

    $order_id = Order::insertGetId($data);
    $contents = Cart::content();

    $pdata = array();
    foreach($contents as $content){
        $pdata['order_id'] = $order_id;
        $pdata['product_id'] = $content->id;
        $pdata['quantity'] = $content->qty;
        $pdata['unitcost'] = $content->price;
        $pdata['total'] = $content->total;
        
        $insert = Orderdetails::insert($pdata); 

    } // end foreach


    $notification = array(
        'message' => 'Order Complete Successfully',
        'alert-type' => 'success'
    );

    Cart::destroy();

    return redirect()->route('pos')->with($notification);

} // End Method 
   
}
