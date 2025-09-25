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
use App\Models\ExchangeRate;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; //  <-- ត្រូវ Import ថែម
use App\Models\Condition; // ✅ 1. បន្ថែម Use Condition Model

// បន្ថែម Use Statement ទាំងពីរនេះនៅផ្នែកខាងលើនៃ PosController.php
use Symfony\Component\DomCrawler\Crawler;

// ✅ ជំហានទី១.១៖ ត្រូវប្រាកដថាបាន Import HTTP Client
use Illuminate\Support\Facades\Http;


class PosController extends Controller
{

    
    // In app/Http/Controllers/PosController.php

public function fetchAndStoreAutoRate(Request $request)
    {
        try {
            $response = Http::timeout(20)->get('https://data.mef.gov.kh/api/v1/realtime-api/exchange-rate?currency_id=USD');

            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Could not connect to API. Please enter manually.'], 502);
            }

            $data = $response->json();
            \Log::info('MEF API Response:', $data);

            // ✅ FIXED: Changed 'rate_buy' to 'bid' to match the actual API response.
            // ✅ បានកែប្រែ៖ បានប្តូរ 'rate_buy' ទៅជា 'bid' ដើម្បីឲ្យត្រូវនឹង Response ពិតរបស់ API។
            if (!empty($data['data']) && isset($data['data']['bid']) && !empty($data['data']['bid'])) {
                
                // --- Case 1: Today's rate is available ---
                // --- ករណីទី១៖ អត្រាថ្ងៃនេះមាន ---
                $rate = (float) $data['data']['bid']; // Use 'bid' instead of 'rate_buy'

                if ($rate <= 0) {
                    return response()->json(['success' => false, 'message' => 'API returned an invalid rate value.'], 500);
                }

                DB::transaction(function () use ($rate) {
                    ExchangeRate::where('rate_date', '!=', now()->toDateString())->update(['is_active' => false]);
                    ExchangeRate::updateOrCreate(
                        ['rate_date' => now()->toDateString()],
                        ['rate_khr' => $rate, 'is_active' => true]
                    );
                });

                return response()->json([
                    'success' => true,
                    'is_fallback' => false, // Flag indicating it's a fresh rate
                    'message' => 'Successfully fetched today\'s exchange rate!',
                    'new_rate' => $rate
                ]);

            } else {
                
                // --- Case 2: Today's rate is NOT available (Weekend/Holiday) ---
                // --- ករណីទី២៖ អត្រាថ្ងៃនេះមិនមាន (ចុងសប្តាហ៍/ថ្ងៃបុណ្យ) ---
                $lastActiveRate = ExchangeRate::latest('rate_date')->first();

                if ($lastActiveRate) {
                    // Fallback to the most recent rate in the database
                    // ប្តូរទៅប្រើអត្រាចុងក្រោយបំផុតនៅក្នុង Database
                    return response()->json([
                        'success' => true,
                        'is_fallback' => true, // Flag indicating it's a fallback rate
                        'message' => "Using last rate from " . Carbon::parse($lastActiveRate->rate_date)->format('d-M-Y'),
                        'new_rate' => $lastActiveRate->rate_khr
                    ]);
                } else {
                    // No rate for today, and no previous rate found in the DB.
                    // គ្មានអត្រាសម្រាប់ថ្ងៃនេះទេ ហើយក៏គ្មានអត្រាមុននៅក្នុង DB ដែរ។
                    return response()->json([
                        'success' => false,
                        'message' => 'No rate available. Please enter one manually to begin.'
                    ], 404);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Auto fetch exchange rate error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function storeExchangeRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                
                // Deactivate អត្រាផ្សេងៗទាំងអស់
                ExchangeRate::where('rate_date', '!=', now()->toDateString())
                    ->update(['is_active' => false]);

                // រកមើល Record ដែលមានកាលបរិច្ឆេទថ្ងៃនេះ បើមានគឺ Update បើមិនមានគឺ Create
                ExchangeRate::updateOrCreate(
                    [
                        'rate_date' => now()->toDateString()
                    ],
                    [
                        'rate_khr' => $request->rate,
                        'is_active' => true
                    ]
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate updated successfully!',
                'new_rate' => $request->rate,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving exchange rate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exchange rate.'
            ], 500);
        }
    }


