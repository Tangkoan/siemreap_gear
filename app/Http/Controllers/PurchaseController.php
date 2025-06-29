<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\purchase_details;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;

class PurchaseController extends Controller
{


    // Purchases All List Complter Purchase & Pending Purchase & Due Purchase
    public function PendingDue(){

        $alldue = Purchase::where('due','>','0')
        ->orderBy('id','DESC')->get();
        return view('admin.purchases.pending_due',compact('alldue'));
    }// End Method 

    public function FinalInvoice(Request $request){

        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;
    
        $data = [];
        $data['supplier_id'] = $request->supplier_id;
        $data['purchase_date'] = $request->purchase_date;
        $data['purchase_status'] = $request->purchase_status;
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;
    
        $data['invoice_no'] = 'Pur' . mt_rand(10000000, 99999999);
        $data['total'] = $rtotal;
        $data['payment_status'] = $request->payment_status;
        $data['pay'] = $rpay;
        $data['due'] = $mtotal; // ✅ តែម្ដង!
        $data['created_at'] = Carbon::now();
    
        $purchase_id = Purchase::insertGetId($data);
    
        $contents = Cart::content();
    
        foreach($contents as $content){
            $pdata = [];
            $pdata['purchase_id'] = $purchase_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['unitcost'] = $content->price;
            $pdata['total'] = $content->total;
    
            purchase_details::insert($pdata);
        }
    
        $notification = [
            'message' => 'Purchase Complete Successfully',
            'alert-type' => 'success'
        ];
    
        Cart::destroy();
    
        return redirect()->route('purchase')->with($notification);
    }
    


    public function PendingPurchase(){

        $purchases = purchase::where('purchase_status','pending')->get();
        return view('admin.purchases.pending_purchase',compact('purchases'));

    }// End Method 



