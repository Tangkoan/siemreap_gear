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
        $settings = $user->appearance_settings ?? []; // យកការកំណត់ចាស់ (បើមាន)

        // Validation Rules ថ្មី
        $validator = Validator::make($request->all(), [
            'light_primary_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_text_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'light_bg_type' => 'nullable|in:default,color,image',
            'light_bg_color' => 'nullable|required_if:light_bg_type,color|regex:/^#[0-9a-fA-F]{6}$/',
            'light_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ដក required_if ចេញ

            'dark_primary_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_text_color' => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_bg_type' => 'nullable|in:default,color,image',
            'dark_bg_color' => 'nullable|required_if:dark_bg_type,color|regex:/^#[0-9a-fA-F]{6}$/',
            'dark_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ដក required_if ចេញ
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        
        try {
            
            // --- START FIX (កែសម្រួល Logic ផ្ទុក​រូបភាព) ---

            // 1. ដំណើរការ Upload រូបភាព (Light Mode)
            if ($request->hasFile('light_bg_image')) {
                // លុបរូបចាស់ (បើមាន)
                if (!empty($settings['light_bg_image']) && File::exists(public_path($settings['light_bg_image']))) {
                    File::delete(public_path($settings['light_bg_image']));
                }
                $validatedData['light_bg_image'] = $this->uploadImage($request->file('light_bg_image'), $user->id, 'light');
                // បង្ខំឲ្យ Type ក្លាយជា 'image' ពេល Upload ជោគជ័យ
                $validatedData['light_bg_type'] = 'image';
            } elseif ($request->input('light_bg_type') !== 'image') {
                // បើ User ប្តូរទៅ 'default' ឬ 'color', ត្រូវលុបរូបចាស់ និង clear path
                if (!empty($settings['light_bg_image']) && File::exists(public_path($settings['light_bg_image']))) {
                    File::delete(public_path($settings['light_bg_image']));
                }
                $validatedData['light_bg_image'] = null; // Clear path ក្នុង Database
            }

            // 2. ដំណើរការ Upload រូបភាព (Dark Mode)
            if ($request->hasFile('dark_bg_image')) {
                // លុបរូបចាស់ (បើមាន)
                if (!empty($settings['dark_bg_image']) && File::exists(public_path($settings['dark_bg_image']))) {
                    File::delete(public_path($settings['dark_bg_image']));
                }
                $validatedData['dark_bg_image'] = $this->uploadImage($request->file('dark_bg_image'), $user->id, 'dark');
                // បង្ខំឲ្យ Type ក្លាយជា 'image' ពេល Upload ជោគជ័យ
                $validatedData['dark_bg_type'] = 'image';
            } elseif ($request->input('dark_bg_type') !== 'image') {
                // បើ User ប្តូរទៅ 'default' ឬ 'color', ត្រូវលុបរូបចាស់ និង clear path
                if (!empty($settings['dark_bg_image']) && File::exists(public_path($settings['dark_bg_image']))) {
                    File::delete(public_path($settings['dark_bg_image']));
                }
                $validatedData['dark_bg_image'] = null; // Clear path ក្នុង Database
            }
            
            // --- END FIX ---

            // បញ្ចូលទិន្នន័យថ្មីទៅក្នុងទិន្នន័យចាស់
            $user->appearance_settings = array_merge($settings, $validatedData);
            $user->save();

            // ត្រឡប់ការកំណត់ដែលបាន Update ទាំងអស់
            $finalSettings = $this->getAppearanceWithDefaults($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Appearance updated successfully!',
                'settings' => $finalSettings, // បញ្ជូនការកំណត់ទាំងអស់กลับទៅវិញ
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper function សម្រាប់ Upload រូបភាព
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
        $defaults = [
            'light_primary_color' => '#4F46E5', // indigo-600
            'light_text_color' => '#1F2937',    // gray-800
            'light_bg_type' => 'default',
            'light_bg_color' => '#F3F4F6',    // gray-100
            'light_bg_image' => null,
            'dark_primary_color' => '#6366F1', // indigo-500
            'dark_text_color' => '#F9FAFB',    // gray-50
            'dark_bg_type' => 'default',
            'dark_bg_color' => '#111827',    // gray-900
            'dark_bg_image' => null,
        ];

        $merged = array_merge($defaults, $settings);

        // --- START FIX (ធានាថា URL ត្រឡប់ទៅត្រឹមត្រូវ) ---
        
        // 1. កំណត់តម្លៃ Default សម្រាប់ URL ឲ្យជា null
        $merged['light_bg_image_url'] = null;
        $merged['dark_bg_image_url'] = null;

        // 2. បំប្លែង Path រូបភាពទៅជា URL ពេញលេញ (បើមាន)
        if ($merged['light_bg_type'] == 'image' && !empty($merged['light_bg_image'])) {
            $merged['light_bg_image_url'] = asset($merged['light_bg_image']);
        }
        if ($merged['dark_bg_type'] == 'image' && !empty($merged['dark_bg_image'])) {
            $merged['dark_bg_image_url'] = asset($merged['dark_bg_image']);
        }
        
        // --- END FIX ---
        
        return $merged;
    }
}
