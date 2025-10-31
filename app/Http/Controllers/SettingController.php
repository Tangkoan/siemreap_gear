<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\informationshop;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache; // <-- 1. បន្ថែមទីនេះ






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


    
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'name_kh' => 'required|string|max:255',
    //         'name_en' => 'required|string|max:255',
    //         'address' => 'nullable|string',
    //         'phone' => 'nullable|string|max:50',
    //         'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    //         'note' => 'nullable|string',
    //         'terms_and_condition' => 'nullable|string',
    //     ]);

    //     // ទាញ record ឬ បង្កើតមួយថ្មី ប្រសិនបើមិនមានទេ
    //     $info = InformationShop::firstOrCreate([], [
    //         'name_kh' => $request->name_kh,
    //         'name_en' => $request->name_en,
    //         'address' => $request->address,
    //         'phone' => $request->phone,
    //         'note' => $request->note,
    //         'terms_and_condition' => $request->terms_and_condition,
    //     ]);

    //     $uploadPath = 'upload/shop_info/'; // relative to public/

    //     // បង្កើត folder ប្រសិនបើគ្មាន
    //     if (!File::exists(public_path($uploadPath))) {
    //         File::makeDirectory(public_path($uploadPath), 0755, true);
    //     }

    //     if ($request->hasFile('logo')) {
    //         $image = $request->file('logo');

    //         // លុប logo ពីមុន ប្រសិនបើមាន
    //         if ($info->logo && File::exists(public_path($uploadPath . $info->logo))) {
    //             File::delete(public_path($uploadPath . $info->logo));
    //         }

    //         // ឈ្មោះឯកសារ sanitized
    //         $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    //         $safeName = Str::slug($baseName);
    //         $imageName = time() . '_' . $safeName . '.' . $image->getClientOriginalExtension();

    //         $image->move(public_path($uploadPath), $imageName);
    //         $info->logo = $imageName;
    //     }

    //     // អាប់ដេតដែកផ្សេងៗ (មកពី request) — វាអាចប្រើ fill ខ្នងដែរ
    //     $info->name_kh = $request->name_kh;
    //     $info->name_en = $request->name_en;
    //     $info->address = $request->address;
    //     $info->phone = $request->phone;
    //     $info->note = $request->note;
    //     $info->terms_and_condition = $request->terms_and_condition;
    //     $info->save();

    //     $notification = [
    //         'message' => 'Shop Information Updated Successfully!',
    //         'alert-type' => 'success',
    //     ];

    //     return redirect()->back()->with($notification);
    // }


    // app/Http/Controllers/SettingController.php

public function update(Request $request)
    {
        $request->validate([
            'name_kh' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'favicon' => 'nullable|file|mimes:ico,png,jpeg,jpg,gif,webp,svg|max:2048',
            'note' => 'nullable|string',
            'terms_and_condition' => 'nullable|string',
        ]);

        // ទាញ record ឬ បង្កើតមួយថ្មី
        $info = InformationShop::firstOrCreate([], [
            'name_kh' => $request->name_kh,
            'name_en' => $request->name_en,
        ]);

        $uploadPath = 'upload/shop_info/';

        // បង្កើត folder ប្រសិនបើគ្មាន
        if (!File::exists(public_path($uploadPath))) {
            File::makeDirectory(public_path($uploadPath), 0755, true);
        }

        // ========== ដោះស្រាយ Logo Upload ==========
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');

            if ($info->logo && File::exists(public_path($uploadPath . $info->logo))) {
                File::delete(public_path($uploadPath . $info->logo));
            }

            $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($baseName);
            $imageName = time() . '_logo_' . $safeName . '.' . $image->getClientOriginalExtension();

            $image->move(public_path($uploadPath), $imageName);
            $info->logo = $imageName; // <-- កំណត់ឈ្មោះ logo ថ្មី
        }

        // ========== (NEW) ដោះស្រាយ Favicon Upload ==========
        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');

            if ($info->favicon && File::exists(public_path($uploadPath . $info->favicon))) {
                File::delete(public_path($uploadPath . $info->favicon));
            }

            $baseName = pathinfo($faviconFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($baseName);
            $faviconName = time() . '_favicon_' . $safeName . '.' . $faviconFile->getClientOriginalExtension();

            $faviconFile->move(public_path($uploadPath), $faviconName); // <--- ហ្វាល់បាន Upload ត្រឹមនេះ

            // !!!!!!!!!! 99% បញ្ហានៅទីនេះ !!!!!!!!!!
            // អ្នកប្រហែលជាភ្លេចបន្ទាត់ខាងក្រោមនេះ
            // បើគ្មានបន្ទាត់នេះ $info->save() នឹងមិនដឹងថាត្រូវ Save ឈ្មោះថ្មីទេ
            $info->favicon = $faviconName; // <-- កំណត់ឈ្មោះ favicon ថ្មី
        }

        // អាប់ដេត data ផ្សេងៗ
        $info->name_kh = $request->name_kh;
        $info->name_en = $request->name_en;
        $info->address = $request->address;
        $info->phone = $request->phone;
        $info->note = $request->note;
        $info->terms_and_condition = $request->terms_and_condition;
        
        // រក្សាទុកការផ្លាស់ប្តូរទាំងអស់ (រួមទាំង logo និង favicon ថ្មី)
        $info->save(); 

        // Clear cache ដើម្បីឲ្យ ComposerServiceProvider ទាញទិន្នន័យថ្មី
        Cache::forget('shopInfo'); 

        $notification = [
            'message' => 'Shop Information Updated Successfully!',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

}
