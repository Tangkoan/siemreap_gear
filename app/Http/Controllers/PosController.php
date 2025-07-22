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
use Illuminate\Support\Facades\Validator; //  <-- ត្រូវ Import ថែម


class PosController extends Controller
{
        //
        /**
         * នេះជាកូដដែលកែប្រែពី StoreCustomer របស់អ្នក ឱ្យទៅជា AJAX Standard
         */
        public function storeCustomerAjax(Request $request)
        {
            // === ផ្នែកទី១៖ ការធ្វើ Validation ===
            // យើងប្រើ Validator::make() ជំនួសឱ្យ $request->validate() ដើម្បីគ្រប់គ្រង Response ដោយខ្លួនឯង
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:customers,name',
                'notes' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500', 
            ], [
                // អ្នកអាចដាក់ Custom Message នៅទីនេះបានដូចគ្នា
                'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
                'name.unique'   => __('validation.unique', ['attribute' => __('validation.attributes.name')]),
            ]);

            // ពិនិត្យមើលបើ Validation Fails
            if ($validator->fails()) {
                // បញ្ជូន Error JavaScript ជា JSON ជាមួយ Status Code 422
                return response()->json(['errors' => $validator->errors()], 422);
            }


            // === ផ្នែកទី២៖ ការរក្សាទុកទិន្នន័យ និង Response ពេលជោគជ័យ ===
            // យើងប្រើ try...catch ដើម្បីចាប់ Error ពី Database
            try {
                // ប្រើ customer::create() ជំនួសឱ្យ insert() ព្រោះវាជា Eloquent best practice
                $customer = Customer::create([
                    'name' => $request->name,
                    'notes' => $request->notes,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    // create() នឹងបំពេញ created_at ដោយស្វ័យប្រវត្តិ មិនចាំបាច់reload page
                ]);
                
                // ប្តូរពី redirect() ទៅជា response()->json()
                return response()->json([
                    'message' => __('messages.customer_inserted_successfully'),
                    'newCustomer' => [ // ត្រូវបញ្ជូនទិន្នន័យនេះ JS
                        'id' => $customer->id,
                        'name' => $customer->name,
                    ]
                ], 200);

            } catch (\Exception $e) {
                // === ផ្នែកទី៣៖ ការដោះស្រាយ Exception ===
                \Log::error('Error saving Customer: ' . $e->getMessage()); 
                
                return response()->json([
                    'errors' => ['database' => __('messages.customer_insert_failed')]
                ], 500);
            }
        }

    

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


    
    /**
     * ✅ ការកែប្រែទី១៖ ដកលក្ខខណ្ឌ stock > 0 ចេញពី Backend
     * យើងនឹងបញ្ជូនផលិតផលទាំងអស់ទៅ Frontend ហើយឱ្យ JavaScript ជាអ្នកសម្រេចចិត្តបង្ហាញ Badge
     */
    public function getProductsByCategory(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id != 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => (float)$product->selling_price,
                'buying_price' => (float)$product->buying_price,
                'code' => $product->product_code,
                'category' => $product->category ? $product->category->category_name : 'No Category',
                'imageUrl' => asset($product->product_image),
                'stock' => (int) $product->product_store // បញ្ជូនចំនួនស្តុកទៅ JavaScript
            ];
        });

        return response()->json(['products' => $products]);
    }

    // PosController.php
    public function searchProducts(Request $request)
    {
        $keyword = $request->input('keyword');

        $products = Product::where('product_name', 'LIKE', "%{$keyword}%")
            ->orWhere('product_code', 'LIKE', "%{$keyword}%") // បន្ថែមលក្ខខណ្ឌ Search តាម Code ក៏បាន
            ->latest()
            ->get()
            ->map(function($product) {
                // ប្រើ .map() ដើម្បីរៀបចំទិន្នន័យឲ្យដូចគ្នា
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'buying_price' => (float)$product->buying_price,
                    'price' => (float)$product->selling_price,
                    'code' => $product->product_code,
                    'category' => $product->category ? $product->category->category_name : 'No Category',
                    'imageUrl' => asset($product->product_image),
                    'stock' => (float) $product->product_store,

                ];
            });

        return response()->json(['products' => $products]);
    }
    
    public function AddCart(Request $request){
        // ពិនិត្យ Stock ម្តងទៀតនៅ Backend ដើម្បីសុវត្ថិភាព
        $product = Product::find($request->id);
        $cart = Cart::content();
        $existingItem = $cart->where('id', $product->id)->first();
        $newQty = $request->qty;

        if ($existingItem) {
            $newQty += $existingItem->qty;
        }
        
        // បើជាផលិតផលដែលមានក្នុងស្តុក ត្រូវប្រាកដថាចំនួនដែលสั่งซื้อไม่เกินចំនួនស្តុក
        if ($product->product_store > 0 && $newQty > $product->product_store) {
            return response()->json([
                'error' => 'Not enough stock for ' . $product->product_name . '. Only ' . $product->product_store . ' left.',
                'alert-type' => 'error'
            ]);
        }
        
        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'weight' => 20, // អ្នកអាចកែប្រែតាមความเหมาะสม
            'options' => $request->options ?? [] // ទទួល Options ពី Request
        ]);

        return response()->json([
            'alert-type' => 'success',
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }

    public function CartRemove($rowId){
        Cart::remove($rowId);

        // Return JSON response instead of redirect
        return response()->json([
            // 'message' => 'Cart Item Removed Successfully',
            'alert-type' => 'success',
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }


    public function CartUpdate(Request $request, $rowId){
        $qty = $request->qty;
        Cart::update($rowId, $qty);

        return response()->json([
            // 'message' => 'Cart Updated Successfully',
            'alert-type' => 'success',
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }

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



   /**
     * ✅ ការកែប្រែធំ និងសំខាន់បំផុត៖ FinalInvoice
     * កែប្រែ Logic ការបង្កើត Order និងការកាត់ស្តុក
     */
    // ដាក់ในไฟล์ PosController.php

public function FinalInvoice(Request $request)
{
    $cartItems = Cart::content();

    if ($cartItems->isEmpty()) {
        return back()->with(['message' => __('messages.you_mout_add_product_to_cart'), 'alert-type' => 'error']);
    }

    $subTotal = floatval(str_replace(',', '', Cart::subtotal()));
    $discount = floatval($request->discount ?? 0);

    if ($discount > $subTotal) {
        return back()->with(['message' => __('messages.discount_cannot_exceed_subtotal', ['subtotal' => number_format($subTotal, 2)]), 'alert-type' => 'error'])->withInput();
    }

    $pay = floatval($request->pay);
    $total = $subTotal - $discount;
    $due = $total - $pay;

    // ✅ ==================== START: កូដដែលបានកែប្រែ ====================

    // B1: ត្រួតពិនិត្យរក Pre-Order ជាមុន (Pre-scan for Pre-Orders) 💡
    $hasPreOrder = false;
    foreach ($cartItems as $item) {
        $product = Product::find($item->id);
        // ប្រសិនបើផលិតផលមិនមាន ឬស្តុកមិនគ្រប់គ្រាន់ នោះវាជា Pre-Order
        if (!$product || $product->product_store < $item->qty) {
            $hasPreOrder = true;
            break; // រកឃើញ Pre-Order មួយហើយ មិនចាំបាច់ឆែកបន្ត
        }
    }

    // B2: កំណត់ Order Status ដោយផ្អែកលើលក្ខខណ្ឌថ្មី
    $orderStatus = '';
    if ($hasPreOrder) {
        // 👉 បើមាន Pre-Order យ៉ាងហោចណាស់មួយ, Order ត្រូវតែ Pending ជានិច្ច
        $orderStatus = 'pending';
    } else {
        // 👉 បើមិនមាន Pre-Order ទើបពិនិត្យលើការបង់ប្រាក់
        $orderStatus = ($due <= 0) ? 'complete' : 'pending';
    }

    // ✅ ===================== END: កូដដែលបានកែប្រែ =====================

    DB::beginTransaction();

    try {
        $data = [
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date ?? Carbon::now()->toDateString(),
            'order_status' => $orderStatus, // ប្រើប្រាស់ Status ដែលបានកំណត់យ៉ាងត្រឹមត្រូវ
            'discount' => $discount,
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

            // ពិនិត្យមើលថាតើជា Pre-Order ឬ In-Stock
            if ($product && $product->product_store >= $item->qty) {
                // --- ករណីលក់ធម្មតា (In-Stock) ---
                $item_status = 'fulfilled';

                // កាត់ស្តុកតែក្នុងករណីដែល Order ទាំងមូល "complete" ប៉ុណ្ណោះ
                // (មានន័យថា គ្មាន Pre-Order และ បង់ប្រាក់គ្រប់)
                if ($orderStatus === 'complete') {
                    $product->decrement('product_store', $item->qty);
                }
                
            } else {
                // --- ករណី Pre-Order ---
                $item_status = 'pre_ordered';
                // មិនកាត់ស្តុកទេ
            }

            Orderdetails::insert([
                'order_id' => $order_id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'unitcost' => $item->price,
                'total' => $item->qty * $item->price,
                'item_status' => $item_status, // កំណត់ status សម្រាប់ item នីមួយៗ
            ]);
        }

        DB::commit();
        Cart::destroy();

        return redirect()->route('print.invoice', $order_id)->with([
            'message' => __('messages.order_completed_successfully'),
            'alert-type' => 'success'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with([
            'message' => __('messages.something_went_wrong'). ': ' . $e->getMessage(),
            'alert-type' => 'error'
        ]);
    }
}
    public function PrintInvoice($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        $orderDetails = Orderdetails::with('product')->where('order_id', $id)->get();

    return view('admin.invoice.print', compact('order', 'orderDetails'))->with("message", 'Successfully Order!!');
    }
 
}
