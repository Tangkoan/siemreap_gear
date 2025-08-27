<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\Order;
use App\Models\Orderdetails;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Notification; 
use App\Notifications\StockAlertNotification;


use App\Models\Category;
use App\Models\Supplier;
use App\Models\Condition;

use Haruncpi\LaravelIdGenerator\IdGenerator;

use Illuminate\Support\Facades\Auth; // បញ្ជាក់ Auth class
use Illuminate\Support\Str;

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller

{

    public function getStockAlerts()
    {
        $products = Product::whereColumn('product_store', '<=', 'stock_alert')
            
            // ✅ START: បន្ថែម​លក្ខខណ្ឌ​នេះ
            // ✅ START: Add this condition
            ->where('status', '1')
            // ✅ END
            
            // Add the new condition: exclude if product_stock is 0 AND stock_alert is 0
            ->where(function ($query) {
                $query->where('product_store', '!=', 0)
                    ->orWhere('stock_alert', '!=', 0);
            })
            // Corrected select statement: product_stock instead of duplicate product_store
            ->select('product_name', 'product_store', 'stock_alert')
            ->get();

        return response()->json([
            'status' => 'success',
            'products' => $products,
            'count' => $products->count()
        ]);
    }


    // ProductController.php
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $products = Product::where('product_name', 'like', "%$keyword%")->get();

        // Convert image path to URL
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $product->selling_price,
                'imageUrl' => asset($product->product_image),
            ];
        });

        return response()->json([
            'products' => $products
        ]);
    }


    //
    public function ProductPage(){
       
        $product = Product::latest()->get();
        return view('admin.product.all_product', compact('product'));
    }// End Method



    // AddProduct
    public function AddProduct(){

        // $category = Category::latest()->get();
        // $supplier = Supplier::latest()->get();

        $category = Category::orderBy('category_name', 'asc')->get();
        $supplier = Supplier::orderBy('name', 'asc')->get();
        $condition = Condition::orderBy('condition_name', 'asc')->get();

        return view('admin.product.add_product',compact('category','supplier','condition'));
    }// End Method 
    
    
    
    public function StoreProduct(Request $request){ 

        do {
            $pcode = IdGenerator::generate([
                'table' => 'products',
                'field' => 'product_code',
                'length' => 5,
                'prefix' => 'SR-'
            ]);
        } while (Product::where('product_code', $pcode)->exists());

        DB::beginTransaction();
        try {
            // Default null if no image
            $image_path = $request->input('product_image') ?? null;

            // Handle image upload if available
            if ($request->hasFile('product_image')) {
                $image = $request->file('product_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/product/'), $name_gen);
                $image_path = 'upload/product/' . $name_gen;
            }
        

            // $image = $request->file('product_image');
    
            // $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            // $image->move(public_path('upload/product/'), $name_gen);
    
            // $save_url = 'upload/product/'.$name_gen;
            Product::insert([
                'product_name' => $request->product_name,
                'stock_alert' => $request->stock_alert,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'condition_id' => $request->condition_id,
                'product_code' => $pcode,
                'product_store' => $request->product_store,
                'product_detail' => $request->product_detail,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'status' => $request->status ?? '1', // ✅ បន្ថែម status
                'product_image' => $image_path,
                'created_at' => Carbon::now(), 
            ]);

            DB::commit();

        } catch (\Exception $e) {
            \Log::error('Error storing product: ' . $e->getMessage()); // ជាការល្អគួរ Log error ទុក
        
            $notification = array(
                'message' => 'Something went wrong: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification)->withInput();
        }

        $notification = array(
            'message' => __('messages.product_inserted_successfully'),
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification);
    } // End Method 
                
        // In ProductController.php

    public function UpdateProduct(Request $request)
    {
        $product_id = $request->id;

        // ✅ 1. បន្ថែម Validation ដើម្បី​ធានា​ថា​ទិន្នន័យ​ត្រឹមត្រូវ
        // ✅ 1. Add validation to ensure data integrity
        $validatedData = $request->validate([
            'product_name'   => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'condition_id'   => 'required|exists:conditions,id',
            'product_code'   => 'required|string',
            'product_store'  => 'required|integer',
            'buying_price'   => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'stock_alert'    => 'required|integer',
            'product_detail' => 'nullable|string',
            'product_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'         => 'required|in:0,1', // ត្រូវប្រាកដថា status ត្រូវបានส่งมา
        ]);

        try {
            $product = Product::findOrFail($product_id);

            // ✅ 2. រៀបចំ Data Array តែ​ម្តង​គត់ (No code repetition)
            // ✅ 2. Prepare the data array only once
            $updateData = $validatedData;

            if ($request->file('product_image')) {
                // លុប​រូបភាព​ចាស់ (ถ้ามี)
                if ($product->product_image && file_exists(public_path($product->product_image))) {
                    unlink(public_path($product->product_image));
                }

                // Upload រូបភាព​ថ្មី
                $image = $request->file('product_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/product/'), $name_gen);
                $updateData['product_image'] = 'upload/product/' . $name_gen;
            }

            // ✅ 3. Update ទិន្នន័យ​ទាំងអស់​ក្នុង​ពេល​តែ​មួយ
            // ✅ 3. Update all data at once
            $product->update($updateData);
            // Eloquent នឹង​จัดการ updated_at ដោយ​ស្វ័យប្រវត្តិ មិន​ចាំបាច់​ដាក់ Carbon::now() ទេ
            // Eloquent will handle updated_at automatically, no need for Carbon::now()

            $notification = [
                'message'    => __('messages.product_updated_successfully'),
                'alert-type' => 'success'
            ];

            return redirect()->route('all.product')->with($notification);

        } catch (\Exception $e) {
            // បើ​មាន​បញ្ហា Log វា​ទុក
            \Log::error('Product Update Error: ' . $e->getMessage());

            $notification = [
                'message'    => 'An error occurred during the update.',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification)->withInput();
        }
    }
        

    public function DeleteProduct($id)
        {
            $product = Product::findOrFail($id);
            $img = $product->product_image;

            // បញ្ហា: ត្រូវពិនិត្យទាំង purchase_details (PurchaseItem)
            $hasPurchaseDetails = \App\Models\purchase_details::where('product_id', $id)->exists();

            // បញ្ហា: ត្រូវពិនិត្យទាំង orderDetails ប្រសិនបើវាដូចជា Sale Detail
            $hasOrderDetails = $product->orderDetails()->exists();

            if ($hasPurchaseDetails || $hasOrderDetails) {
                $notification = array(
                    'message' => __('messages.cannot_delete_product'),
                    'alert-type' => 'error'
                );
                return redirect()->route('all.product')->with($notification);
            }

            // Delete image if it exists
            if ($img && file_exists($img)) {
                unlink($img);
            }

            $product->delete();

            $notification = array(
                'message' => __('messages.product_deleted_successfully'),
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
    }

    public function EditProduct($id){
                $product = Product::findOrFail($id);
                $category = Category::orderBy('category_name', 'asc')->get();
                $supplier = Supplier::orderBy('name', 'asc')->get();
                $condition = Condition::orderBy('condition_name', 'asc')->get();

                return view('admin.product.edit_product',compact('product','category','supplier','condition'));
    } // End Method


    public function DetailProduct($id){
                $product = Product::findOrFail($id);
                $category = Category::orderBy('category_name', 'asc')->get();
                $supplier = Supplier::orderBy('name', 'asc')->get();
                $condition = Condition::orderBy('condition_name', 'asc')->get();

                return view('admin.product.detail_product', compact('product', 'category', 'supplier','condition'));

    } // End Method


    public function searchProduct(Request $request)
            {
                $query = Product::query();

                if ($request->has('search') && $request->search != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('product_name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('product_code', 'LIKE', '%' . $request->search . '%')
                        ->orWhereHas('category', function ($cat) use ($request) {
                                $cat->where('category_name', 'LIKE', '%' . $request->search . '%');
                                })
                        ->orWhere('product_store', 'LIKE', '%' . $request->search . '%');
                    });
                }

                // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
                $query->orderBy('created_at', 'desc');

                $perPage = $request->perPage ?? 10; // ✅ Default = 10
                $isAll = $perPage === 'all';

                if ($isAll) {
                    $categories = $query->get();
                } else {
                    $categories = $query->paginate((int)$perPage);
                }

                $table = '';
                foreach ($categories as $key => $item) {
                    $editBtn = '';
                    $deleteBtn = '';
                    $barcodeBtn = '';
                    $viewBtn = '';
                    
                    
                    // ✅ Edit Button
                    if (Auth::user()->can('product.edit')) {
                        $editBtn = '
                        <button class="icon-edit  transition-colors duration-200 dark:hover:text-blue-900  hover:text-blue-900 focus:outline-none">
                            <a href="' . route('edit.product', $item->id) . '">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652
                                        L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685
                                        a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125
                                        M18 14v4.75A2.25 2.25 0 0115.75 21H5.25
                                        A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                        </button>';
                    } else {
                        // Disabled Edit Button (grey)
                        $editBtn = '
                        <button class=" text-gray-400 cursor-not-allowed" disabled title="No permission to edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652
                                    L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685
                                    a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125
                                    M18 14v4.75A2.25 2.25 0 0115.75 21H5.25
                                    A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>';
                    }
                    
                    // ✅ barcode Button
                    if (Auth::user()->can('product.barcode')) {
                        $barcodeBtn = '
                        <button class="icon-edit dark:hover:text-blue-900  hover:text-blue-900  transition-colors duration-200  focus:outline-none">
                            <a href="' . route('barcode.product', $item-> id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14m3-14v14m4-14v14m4-14v14m3-14v14m3-14v14" />
                                </svg>
                            </a>
                        </button>';
                    } else {
                        // Disabled Delete Button (grey)
                        $barcodeBtn = '
                        <button type="button" class=" text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14m3-14v14m4-14v14m4-14v14m3-14v14m3-14v14" />
                                </svg>
                        </button>';
                    }

                    // ✅ View Button
                    if (Auth::user()->can('product.details')) {
                        $viewBtn = '
                        <button class="icon-detail dark:hover:text-green-900  hover:text-green-900  transition-colors duration-200  focus:outline-none">
                            <a href="' . route('detail.product', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                            </a>
                        </button>';
                    } else {
                        // Disabled View Button (grey)
                        $viewBtn = '
                        <button type="button" class=" text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>';
                    }


                    // ✅ Delete Button
                    if (Auth::user()->can('product.delete')) {
                        $deleteBtn = '
                        <button class="icon-delete  transition-colors duration-200 dark:hover:text-red-900  hover:text-red-900 focus:outline-none">
                            <a href="' . route('delete.product', $item->id) . '" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21
                                        c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673
                                        a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0
                                        01-2.244-2.077L4.772 5.79m14.456 0
                                        a48.108 48.108 0 00-3.478-.397m-12 .562
                                        c.34-.059.68-.114 1.022-.165m0 0
                                        a48.11 48.11 0 013.478-.397m7.5 0v-.916
                                        c0-1.18-.91-2.164-2.09-2.201
                                        a51.964 51.964 0 00-3.32 0
                                        c-1.18.037-2.09 1.022-2.09 2.201v.916
                                        m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </a>
                        </button>';
                    } else {
                        // Disabled Delete Button (grey)
                        $deleteBtn = '
                        <button type="button" class=" text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21
                                    c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673
                                    a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0
                                    01-2.244-2.077L4.772 5.79m14.456 0
                                    a48.108 48.108 0 00-3.478-.397m-12 .562
                                    c.34-.059.68-.114 1.022-.165m0 0
                                    a48.11 48.11 0 013.478-.397m7.5 0v-.916
                                    c0-1.18-.91-2.164-2.09-2.201
                                    a51.964 51.964 0 00-3.32 0
                                    c-1.18.037-2.09 1.022-2.09 2.201v.916
                                    m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>';
                    }

                    // ✅ START: កូដ​ថ្មី​សម្រាប់​បង្ហាញ​តែ​អក្សរ Status
                        $statusText = $item->status == '1' ? 'Active' : 'Disable';
                        $statusBadgeClass = $item->status == '1'
                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';

                        $statusDisplay = <<<HTML
                            <span class="px-2 py-1 text-xs  rounded-md {$statusBadgeClass}">
                                {$statusText}
                            </span>
                        HTML;
                        // ✅ END
                    

                    

                    $table .= '
                    <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                        <td class="p-4 py-5">' . ($key + 1) . '</td>
                        
                        <td class="p-4 py-5">
                            <img src="' . asset($item->product_image ? $item->product_image : 'upload/no_image.jpg') . '" alt="Product Image" class="rounded-md" style="width: 40px; height: 40px; object-fit: cover; object-position: center;" />
                        </td>

                        <td class="p-4 py-5">' . $item->product_code . '</td>
                       <td class="p-4 py-5">' . Str::limit($item->product_name, 13) . '</td>
                        <td class="p-4 py-5">' . $item['category']['category_name'] . '</td>
                        <td class="p-4 py-5">' . $item['condition']['condition_name'] . '</td>
                        <td class="p-4 py-5">' . $item->selling_price.'$'  . '</td>
                        <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                        
                        <td class="p-4 py-5 text-center align-middle">
                            <span class="inline-block px-3 py-1 rounded-md bg-green-600 text-white  shadow-sm
                                        ">
                                '. $item->product_store  .'
                            </span>
                        
                        
                        </td>

                        <td class="p-4 py-5">' . $statusDisplay . '</td>
                        

                      
                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-x-6">
                                ' . $editBtn . $barcodeBtn . $viewBtn . $deleteBtn . '
                            </div>
                        </td>
                    </tr>';
                }

                $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $categories->links('pagination::tailwind')->toHtml();

                return response()->json([
                    'table' => $table,
                    'pagination' => $pagination
                ]);
    }

    public function BarcodeProduct($id){
        $product = Product::findOrFail($id);
        return view('admin.product.barcode_product',compact('product'));
    }// End Method


    //
    public function ImportProduct(){
            return view('admin.product.import_product');
    }// End Method 

    public function Export(){
        return Excel::download(new ProductExport,'products.xlsx');
    }
    // End Export

    public function Import(Request $request){
            
        Excel::import(new ProductImport, $request->file('import_file'));
        // Excel::import(new ProductImport, $request->file('import_file'), null, \Maatwebsite\Excel\Excel::XLSX);


        $notification = array(
            'message' => 'Product Import Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }


    // ✅ Function ថ្មីសម្រាប់ Update status ដោយប្រើ AJAX
    public function updateProductStatus(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->status = $product->status == '1' ? '0' : '1';
            $product->save();

            return response()->json(['success' => true, 'newStatus' => $product->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getProductDetails($id)
    {
        try {
            // Eager load a relation category and supplier for more details
            $product = Product::with(['category', 'supplier'])
                              ->findOrFail($id);

            // បង្កើត Image URL ពេញលេញ (កែសម្រួលតាមរចនាសម្ព័ន្ធរបស់អ្នក)
            $product->imageUrl = $product->product_image ? asset($product->product_image) : null;

            return response()->json($product);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
    }
}
