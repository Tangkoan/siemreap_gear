<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Carbon\Carbon;



class EmployeeContrlloer extends Controller
{
    //
    public function EmployeePage(){
        $employee = Employee::latest()->get();
        return view('admin.employee.admin_employee', compact('employee'));
    } // End Method

    public function AddEmployee(){
        return view('admin.employee.add_employee');
    } // End Method

    public function StoreEmployee(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:employees|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            
            
            'experience' => 'required', 
            'image' => 'required',  
            ],
            [
                'name.required' => 'This Employee Name Field Is Required',
            ]
        );

        $image = $request->file('image');
        if ($image) {
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/employee'), $name_gen);
            $save_url = 'upload/employee/' . $name_gen;

            Employee::insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'experience' => $request->experience,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Employee Inserted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('employee.all')->with($notification);
        } else {
            return redirect()->back()->with('error', 'Image is required');
        }
    }

    public function EditEmployee($id){

        $employee = Employee::findOrFail($id);
        return view('admin.employee.edit_employee',compact('employee'));
    } // End Method 

    public function UpdateEmployee(Request $request){
        $employee_id = $request->id;
        // Validate email to avoid the "email already taken" issue
    $request->validate([
        'email' => 'required|email|unique:employees,email,' . $employee_id,  // Ignore unique check for the current employee
    ]);

        if ($request->file('image')) {
        $image = $request->file('image');
        // $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        // Image::make($image)->resize(300,300)->save('upload/employee/'.$name_gen);
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('upload/employee'), $name_gen);
        $save_url = 'upload/employee/'.$name_gen;

        Employee::findOrFail($employee_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(), 
        ]);

         $notification = array(
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('employee.all')->with($notification); 
             
        } else{
            Employee::findOrFail($employee_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'city' => $request->city, 
            'created_at' => Carbon::now(), 
        ]);
         $notification = array(
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('employee.all')->with($notification); 
        } // End else Condition  
    } // End Method

    public function DeleteEmployee($id){
        $employee_img = Employee::findOrFail($id);
        $img = $employee_img->image;
        unlink($img);
        Employee::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Employee Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    } // End Method 

   


}
