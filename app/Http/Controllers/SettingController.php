<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\informationshop;



use Illuminate\Support\Facades\File; // ត្រូវតែ use File facade


class SettingController extends Controller
{
    public function settingPage(){
        
        return view('admin.setting.setting');
    }// End Method


    public function informationShop()
    {
        $defaults = [
            'name_kh' => 'ឈ្មោះហាង (ខ្មែរ)',
            'name_en' => 'Shop Name (English)',
            'address' => 'Siem Reap, Cambodia',
            'phone' => '012-345-678',
        ];

        // ប្រសិនបើ Model មាន SoftDeletes ហើយអ្នកចង់ទាញតែដែលមិនបាន soft-deleted
        $info = InformationShop::orderBy('created_at')->first();

        if (! $info) {
            // បង្កើតថ្មី (id នឹង auto-increment)
            $info = InformationShop::create($defaults);
        }

        return view('admin.setting.information_shop', compact('info'));
    }


    
    public function update(Request $request)
    {
        
        $request->validate([
            'name_kh' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'note' => 'nullable|string',
            'terms_and_condition' => 'nullable|string',
        ]);

        
        $info = InformationShop::first();

        
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $uploadPath = 'upload/shop_info/';

            
            if ($info->logo && File::exists(public_path($uploadPath . $info->logo))) {
                File::delete(public_path($uploadPath . $info->logo));
            }

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($uploadPath), $imageName);
            $info->logo = $imageName; 
        }

       
        $info->name_kh = $request->name_kh;
        $info->name_en = $request->name_en;
        $info->address = $request->address;
        $info->phone = $request->phone;
        $info->note = $request->note;
        $info->terms_and_condition = $request->terms_and_condition;

        $info->save(); 

        
        $notification = [
            'message' => 'Shop Information Updated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    } // End Method

}
