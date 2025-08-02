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
use Illuminate\Support\Facades\Validator;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class PurchaseController extends Controller
{
    public function storeProductAjax(Request $request)
    {
        // Part 1: Validation
        // We use Validator::make() to have full control over the JSON response.
        $validator = Validator::make($request->all(), [
            'product_name'  => 'required|string|max:255|unique:products,product_name',
            'category_id'   => 'required|integer|exists:categories,id', // Assumes 'categories' table
            'supplier_id'   => 'required|integer|exists:suppliers,id', // Assumes 'suppliers' table
            'condition_id'  => 'required|integer|exists:conditions,id', // Assumes 'conditions' table
            'buying_price'  => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'product_store' => 'required|string|max:255',
            'stock_alert'   => 'nullable|integer|min:0',
            'product_detail'=> 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Image validation rules
        ],[], // 👈 Argument ទី 3 សម្រាប់ custom messages (ទុកឱ្យនៅទទេប្រសិនបើមិនត្រូវការ)
        [
            // 👇 Argument ទី 4 សម្រាប់ custom attributes
            'buying_price' => 'cost', 
            'selling_price' => 'price', 
        ]);

        // If validation fails, return errors as JSON with a 422 status code
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Use a database transaction to ensure all or no data is saved.
        DB::beginTransaction();

        // Part 2: Save data and handle potential errors
        try {
            // Generate a unique product code, ensuring it doesn't already exist.
            do {
                $pcode = IdGenerator::generate([
                    'table'  => 'products',
                    'field'  => 'product_code',
                    'length' => 5,
                    'prefix' => 'SR-'
                ]);
            } while (Product::where('product_code', $pcode)->exists());

            // Handle the image upload if a file is present
            $image_path = null;
            if ($request->hasFile('product_image')) {
                $image = $request->file('product_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/product/'), $name_gen);
                $image_path = 'upload/product/' . $name_gen;
            }

            // Create the product using Eloquent's create() method.
            // This requires you to set the `$fillable` property in your Product model.
            $product = Product::create([
                'product_name'  => $request->product_name,
                'category_id'   => $request->category_id,
                'supplier_id'   => $request->supplier_id,
                'condition_id'  => $request->condition_id,
                'buying_price'  => $request->buying_price,
                'selling_price' => $request->selling_price,
                'product_store' => $request->product_store,
                'stock_alert'   => $request->stock_alert,
                'product_detail'=> $request->product_detail,
                'product_code'  => $pcode,
                'product_image' => $image_path,
                'status'        => $request->status ?? '1', // Default to 'active' if not provided
            ]);

            // If everything is successful, commit the changes to the database.
            DB::commit();

            // Return a successful JSON response with the newly created product data.
            return response()->json([
                'message' => __('messages.product_inserted_successfully'),
                'product' => $product // Sending the new product object is useful for the frontend
            ], 201); // 201 'Created' is a more appropriate status code here.

        } catch (\Exception $e) {
            // If any error occurs, roll back the entire transaction.
            DB::rollBack();

            // Part 3: Exception Handling
            // Log the detailed error for debugging purposes.
            Log::error('Error storing product via AJAX: ' . $e->getMessage());

            // Return a generic error message to the user.
            return response()->json([
                'errors' => ['database' => __('messages.product_insert_failed')]
            ], 500); // 500 'Internal Server Error' status code.
        }
    }

    public function storeSupplierAjax(Request $request)
    {
        // Part 1: Validation
        // Using Validator::make() to control the response manually
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:suppliers,name',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            // You can put custom messages here
            'name.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
            'name.unique'  => __('validation.unique', ['attribute' => __('validation.attributes.name')]),
        ]);

        // Check if Validation Fails
        if ($validator->fails()) {
            // Send errors back to JavaScript as JSON with Status Code 422
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Part 2: Save data and successful response
        // Using try...catch to handle database errors
        try {
            // Using Supplier::create() is an Eloquent best practice
            $supplier = Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                // create() will automatically fill created_at, no need to reload page
            ]);

            // Changed from redirect() to response()->json()
            return response()->json([
                'message' => __('messages.supplier_inserted_successfully'),
                'newSupplier' => [ // This data must be sent to JS
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                ]
            ], 200);
        } catch (\Exception $e) {
            // Part 3: Handling Exceptions
            Log::error('Error saving supplier: ' . $e->getMessage());
            return response()->json([
                'errors' => ['database' => __('messages.supplier_insert_failed')]
            ], 500);
        }
    }

    // List all due purchases
    public function PendingDue()
    {
        $alldue = Purchase::where('due', '>', '0')
            ->orderBy('id', 'DESC')->get();
        return view('admin.purchases.pending_due', compact('alldue'));
    }

    public function PurchaseViewDetails($purchase_id)
    {
        $purchase = Purchase::where('id', $purchase_id)->first();
        $purchaseItem = purchase_details::with('product')->where('purchase_id', $purchase_id)->orderBy('id', 'DESC')->get();
        return view('admin.purchases.purchase_view_details', compact('purchase', 'purchaseItem'));
    }
    
    // List all complete purchases
    public function CompletePurchase()
    {
        $purchases = Purchase::where('purchase_status', 'complete')->get();
        return view('admin.purchases.purchase_complete', compact('purchases'));
    }
    
    // AJAX search for complete purchases
    public function searchCompletePurchase(Request $request)
    {
        $query = Purchase::where('purchase_status', 'complete');
        if ($request->has('search') && $request->search != '') {
             $query->whereHas('supplier', function ($cat) use ($request) { // Corrected from 'suppier' to 'supplier'
                $cat->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }
        $query->orderBy('created_at', 'desc');
        $perPage = $request->perPage ?? 10;
        $isAll = $perPage === 'all';
        $purchases = $isAll ? $query->get() : $query->paginate((int)$perPage);
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
                    <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white  font-semibold shadow-sm">' . $item->purchase_status  . '</span>
                </td>
            </tr>';
        }
        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $purchases->links('pagination::tailwind')->toHtml();
        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }

    // AJAX search for due purchases
    public function searchPendingDue(Request $request)
    {
        $query = Purchase::where('due', '>', 0);
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('supplier', function ($cat) use ($request) {
                $cat->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }
        $query->orderBy('created_at', 'desc');
        $perPage = $request->perPage ?? 10;
        $isAll = $perPage === 'all';
        $purchases = $isAll ? $query->get() : $query->paginate((int)$perPage);
        $table = '';
        foreach ($purchases as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                <td class="p-4 py-5">' . $item->purchase_date  . '</td>
                <td class="p-4 py-5">' . $item->payment_status  . '</td>
                <td class="p-4 py-5">
                    <span class="inline-block px-3 py-1 rounded-md bg-gray-500 text-white font-semibold shadow-sm">' . $item->total . ' $</span>
                </td>
                <td class="p-4 py-5 ">
                    <span class="inline-block px-3 py-1 rounded-md bg-red-500 text-white font-semibold shadow-sm">' . $item->pay . ' $</span>
                </td>
                <td class="p-4 py-5 text-center align-middle">
                    <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white font-semibold shadow-sm">' . $item->due . ' $</span>
                </td>
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                        <button type="button" class="icon-detail dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                            <a href="' . route('purchase.view.details', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </a>
                        </button>
                        <button type="button" class="icon-edit dark:hover:text-green-900  hover:text-green-900 text-gray-500 transition-colors duration-200   focus:outline-none">
                            <a href="' . route('paydue.due', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                            </a>
                        </button>
                    </div>
                </td>
            </tr>';
        }
        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $purchases->links('pagination::tailwind')->toHtml();
        return response()->json(['table' => $table, 'pagination' => $pagination]);
    }
    
    // Main page for adding a purchase
    public function PurchasePage()
    {
        $products = Product::latest()->get();
        $categories = Category::all();
        $conditions = Condition::orderBy('condition_name', 'asc')->get();
        $supplier = Supplier::orderBy('name', 'ASC')->get();
        return view('admin.purchases.add_purchase', compact('products', 'supplier', 'categories', 'conditions'));
    }

    // Get products for the purchase page grid, with filters
    public function getProductsForPurchase(Request $request)
    {
        $query = Product::with(['category', 'condition'])->where("status", 1);
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('condition_id') && $request->condition_id !== 'all') {
            $query->where('condition_id', $request->condition_id);
        }
        $products = $query->latest()->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'buying_price' => (float) $product->buying_price,
                'code' => $product->product_code,
                'category' => $product->category ? $product->category->category_name : 'No Category',
                'imageUrl' => asset($product->product_image),
                'stock' => $product->product_store,
                'condition' => $product->condition ? $product->condition->condition_name : 'N/A'
            ];
        });
        return response()->json(['products' => $products]);
    }
    
    // Add item to cart
    public function AddToCart(Request $request)
    {
        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'weight' => 0,
            'options' => [],
        ]);
        return response()->json([
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }

    // Remove item from cart
    public function RemoveCartItem($rowId)
    {
        Cart::remove($rowId);
        return response()->json([
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }
    
    // Update item quantity in cart
    public function UpdateCartItem(Request $request, $rowId)
    {
        $qty = $request->qty;
        if ($qty <= 0) {
            Cart::remove($rowId);
        } else {
            Cart::update($rowId, $qty);
        }
        return response()->json([
            'cart_content' => Cart::content(),
            'cart_subtotal' => Cart::subtotal(),
        ]);
    }

    public function StorePurchase(Request $request){
        $cartItems = Cart::content();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with([
                'message' => __('messages.please_select_product_for_purchase'),
                'alert-type' => 'error',
            ]);
        }

        $subTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $discount = floatval($request->discount ?? 0);
        $paid = floatval($request->pay);

        if ($discount >= $subTotal) {
            return redirect()->back()->withInput()->with([
                'message' => __('messages.discount_cannot_exceed_subtotal') . ' (' . number_format($subTotal, 2) . ')',
                'alert-type' => 'error',
            ]);
        }

        $finalTotal = $subTotal - $discount;
        $due = max($finalTotal - $paid, 0);

        $data = [
            'supplier_id' => $request->supplier_id,
            'purchase_date' => Carbon::now(),
            'invoice_no' => $request->invoice_no, // ✅ បញ្ចូលដោយដៃ
            'purchase_status' => 'complete', // ✅ ប្តូរទៅ complete ដោយស្វ័យប្រវត្តិ
            'discount' => $discount,
            'total_products' => $cartItems->count(),
            'sub_total' => $subTotal,
            'vat' => 0,
            'total' => $finalTotal,
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

            // ✅ បន្ថែមចំនួនចូលក្នុងស្តុក
            Product::where('id', $item->id)->increment('product_store', $item->qty);
    }

    Cart::destroy();

    return redirect()->route('purchase.page')->with([
        'message' => __('messages.purchase_completed_successfully'),
        'alert-type' => 'success',
    ]);
    }

    // Pay due amount modal page
    public function payDueModel(Request $request, $id)
    {
        $purchasepaydue = Purchase::findOrFail($id);
        return view('admin.purchases.purchase_payduepage', compact('purchasepaydue'));
    }
    
    // Update due amount
    public function PurchaseUpdateDue(Request $request)
    {
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
            'message' => __('messages.due_amount_updated_successfully'),
            'alert-type' => 'success'
        );
        return redirect()->route('purchase.pending.due')->with($notification);
    }

    // Search products for the purchase grid
    public function searchPurchaseProducts(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Product::where('status', '1');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('product_code', 'LIKE', "%{$keyword}%");
            });
        }

        $products = $query->latest()->get()->map(function ($product) {
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

}