    // In app/Http/Controllers/PosController.php

    public function generateQuotationPreview(Request $request)
    {
        // ពិនិត្យ Cart និង Customer
        if (Cart::count() < 1) {
            return response('Cart is empty.', 400);
        }
        if (empty($request->customer_id)) {
            return response('Customer not selected.', 400);
        }

        // 1. ទាញយកข้อมูลពី Cart និង Request
        $cartItems = Cart::content();
        $customer = Customer::find($request->customer_id);
        $subTotal = floatval(str_replace(',', '', Cart::subtotal()));
        $discount = floatval($request->discount ?? 0);
        $total = $subTotal - $discount;

        // 2. បង្កើត Object ក្លែងក្លាយ (Fake Object) ដែលមានโครงสร้างដូច Quotation
        // ដើម្បីให้ View អាចប្រើប្រាស់បានโดยไม่ต้องកែប្រែច្រើន
        $quotationData = (object) [
            'customer' => $customer,
            'quotation_no' => 'PREVIEW-' . mt_rand(1000, 9999),
            'quotation_date' => Carbon::now()->toDateString(),
            'validity_date' => Carbon::now()->addDays(7)->toDateString(),
            'sub_total' => $subTotal,
            'discount' => $discount,
            'total' => $total,
            'terms_and_conditions' => "a. Laptop 2years Warranty, 1year service warranty\nb. Warranty void if: seal broken, electric shock, misuse, accident, or modification by anyone other than SR Gears.\nc. CPU(1year),MB(2years),RAM(1year),GPU(2years),HDD(1year) SSD(3year),Monitor (3years).\nd. Goods sold are not refundable or returnable.",
        ];

        // 3. បង្កើត Array សម្រាប់เก็บรายละเอียด Product
        $quotationDetailsData = [];
        foreach ($cartItems as $item) {
            $quotationDetailsData[] = (object) [
                'product' => (object) ['product_name' => $item->name], // បង្កើត Fake Product Object
                'quantity' => $item->qty,
                'unitcost' => $item->price,
                'total' => $item->qty * $item->price,
            ];
        }

        // 4. បញ្ជូនข้อมูลទៅកាន់ View ហើយ Return ជា HTML
        // យើងប្រើ View ដដែលกับตอน Print ធម្មតា
        return view('admin.quotation.print', [
            'quotation' => $quotationData,
            'quotationDetails' => $quotationDetailsData
        ]);
    }


    public function clearCartAndRedirect()
    {
    
        Cart::destroy();
        return redirect()->back(); 
    }

    

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
        // ✅ កែប្រែទី១៖ បន្ថែម ->where('status', '1') 
        $product = Product::where('status', '1')->latest()->get();
        
        $categories = Category::all();
        $conditions = Condition::orderBy('condition_name', 'asc')->get();
        $activeRate = ExchangeRate::where('is_active', true)->latest()->first();
        
        $walkInCustomer = Customer::where('name', 'Walk-In')->first();
        $otherCustomers = Customer::where('name', '!=', 'Walk-In')->orderBy('name', 'ASC')->get();
        
        $customers = collect();
        if ($walkInCustomer) { $customers->push($walkInCustomer); }
        $customers = $customers->merge($otherCustomers);

