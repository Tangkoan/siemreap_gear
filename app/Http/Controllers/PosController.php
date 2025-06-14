<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\customer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Orderdetails;



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

        // // ========================================================================
        // // ករណីទី១៖ បង្ហាញหน้าเว็บដំបូង (GET Request)
        // // ========================================================================
        // if ($request->isMethod('get')) {
        //         $contents = Cart::content();
        //         $cust_id = $request->customer_id;

        //         // ពិនិត្យមើលថា Customer មានពិតหรือไม่
        //         if (!$cust_id || !$customer = Customer::find($cust_id)) {
        //             return redirect()->route('pos')->withErrors(['msg' => 'Invalid Customer Selected.']);
        //         }

        //         return view('admin.invoice.product_invoice', compact('contents', 'customer'));
        // }


        // // ========================================================================
        // // ករណីទី២៖ ដោះស្រាយการ Submit Form (POST Request)
        // // ========================================================================
        // if ($request->isMethod('post')) {

        //     // --- ការត្រួតពិនិត្យข้อมูลរួម (Common Validation) ---
        //     $request->validate(['customer_id' => 'required|integer']);

        //     $contents = Cart::content();
        //     if ($contents->isEmpty()) {
        //         return redirect()->route('pos')->withErrors(['msg' => 'Cannot create order from an empty cart.']);
        //     }

        //     // --- រៀបចំข้อมูลរួមសម្រាប់ Order (Common Data) ---
        //     $data = [
        //         'customer_id'    => $request->customer_id,
        //         'order_date'     => Carbon::now()->format('d-F-Y'),
        //         'total_products' => Cart::count(),
        //         'sub_total'      => Cart::subtotal(),
        //         'vat'            => Cart::tax(),
        //         'total'          => Cart::total(),
        //         'created_at'     => Carbon::now(),
        //     ];

        //     // --- ពិនិត្យមើលថាជា Final Invoice ឬ Pending Order ---

        //     // A. ប្រសិនបើ Request មាន 'payment_status' (មកពី Popup) -> สร้าง Final Invoice
        //     if ($request->has('payment_status')) {
        //         $data['order_status']   = 'completed'; // កំណត់ status ជា completed
        //         $data['invoice_no']     = 'EPOS' . mt_rand(10000000, 99999999);
        //         $data['payment_status'] = $request->payment_status;
        //         $data['pay']            = $request->pay;
        //         $data['due']            = $request->due;

        //         $notification_message = 'Order Complete Successfully';

        //     }
        //     // B. បើមិនដូច្នោះទេ (មកពីប៊ូតុង Submit ធម្មតា) -> สร้าง Pending Order
        //     else {
        //         $data['order_status']   = 'pending';
        //         $data['invoice_no']     = null; // មិនទាន់មាន Invoice No.
        //         $data['payment_status'] = 'unpaid';
        //         $data['pay']            = 0;
        //         $data['due']            = $data['total']; // ជំពាក់ทั้งหมด

        //         $notification_message = 'Order Saved as Pending Successfully';
        //     }

        //     // --- ការរក្សាទុកข้อมูลទៅ Database (ដំណើរការเหมือนกันทั้งสองกรณี) ---
        //     $order_id = Order::insertGetId($data);

        //     // រៀបចំข้อมูลสินค้าទាំងអស់
        //     $pdata = [];
        //     foreach ($contents as $content) {
        //         $pdata[] = [
        //             'order_id' => $order_id,
        //             'product_id' => $content->id,
        //             'quantity' => $content->qty,
        //             'unitcost' => $content->price,
        //             'total' => $content->total,
        //             'created_at' => Carbon::now(),
        //         ];
        //     }
        //     // បញ្ចូលข้อมูลสินค้าทั้งหมดក្នុងครั้งเดียว
        //     Orderdetails::insert($pdata);

        //     // បង្កើត Notification
        //     $notification = [
        //         'message' => $notification_message,
        //         'alert-type' => 'success'
        //     ];

        //     // លុបตะกร้าสินค้า
        //     Cart::destroy();

        //     // Redirect กลับไปหน้า POS
        //     return redirect()->route('pos')->with($notification);
        // }
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
