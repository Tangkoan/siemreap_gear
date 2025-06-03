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
            'message' => 'Category Inserted Successfully',
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
    }


