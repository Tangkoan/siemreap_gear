<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{    
    public function BrandPage(){
        $brand = Brand::latest()->get();
        return view('admin.brand.all_brand', compact('brand'));
    } // End Method


    public function AddBrand(){
        return view('admin.brand.add_brand');
    } // End Method

    public function StoreBrand(Request $request)
    {
        $validateData = $request->validate([
            'brand_name' => 'required|max:200|unique:brands,brand_name',
        ],
        [
            'brand_name.required' => 'This Brand Name field is required.',
            'brand_name.unique' => 'This Brand Name already exists.',
        ]);
    
        Brand::insert([
            'brand_name' => $request->brand_name,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = [
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success',
        ];
    
        return redirect()->route('all.brand')->with($notification);
    }

    public function EditBrand($id){

        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit_brand',compact('brand'));
    } // End Method 

    public function UpdateBrand(Request $request){
        $brand_id = $request->id;
    
        $request->validate([
            'brand_name' => [
                'required',
                'max:200',
                Rule::unique('brands', 'brand_name')->ignore($brand_id),
            ],
        ],[
            'brand_name.required' => 'This Brand Name Field is required.',
            'brand_name.unique' => 'This Brand Name already exists.',
        ]);
    
        Brand::findOrFail($brand_id)->update([
            'brand_name' => $request->brand_name,
            'updated_at' => Carbon::now(), 
        ]);
    
        $notification = [
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'success',
        ];
    
        return redirect()->route('all.brand')->with($notification);
    }
public function DeleteBrand($id){
    $brand = Brand::findOrFail($id);
            
            Brand::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Brand Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification); 
        } // End Method 
    
    
        public function searchBrand(Request $request)
        {
            $query = Brand::query();
        
            if ($request->has('search') && $request->search != '') {
                $query->where('brand_name', 'LIKE', '%' . $request->search . '%');
            }
        
            // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
            $query->orderBy('created_at', 'desc');

            $perPage = $request->perPage ?? 10; // ✅ Default = 10
            $isAll = $perPage === 'all';
        
            if ($isAll) {
                $brands = $query->get();
            } else {
                $brands = $query->paginate((int)$perPage);
            }
        
            $table = '';
            foreach ($brands as $key => $item) {
                $table .= '
                <tr class="hover:bg-slate-50 border-b border-slate-200">
                    <td class="p-4 py-5">' . ($key + 1) . '</td>
                    <td class="p-4 py-5">' . $item->brand_name . '</td>
                    <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at)) . '</td>
                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div class="flex items-center gap-x-6">
                           
                        
                        
                        <button class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                                    <a href="' . route('edit.brand', $item->id) . '" >
                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                      </svg>
                                    </a>
                                  </button>
                                  
                        <button type="button" class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                    <a href="' . route('delete.brand', $item->id) . '" id="delete">
                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    </a>
                        </button>
                        
                        
                        
                        
                        </div>
        
                        
                    </td>
                </tr>';
            }
        
            $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $brands->links('pagination::tailwind')->toHtml();
        
            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }
    
    
    
}


