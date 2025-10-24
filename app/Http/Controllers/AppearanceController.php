<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // ប្រើសម្រាប់លុបไฟล์

class AppearanceController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('background_type');
        $oldImage = ($user->background_type === 'image' && $user->background_value) ? public_path($user->background_value) : null;

        // Validation Rules
        $validated = $request->validate([
            'background_type' => 'required|in:default,color,image',
            'background_color' => 'nullable|required_if:background_type,color|regex:/^#[0-9a-fA-F]{6}$/',
            'background_image' => 'nullable|required_if:background_type,image|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'background_color.regex' => 'Invalid color format.',
            'background_image.image' => 'The file must be an image.',
        ]);

        try {
            if ($type === 'color') {
                $user->background_type = 'color';
                $user->background_value = $validated['background_color'];

                // លុបរូបចាស់ (បើមាន)
                if ($oldImage && File::exists($oldImage)) {
                    File::delete($oldImage);
                }

            } elseif ($type === 'image' && $request->hasFile('background_image')) {

                // លុបរូបចាស់ (បើមាន)
                if ($oldImage && File::exists($oldImage)) {
                    File::delete($oldImage);
                }

                $file = $request->file('background_image');
                $filename = 'user_bg_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = 'upload/backgrounds/'; // ត្រូវប្រាកដថា Folder នេះមាន
                $file->move(public_path($path), $filename);

                $user->background_type = 'image';
                $user->background_value = $path . $filename; // រក្សាទុក Path

            } elseif ($type === 'default') {
                $user->background_type = 'default';
                $user->background_value = null;

                // លុបរូបចាស់ (បើមាន)
                if ($oldImage && File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }

            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Background updated successfully!',
                'background_type' => $user->background_type,
                // បញ្ជូន URL ពេញលេញសម្រាប់រូបភាព ឬ Color Code
                'background_value' => $user->background_type === 'image' ? asset($user->background_value) : $user->background_value,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}