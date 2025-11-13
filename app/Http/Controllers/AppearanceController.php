<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AppearanceController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = $user->appearance_settings ?? [];

        // ✅ START: បន្ថែម Validation Rules សម្រាប់ Input
        $validator = Validator::make($request->all(), [
            // --- Settings ចាស់ (Background & Colors) ---
            'light_primary_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_text_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_bg_type' => 'nullable|in:default,color,image',
            'light_bg_color' => 'nullable|required_if:light_bg_type,color|regex:/^#[0-9a-fA-F]{6}$/',
            'light_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:10000',
            'dark_primary_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_text_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_bg_type' => 'nullable|in:default,color,image',
            'dark_bg_color' => 'nullable|required_if:dark_bg_type,color|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:10000',

            // --- Settings សម្រាប់ Card ---
            'light_card_type' => 'nullable|in:default,solid,gradient',
            'light_card_color1' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_card_opacity' => 'nullable|numeric|min:0|max:100',
            'light_card_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_card_gradient_dir' => 'nullable|string',
            'dark_card_type' => 'nullable|in:default,solid,gradient',
            'dark_card_color1' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_card_opacity' => 'nullable|numeric|min:0|max:100',
            'dark_card_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_card_gradient_dir' => 'nullable|string',

            // --- ✅ START: Settings ថ្មី (Input Background) ---
            'light_input_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_input_opacity' => 'nullable|numeric|min:0|max:100',
            'dark_input_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_input_opacity' => 'nullable|numeric|min:0|max:100',
            // --- ✅ END: Settings ថ្មី ---

            // ✅ START: Settings ថ្មីសម្រាប់ Header (កែពី Color ធម្មតា ទៅជា Full Option)
            'light_header_type' => 'nullable|in:default,solid,gradient,image,blur',
            'light_header_bg_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_header_opacity' => 'nullable|numeric|min:0|max:100',
            'light_header_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/', // For Gradient
            'light_header_gradient_dir' => 'nullable|string',
            'light_header_image' => 'nullable|image|max:5000',
            'light_header_blur' => 'nullable|numeric|min:0|max:50', // For Blur effect

            'dark_header_type' => 'nullable|in:default,solid,gradient,image,blur',
            'dark_header_bg_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_header_opacity' => 'nullable|numeric|min:0|max:100',
            'dark_header_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_header_gradient_dir' => 'nullable|string',
            'dark_header_image' => 'nullable|image|max:5000',
            'dark_header_blur' => 'nullable|numeric|min:0|max:50',

            // ✅ START: Settings ថ្មីសម្រាប់ Sidebar
            'light_sidebar_type' => 'nullable|in:default,solid,gradient,image,blur',
            'light_sidebar_bg_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_sidebar_opacity' => 'nullable|numeric|min:0|max:100',
            'light_sidebar_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_sidebar_gradient_dir' => 'nullable|string',
            'light_sidebar_image' => 'nullable|image|max:5000',
            'light_sidebar_blur' => 'nullable|numeric|min:0|max:50',

            'dark_sidebar_type' => 'nullable|in:default,solid,gradient,image,blur',
            'dark_sidebar_bg_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_sidebar_opacity' => 'nullable|numeric|min:0|max:100',
            'dark_sidebar_color2' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_sidebar_gradient_dir' => 'nullable|string',
            'dark_sidebar_image' => 'nullable|image|max:5000',
            'dark_sidebar_blur' => 'nullable|numeric|min:0|max:50',
        ]);
        // ✅ END: បន្ថែម Validation Rules

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        
        try {
            // 1. Handle Main Background Images (ទុកដដែល)
            if ($request->hasFile('light_bg_image')) {
                $validatedData['light_bg_image'] = $this->uploadImage($request->file('light_bg_image'), $user->id, 'light_bg');
                $validatedData['light_bg_type'] = 'image';
            }
            if ($request->hasFile('dark_bg_image')) {
                $validatedData['dark_bg_image'] = $this->uploadImage($request->file('dark_bg_image'), $user->id, 'dark_bg');
                $validatedData['dark_bg_type'] = 'image';
            }

            // 2. ✅ Handle Header Images
            foreach (['light', 'dark'] as $mode) {
                if ($request->hasFile("{$mode}_header_image")) {
                    $validatedData["{$mode}_header_image"] = $this->uploadImage($request->file("{$mode}_header_image"), $user->id, "{$mode}_header");
                    $validatedData["{$mode}_header_type"] = 'image';
                } elseif ($request->input("{$mode}_header_type") !== 'image') {
                     // Keep old image if logic permits, or clear it based on your preference
                }
            }

            // 3. ✅ Handle Sidebar Images
            foreach (['light', 'dark'] as $mode) {
                if ($request->hasFile("{$mode}_sidebar_image")) {
                    $validatedData["{$mode}_sidebar_image"] = $this->uploadImage($request->file("{$mode}_sidebar_image"), $user->id, "{$mode}_sidebar");
                    $validatedData["{$mode}_sidebar_type"] = 'image';
                }
            }

            $user->appearance_settings = array_merge($settings, $validatedData);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Appearance updated successfully!',
                'settings' => $this->getAppearanceWithDefaults($user),
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Helper function សម្រាប់ Upload រូបភាព (មិនផ្លាស់ប្តូរ)
    private function uploadImage($file, $userId, $prefix)
    {
        $filename = $prefix . '_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'upload/backgrounds/';
        if (!File::exists(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true);
        }
        $file->move(public_path($path), $filename);
        return $path . $filename;
    }

    // Helper function ដើម្បីយក Settings ជាមួយនឹង Default
    private function getAppearanceWithDefaults($user)
    {
        $settings = $user->appearance_settings ?? [];
        
        // ✅ START: បន្ថែម Default Values ថ្មី
        $defaults = [
            // Settings ចាស់
            'light_primary_color' => '#4F46E5', 'light_text_color' => '#1F2937',
            'light_bg_type' => 'default', 'light_bg_color' => '#F3F4F6', 'light_bg_image' => null,
            'dark_primary_color' => '#6366F1', 'dark_text_color' => '#F9FAFB',
            'dark_bg_type' => 'default', 'dark_bg_color' => '#111827', 'dark_bg_image' => null,

            // Settings សម្រាប់ Card
            'light_card_type' => 'default', 'light_card_color1' => '#FFFFFF', 'light_card_opacity' => 80, 'light_card_color2' => '#F9FAFB', 'light_card_gradient_dir' => 'to right',
            'dark_card_type' => 'default', 'dark_card_color1' => '#1F2937', 'dark_card_opacity' => 80, 'dark_card_color2' => '#111827', 'dark_card_gradient_dir' => 'to right',

            // ✅ Settings ថ្មី (Input)
            'light_input_color' => '#FFFFFF',       // Default ពណ៌ស
            'light_input_opacity' => 80,             // Default 80%
            'dark_input_color' => '#1F2937',        // Default ពណ៌ slate-800
            'dark_input_opacity' => 80,              // Default 80%

            // HEADER Defaults
            'light_header_type' => 'default', 'light_header_bg_color' => '#FFFFFF', 'light_header_opacity' => 100, 'light_header_blur' => 10,
            'light_header_color2' => '#F3F4F6', 'light_header_gradient_dir' => 'to right', 'light_header_image' => null,
            
            'dark_header_type' => 'default', 'dark_header_bg_color' => '#1F2937', 'dark_header_opacity' => 100, 'dark_header_blur' => 10,
            'dark_header_color2' => '#111827', 'dark_header_gradient_dir' => 'to right', 'dark_header_image' => null,

            // SIDEBAR Defaults
            'light_sidebar_type' => 'default', 'light_sidebar_bg_color' => '#FFFFFF', 'light_sidebar_opacity' => 100, 'light_sidebar_blur' => 10,
            'light_sidebar_color2' => '#F3F4F6', 'light_sidebar_gradient_dir' => 'to bottom', 'light_sidebar_image' => null,

            'dark_sidebar_type' => 'default', 'dark_sidebar_bg_color' => '#111827', 'dark_sidebar_opacity' => 100, 'dark_sidebar_blur' => 10,
            'dark_sidebar_color2' => '#000000', 'dark_sidebar_gradient_dir' => 'to bottom', 'dark_sidebar_image' => null,
        ];
        // ✅ END: បន្ថែម Default Values ថ្មី

        $merged = array_merge($defaults, $settings);

        // Create URLs for JS
        foreach(['light_bg_image', 'dark_bg_image', 'light_header_image', 'dark_header_image', 'light_sidebar_image', 'dark_sidebar_image'] as $key) {
            $merged[$key . '_url'] = (!empty($merged[$key]) && File::exists(public_path($merged[$key]))) ? asset($merged[$key]) : null;
        }

        // // Logic សម្រាប់ URL រូបភាព (មិនផ្លាស់ប្តូរ)
        // $merged['light_bg_image_url'] = null;
        // $merged['dark_bg_image_url'] = null;
        // if ($merged['light_bg_type'] == 'image' && !empty($merged['light_bg_image'])) {
        //     $merged['light_bg_image_url'] = asset($merged['light_bg_image']);
        // }
        // if ($merged['dark_bg_type'] == 'image' && !empty($merged['dark_bg_image'])) {
        //     $merged['dark_bg_image_url'] = asset($merged['dark_bg_image']);
        // }
        
        return $merged;
    }
}

