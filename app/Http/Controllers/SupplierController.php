<?php

namespace App\Http\Controllers;

use App\Models\supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SupplierController extends Controller
{

    public function SupplierPage(){
        $supplier = Supplier::latest()->get();
        return view('admin.supplier.all_supplier', compact('supplier'));
    } // End Method


    public function AddSupplier(){
        return view('admin.supplier.add_supplier');
    } // End Method

    public function EditSupplier($id){
        $supplier = Supplier::findOrFail($id);
        return view('admin.supplier.edit_supplier', compact('supplier'));
    } // End Method


    public function StoreSupplier(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'nullable|unique:Suppliers|max:200',
            'phone' => 'required|max:200',
            ],
            [
                'name.required' => 'This Supplier Name Field Is Required',
                'phone.required' => 'This Supplier phone Field Is Required',
            ]
        );


        Supplier::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'created_at' => Carbon::now(),
        ]);
       

        $notification = array(
            'message' => 'Supplier Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification);
       
    }
    // End

    public function SupplierUpdate(Request $request){
        $supplier_id = $request->id;
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'nullable|unique:Suppliers|max:200',
            'phone' => 'required|max:200',
            ],
            [
                'name.required' => 'This Supplier Name Field Is Required',
                'phone.required' => 'This Supplier phone Field Is Required',
            ]
        );

        Supplier::findOrFail($supplier_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'updated_at' => Carbon::now(), 
        ]);
       

        $notification = array(
            'message' => 'Supplier Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification);
       
    }// End Method


    

    public function DeleteSupplier($id){
        
        $supplier_id = Supplier::findOrFail($id);
       
        Supplier::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    } // End Method 


    
    
}
