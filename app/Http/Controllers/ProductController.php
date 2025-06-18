<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Supplier;
use Haruncpi\LaravelIdGenerator\IdGenerator;



use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ProductController extends Controller

{


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

        return view('admin.product.add_product',compact('category','supplier'));
       }// End Method 
    
    
       // Store Product
       public function StoreProduct(Request $request){ 
     
            $pcode = IdGenerator::generate(['table' => 'products','field' => 'product_code','length' => 6, 'prefix' => 'SR_GEAR' ]);
    
            $image = $request->file('product_image');
    
            // $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            // Image::make($image)->resize(300,300)->save('upload/product/'.$name_gen);
    
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/product/'), $name_gen);
    
            $save_url = 'upload/product/'.$name_gen;
            Product::insert([
                'product_name' => $request->product_name,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'product_code' => $pcode,
                'product_store' => $request->product_store,
                'buying_date' => $request->buying_date,
                'product_detail' => $request->product_detail,
                'buying_price' => $request->buying_price,
                'selling_price' => $request->selling_price,
                'cost' => $request->cost,
                'product_image' => $save_url,
                'created_at' => Carbon::now(), 
            ]);
             $notification = array(
                'message' => 'Product Inserted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.product')->with($notification); 
        } // End Method 
    
        public function EditProduct($id){
                $product = Product::findOrFail($id);
                $category = Category::latest()->get();
                $supplier = Supplier::latest()->get();
                return view('admin.product.edit_product',compact('product','category','supplier',));
            } // End Method 
            

            public function DetailProduct($id){
                $product = Product::findOrFail($id);
                $category = Category::latest()->get();
                $supplier = Supplier::latest()->get();
                return view('admin.product.detail_product',compact('product','category','supplier',));
            } // End Method 

            
        public function UpdateProduct(Request $request){
                $product_id = $request->id;
                if ($request->file('product_image')) {
                $image = $request->file('product_image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/product/'), $name_gen);
                $save_url = 'upload/product/'.$name_gen;
                Product::findOrFail($product_id)->update([

                    'product_name' => $request->product_name,
                    'category_id' => $request->category_id,
                    'supplier_id' => $request->supplier_id,
                    'product_detail' => $request->product_detail,
                    'product_code' => $request->product_code,
                    'product_store' => $request->product_store,
                    'buying_date' => $request->buying_date,
                    'buying_price' => $request->buying_price,
                    'selling_price' => $request->selling_price,
                    'cost' => $request->cost,
                    'product_image' => $save_url,
                    'updated_at' => Carbon::now(),
                ]);
                $notification = array(
                    'message' => 'Product Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('all.product')->with($notification); 
                    
                } else{
                    Product::findOrFail($product_id)->update([

                    'product_name' => $request->product_name,
                    'category_id' => $request->category_id,
                    'supplier_id' => $request->supplier_id,
                    'product_code' => $request->product_code,
                    'product_store' => $request->product_store,
                    'buying_date' => $request->buying_date,
                    'product_detail' => $request->product_detail,
                    'buying_price' => $request->buying_price,
                    'selling_price' => $request->selling_price,
                    'cost' => $request->cost,
                    'updated_at' => Carbon::now(),
                ]);
                $notification = array(
                    'message' => 'Product Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('all.product')->with($notification); 
                } // End else Condition  
        } // End Method 
        

        public function DeleteProduct($id){
            $product = Product::findOrFail($id);
            $img = $product->product_image;
        
            // បើមានរូបភាព និង file មានស្ថិតនៅលើ server នោះទើបលុប
            if ($img && file_exists($img)) {
                unlink($img);
            }
        
            // បន្ទាប់មកលុប product
            $product->delete();
        
            $notification = array(
                'message' => 'Product Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification); 
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
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                <td class="p-4 py-5">
                    <img src="' . asset($item->product_image) . '" alt="Product Image" class="rounded-md" style="width: 64px; height: 64px; object-fit: cover; object-position: center;" />
                </td>

                <td class="p-4 py-5">' . $item->product_code . '</td>
                <td class="p-4 py-5">' . $item->product_name . '</td>
                <td class="p-4 py-5">' . $item['category']['category_name'] . '</td>
                <td class="p-4 py-5">' . $item->selling_price  . '</td>
                <td class="p-4 py-5">' . $item['supplier']['name'] . '</td>
                <td class="p-4 py-5">' . $item->product_store  . '</td> 
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    
                    <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                                <a href="' . route('edit.product', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                </a>
                    </button>

                    <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                                <a href="' . route('barcode.product', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14m3-14v14m4-14v14m4-14v14m3-14v14m3-14v14" />
                        </svg>
                                </a>
                    </button>

                    
                    <button type="button" class="icon-detail text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                <a href="' . route('detail.product', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </a>
                    </button>
                            
                    <button type="button" class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                <a href="' . route('delete.product', parameters: $item->id) . '" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                </a>
                    </button>
                    
                    
                    
                    
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
        
}
