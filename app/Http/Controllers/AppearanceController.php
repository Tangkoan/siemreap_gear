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

            // ✅ បន្ថែមថ្មី៖ សម្រាប់ Header & Sidebar
            'light_header_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_sidebar_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_header_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_sidebar_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
        ]);
        // ✅ END: បន្ថែម Validation Rules

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        
        try {
            
            // --- Logic ផ្ទុករូបភាព (មិនផ្លាស់ប្តូរ) ---
            if ($request->hasFile('light_bg_image')) {
                if (!empty($settings['light_bg_image']) && File::exists(public_path($settings['light_bg_image']))) {
                    File::delete(public_path($settings['light_bg_image']));
                }
                $validatedData['light_bg_image'] = $this->uploadImage($request->file('light_bg_image'), $user->id, 'light');
                $validatedData['light_bg_type'] = 'image';
            } elseif ($request->input('light_bg_type') !== 'image') {
                if (!empty($settings['light_bg_image']) && File::exists(public_path($settings['light_bg_image']))) {
                    File::delete(public_path($settings['light_bg_image']));
                }
                $validatedData['light_bg_image'] = null;
            }
            if ($request->hasFile('dark_bg_image')) {
                if (!empty($settings['dark_bg_image']) && File::exists(public_path($settings['dark_bg_image']))) {
                    File::delete(public_path($settings['dark_bg_image']));
                }
                $validatedData['dark_bg_image'] = $this->uploadImage($request->file('dark_bg_image'), $user->id, 'dark');
                $validatedData['dark_bg_type'] = 'image';
            } elseif ($request->input('dark_bg_type') !== 'image') {
                if (!empty($settings['dark_bg_image']) && File::exists(public_path($settings['dark_bg_image']))) {
                    File::delete(public_path($settings['dark_bg_image']));
                }
                $validatedData['dark_bg_image'] = null;
            }
            // --- បញ្ចប់ Logic ផ្ទុករូបភាព ---

            // បញ្ចូលទិន្នន័យថ្មីទៅក្នុងទិន្នន័យចាស់
            $user->appearance_settings = array_merge($settings, $validatedData);
            $user->save();

            // ត្រឡប់ការកំណត់ដែលបាន Update ទាំងអស់
            $finalSettings = $this->getAppearanceWithDefaults($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Appearance updated successfully!',
                'settings' => $finalSettings,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper function សម្រាប់ Upload រូបភាព (មិនផ្លាស់ប្តូរ)
    private function uploadImage($file, $userId, $prefix)
    {
        $filename = 'user_bg_' . $prefix . '_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
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

            // ✅ បន្ថែមថ្មី៖ Default Values សម្រាប់ Header & Sidebar
            'light_header_color' => '#FFFFFF',  // ពណ៌ស
            'light_sidebar_color' => '#FFFFFF', // ពណ៌ស
            
            'dark_header_color' => '#1F2937',   // Dark Gray
            'dark_sidebar_color' => '#111827',  // Darker Gray
        ];
        // ✅ END: បន្ថែម Default Values ថ្មី

        $merged = array_merge($defaults, $settings);

        // Logic សម្រាប់ URL រូបភាព (មិនផ្លាស់ប្តូរ)
        $merged['light_bg_image_url'] = null;
        $merged['dark_bg_image_url'] = null;
        if ($merged['light_bg_type'] == 'image' && !empty($merged['light_bg_image'])) {
            $merged['light_bg_image_url'] = asset($merged['light_bg_image']);
        }
        if ($merged['dark_bg_type'] == 'image' && !empty($merged['dark_bg_image'])) {
            $merged['dark_bg_image_url'] = asset($merged['dark_bg_image']);
        }
        
        return $merged;
    }
}