    // Search Show table
    public function searchPurchase(Request $request)
    {
        $query = Purchase::where('purchase_status', 'pending'); // ✅ មិនប្រើ get()

        // $query = Order::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('supplier', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        // if ($isAll) {
        //     // $orders = $query->get();
        //     $orders = Order::where('order_status','pending')->get();;
        // } else {
        //     $orders = $query->paginate((int)$perPage);
        // }

        if ($isAll) {
            $purchases = $query->get(); // ✅ Use query result with filter
        } else {
            $purchases = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($purchases as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
           
                <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                <td class="p-4 py-5">' . $item->purchase_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 px-4 py-5 text-center align-middle">
                        <span class="inline-block px-3 py-1 rounded-md bg-red-500 text-white font-semibold shadow-sm">

                        '. $item->purchase_status  .'
                    </span>
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('purchase.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>
                       
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $purchases->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    public function PurchaseDetails($purchase_id){

        $purchase = Purchase::where('id',$purchase_id)->first();

        $purchaseItem = purchase_details::with('product')->where('purchase_id',$purchase_id)->orderBy('id','DESC')->get();
        return view('admin.purchases.purchase_details',compact('purchase','purchaseItem'));

    }// End Method 

    public function PurchaseStatusUpdate(Request $request)
    {
        $purchase_id = $request->id;

        $purchaseProducts = purchase_details::where('purchase_id', $purchase_id)->get();

        foreach ($purchaseProducts as $item) {
            $product = Product::find($item->product_id);

            // ✅ ថែម Stock
            $product->increment('product_store', $item->quantity);
        }

        // ✅ Update status purchase ជា complete
        Purchase::findOrFail($purchase_id)->update(['purchase_status' => 'complete']);

        $notification = [
            'message' => 'Purchase Done Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('pending.purchase')->with($notification);
    }

    public function CompletePurchase(){

        $purchases = Purchase::where('purchase_status','complete')->get();
        return view('admin.purchases.purchase_complete',compact('purchases'));

    }// End Method

    public function searchCompletePurchase(Request $request)
    {
        $query = Purchase::where('purchase_status', 'complete'); // ✅ មិនប្រើ get()

        // $query = Order::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('suppier', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

       

        if ($isAll) {
            $purchases = $query->get(); // ✅ Use query result with filter
        } else {
            $purchases = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($purchases as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                

                
                <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                <td class="p-4 py-5">' . $item->purchase_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">' . $item->invoice_no  . '</td>
                <td class="p-4 py-5">' . $item->pay  . '</td>
                
                <td class="p-4 py-5 text-center align-middle">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-green-500 text-white font-semibold shadow-sm">
                            '. $item->purchase_status  .'
                        </span>
                    
                    
                </td>
                
                
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $purchases->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    public function searchPendingDue(Request $request)
    {
        $query = Purchase::where('due', '>' ,0); 


        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->WhereHas('supplier', function ($cat) use ($request) {
                        $cat->where('name', 'LIKE', '%' . $request->search . '%');
                        });
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        

        if ($isAll) {
            $purchases = $query->get(); // ✅ Use query result with filter
        } else {
            $purchases = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($purchases as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
            
                <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                <td class="p-4 py-5">' . $item->purchase_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">
                <span class="inline-block px-3 py-1 rounded-md bg-gray-400 text-white font-semibold shadow-sm">
                            ' . $item->total  . ' $
                        </span>
                    
                
                </td>

                <td class="p-4 py-5 ">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-red-500 text-white font-semibold shadow-sm">
                            '. $item->pay  .' $
                        </span>
                    
                    
                </td>
                
                <td class="p-4 py-5">
                    
                        <span class="inline-block px-3 py-1 rounded-md bg-green-500 text-white font-semibold shadow-sm">
                            '. $item->due  .' $
                        </span>
                    
                    
                </td>
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                
                   

                    <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('purchase.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>

                    <button type="button" class="icon-edit dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                                <a href="' . route('paydue.due', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                </svg>

                                </a>
                    </button>


                    
                       
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $purchases->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    















    public function PurchasePage() {
        $products = Product::latest()->get();
        $categories = Category::all();
        $supplier = Supplier::orderBy('name', 'ASC')->get();
        return view('admin.purchases.add_purchase', compact('products', 'supplier', 'categories'));
    }

    public function getProductsForPurchase(Request $request) {
        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => (float) $product->buying_price,
                'category' => $product->category ? $product->category->category_name : 'No Category',
                'imageUrl' => asset($product->product_image),
            ];
        });

        return response()->json(['products' => $products]);
    }

    public function AddToCart(Request $request) {
        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'weight' => 0,
            'options' => [],
        ]);

        return redirect()->back()->with('message', 'Product added to purchase cart.');
    }

    public function RemoveCartItem($rowId) {
        Cart::remove($rowId);
        return redirect()->back()->with('message', 'Item removed from cart.');
    }

    public function UpdateCartItem(Request $request, $rowId) {
        Cart::update($rowId, $request->qty);
        return redirect()->back()->with('message', 'Cart updated successfully.');
    }




public function StorePurchase(Request $request)
{
    // Validate input
    $request->validate([
        'supplier_id' => 'required',
        'payment_status' => 'required',
        'total' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'pay' => 'required|numeric|min:0',
    ]);

    $cartItems = Cart::content();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with([
            'message' => 'Please select product for purchase',
            'alert-type' => 'error',
        ]);
    }

    // Subtotal from cart
    $subTotal = $cartItems->sum(function ($item) {
        return $item->price * $item->qty;
    });

    $total = floatval($request->total);
    $discount = floatval($request->discount ?? 0);
    $paid = floatval($request->pay);

    // បន្ទាប់ពីបញ្ចុះថ្លៃ (final payable)
    $finalTotal = $total - $discount;
    $due = max($finalTotal - $paid, 0); // prevent negative due

    $data = [
        'supplier_id' => $request->supplier_id,
        'purchase_date' => Carbon::now(),
        'invoice_no' => 'PUR_' . mt_rand(10000000, 99999999),
        'purchase_status' => 'pending',
        'discount' => $discount,
        'total_products' => $cartItems->count(),
        'sub_total' => $subTotal,
        'vat' => 0,
        'total' => $total,
        'payment_status' => $request->payment_status,
        'pay' => $paid,
        'due' => $due,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];

    $purchase_id = Purchase::insertGetId($data);

    foreach ($cartItems as $item) {
        purchase_details::create([
            'purchase_id' => $purchase_id,
            'product_id' => $item->id,
            'purchase_price' => $item->price,
            'unitcost' => $item->price,
            'quantity' => $item->qty,
            'total' => $item->price * $item->qty,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // កូដបន្ទាត់នេះគឺ Update ភ្លាមៗតែម្ដង
        // Product::where('id', $item->id)->increment('product_store', $item->qty);
    }

    Cart::destroy();

    return redirect()->route('purchase.page')->with([
        'message' => 'Purchase completed successfully!',
        'alert-type' => 'success',
    ]);
}

    public function payDueModel(Request $request, $id){
        $purchasepaydue = Purchase::findOrFail($id);
        return view('admin.purchases.purchase_payduepage',compact('purchasepaydue'));

    }
   
    public function PurchaseUpdateDue(Request $request){
        $purchase_id = $request->id;
        $due_amount = $request->due;
        $pay_amount = $request->pay;

        $allpurchase = Purchase::findOrFail($purchase_id);
        $maindue = $allpurchase->due;
        $maindpay = $allpurchase->pay;
 
        $paid_due = $maindue - $due_amount;
        $paid_pay = $maindpay + $due_amount;

        Purchase::findOrFail($purchase_id)->update([
            'due' => $paid_due,
            'pay' => $paid_pay, 
        ]);

         $notification = array(
            'message' => 'Due Amount Updated Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('purchase.pending.due')->with($notification);


    }// End Method 
}