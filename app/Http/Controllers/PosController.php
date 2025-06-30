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

use Illuminate\Support\Facades\DB;


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


public function FinalInvoice(Request $request)
{
    $cartItems = Cart::content();

    if ($cartItems->isEmpty()) {
        return back()->with([
            'message' => 'You must add product to cart!',
            'alert-type' => 'error'
        ]);
    }

    // Pre-check stock before transaction
    foreach ($cartItems as $item) {
        $product = Product::find($item->id);
        if (!$product || $product->product_store < $item->qty) {
            return back()->with([
                'message' => "Not enough stock for product: {$product->product_name}",
                'alert-type' => 'error'
            ]);
        }
    }

    DB::beginTransaction();

    try {
        $subTotal = floatval(str_replace(',', '', Cart::subtotal()));
        $discount = floatval($request->discount ?? 0);
        $pay = floatval($request->pay);
        $total = $subTotal - $discount;
        $due = $total - $pay;

        // ✅ Logic: Check if paid in full (considering discount)
        $orderStatus = $due <= 0 ? 'complete' : 'pending';

        $data = [
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date ?? Carbon::now()->toDateString(),
            'order_status' => $orderStatus,
            'total_products' => Cart::count(),
            'sub_total' => $subTotal,
            'vat' => 0,
            'invoice_no' => 'SR_GEAR' . mt_rand(10000000, 99999999),
            'total' => $total,
            'payment_status' => $request->payment_status,
            'pay' => $pay,
            'due' => max(0, $due),
            'created_at' => Carbon::now(),
        ];

        $order_id = Order::insertGetId($data);

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            // Final stock check again for safety
            if (!$product || $product->product_store < $item->qty) {
                DB::rollBack();
                return back()->with([
                    'message' => "Not enough stock for product: {$product->product_name}",
                    'alert-type' => 'error'
                ]);
            }

            // Save order details
            Orderdetails::insert([
                'order_id' => $order_id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'unitcost' => $item->price,
                'total' => $item->qty * $item->price,
            ]);

            // ✅ Only deduct stock if order is complete
            if ($orderStatus === 'complete') {
                $product->decrement('product_store', $item->qty);
            }
        }

        DB::commit();
        Cart::destroy();

        return redirect()->route('print.invoice', $order_id)->with([
            'message' => 'Order completed successfully',
            'alert-type' => 'success'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with([
            'message' => 'Something went wrong! ' . $e->getMessage(),
            'alert-type' => 'error'
        ]);
    }
}


public function PrintInvoice($id)
{
    $order = Order::with('customer')->findOrFail($id);
    $orderDetails = Orderdetails::with('product')->where('order_id', $id)->get();

    return view('admin.invoice.print', compact('order', 'orderDetails'));
}






   
}
