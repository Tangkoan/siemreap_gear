<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
{
    //

    public function AllCategory(){
        $category = Category::latest()->get();
        return view('admin.category.all_category',compact('category'));
    }// End Method

    public function AddCategory(){
        return view('admin.category.add_category');
    } // End Method


    public function StoreCategory(Request $request)
    {
        $validateData = $request->validate([
            'category_name' => 'required|max:200|unique:categories,category_name',
        ],
        [
            'category_name.required' => 'This Category Name field is required.',
            'category_name.unique' => 'This Category Name already exists.',
        ]);
    
        Category::insert([
            'category_name' => $request->category_name,
            'created_at' => Carbon::now(),
        ]);
    
        $notification = [
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success',
        ];
    
        return redirect()->route('all.category')->with($notification);
    }
    



    public function EditCategory($id){
        $category = Category::findOrFail($id);
        return view('admin.category.edit_category',compact('category'));
    } // End Method 


    public function CategoryUpdate(Request $request)
{
    $category_id = $request->id;

    $request->validate([
        'category_name' => 'required|max:200|unique:categories,category_name,' . $category_id,
    ],
    [
        'category_name.required' => 'This Category Name field is required.',
        'category_name.unique' => 'This Category Name already exists.',
    ]);

    Category::findOrFail($category_id)->update([
        'category_name' => $request->category_name,
        'updated_at' => Carbon::now(), // កែជា updated_at បើកំពុង update
    ]);

    $notification = [
        'message' => 'Category Updated Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.category')->with($notification); 
}


    public function DeleteCategory($id){

        $categroy_id = Category::findOrFail($id);

        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification); 
    } // End Method 
}