        return view('admin.pos.pos', compact('product', 'customers', 'categories', 'conditions', 'activeRate'));
    }

    public function getProductsByCategory(Request $request)
        {
            $query = Product::with('category', 'condition');

            // ✅ ត្រឹមត្រូវ​ហើយ
            $query->where('status', '1');

            if ($request->filled('category_id') && $request->category_id != 'all') {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('condition_id') && $request->condition_id != 'all') {
                $query->where('condition_id', $request->condition_id);
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
                    'stock' => (int) $product->product_store,
                    'condition' => $product->condition ? $product->condition->condition_name : 'N/A'
                ];
            });

            return response()->json(['products' => $products]);
        }
    // PosController.php
  public function searchProducts(Request $request)
    {
        $keyword = $request->input('keyword');

        // ✅ កែប្រែទី២៖ បន្ថែម ->where('status', '1') សម្រាប់​ពេល​ស្វែងរក
        $query = Product::where('status', '1');

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('product_name', 'LIKE', "%{$keyword}%")
                  ->orWhere('product_code', 'LIKE', "%{$keyword}%");
            });
        }

        $products = $query->latest()->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'buying_price' => (float)$product->buying_price,
                'price' => (float)$product->selling_price,
                'code' => $product->product_code,
                'category' => optional($product->category)->category_name ?? 'No Category',
                'imageUrl' => asset($product->product_image),
                'stock' => (int) $product->product_store,
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

        // B1: ត្រួតពិនិត្យរក Pre-Order ជាមុន (Pre-scan for Pre-Orders)
        $hasPreOrder = false;
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            if (!$product || $product->product_store < $item->qty) {
                $hasPreOrder = true;
                break;
            }
        }

        // B2: កំណត់ Order Status និង Order Type
        $orderStatus = '';
        $orderType = '';

        if ($hasPreOrder) {
            $orderStatus = 'pending';
            $orderType = 'pre_order';
        } else {
            $orderStatus = ($due <= 0) ? 'complete' : 'pending';
            $orderType = 'sale';
        }

        DB::beginTransaction();

        try {
            $data = [
                'customer_id' => $request->customer_id,
                'order_date' => $request->order_date ?? Carbon::now()->toDateString(),
                'order_status' => $orderStatus,
                'order_type' => $orderType,
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
                'exchange_rate_khr' => $request->exchange_rate_khr,
            ];

            // ✅ START: កូដដែលបានបន្ថែម
            // ពិនិត្យមើល បើ Order Status គឺ complete ត្រូវបន្ថែម completion_date
            if ($orderStatus === 'complete') {
                $data['completion_date'] = Carbon::now();
            }
            // ✅ END: កូដដែលបានបន្ថែម

            $order_id = Order::insertGetId($data);

            foreach ($cartItems as $item) {
                $product = Product::find($item->id);

                if ($product && $product->product_store >= $item->qty) {
                    $item_status = 'fulfilled';
                    if ($orderStatus === 'complete') {
                        $product->decrement('product_store', $item->qty);
                    }
                } else {
                    $item_status = 'pre_ordered';
                }

                Orderdetails::insert([
                    'order_id' => $order_id,
                    'product_id' => $item->id,
                    'quantity' => $item->qty,
                    'unitcost' => $item->price,
                    'total' => $item->qty * $item->price,
                    'item_status' => $item_status,
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
                'message' => __('messages.something_went_wrong') . ': ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }




    
    // public function PrintInvoice($id)
    // {
    //     $order = Order::with('customer')->findOrFail($id);
    //     $orderDetails = Orderdetails::with('product')->where('order_id', $id)->get();

    // return view('admin.invoice.print', compact('order', 'orderDetails'))->with("message", 'Successfully Order!!');
    // }
 
    public function PrintInvoice($id)
    {
        // មិនចាំបាច់កែប្រែទេ ព្រោះ $order នឹងមាន exchange_rate_khr ដោយស្វ័យប្រវត្តិ
        $order = Order::with('customer')->findOrFail($id);
        $orderDetails = Orderdetails::with('product')->where('order_id', $id)->get();

        return view('admin.invoice.print', compact('order', 'orderDetails'))->with("message", 'Successfully Order!!');
    }
}
