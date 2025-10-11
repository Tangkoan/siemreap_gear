<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condition; // ✅ ប្តូរពី Category ទៅ Condition
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     * បង្ហាញ Condition ទាំងអស់
     */
    public function AllCondition(){
        $condition = Condition::latest()->get();
        return view('admin.condition.all_condition', compact('condition'));
    } // End Method

    /**
     * Show the form for creating a new resource.
     * បង្ហាញទំព័រសម្រាប់បន្ថែម Condition ថ្មី
     */
    public function AddCondition(){
        return view('admin.condition.add_condition');
    } // End Method

    /**
     * Store a newly created resource in storage.
     * រក្សាទុក Condition ថ្មីទៅក្នុង Database
     */
    public function StoreCondition(Request $request)
    {
        // Validation
        $request->validate([
            'condition_name' => 'required|max:200|unique:conditions,condition_name', // ✅ ប្តូរឈ្មោះ Table និង Field
        ]);

        // Generate slug
        $slug = Str::slug($request->condition_name);

        // Insert into DB
        Condition::insert([
            'condition_name' => $request->condition_name,
            'created_at' => Carbon::now(),
        ]);

        // Notification
        $notification = [
            'message' => __('messages.condition_inserted_successfully'), // ✅ ប្តូរសារ Notification
            'alert-type' => 'success',
        ];

        return redirect()->route('all.condition')->with($notification); // ✅ ប្តូរ Route
    }

    /**
     * Show the form for editing the specified resource.
     * បង្ហាញទំព័រកែសម្រួល Condition
     */
    public function EditCondition($id){
        $condition = Condition::findOrFail($id);
        return view('admin.condition.edit_condition', compact('condition'));
    } // End Method

    /**
     * Update the specified resource in storage.
     * Update Condition ដែលមានស្រាប់
     */
    public function ConditionUpdate(Request $request)
    {
        $condition_id = $request->id;

        // Validation
        $request->validate([
            'condition_name' => 'required|max:200|unique:conditions,condition_name,' . $condition_id, // ✅ ប្តូរឈ្មោះ Table និង Field
        ]);

        // Generate new slug
        $slug = Str::slug($request->condition_name);

        // Update condition
        Condition::findOrFail($condition_id)->update([
            'condition_name' => $request->condition_name,
            'updated_at' => Carbon::now(),
        ]);

        // Notification
        $notification = [
            'message' => __('messages.condition_updated_successfully'), // ✅ ប្តូរសារ Notification
            'alert-type' => 'success',
        ];

        return redirect()->route('all.condition')->with($notification); // ✅ ប្តូរ Route
    }

    /**
     * Remove the specified resource from storage.
     * លុប Condition
     */
    public function DeleteCondition($id)
    {
        $condition = Condition::findOrFail($id);

        // ពិនិត្យមើលថាតើ Condition នេះត្រូវបានប្រើប្រាស់ដោយ Product ដែរឬទេ
        // អាចកែសម្រួល `products()` ទៅតាម relationship បានបង្កើត Model
        if ($condition->products()->exists()) {
            $notification = array(
                'message' => __('messages.condition_delete_error_has_products'), // ✅ ប្តូរសារ Notification
                'alert-type' => 'warning'
            );
            return redirect()->route('all.condition')->with($notification);
        }

        // ប្រសិនបើ Product អត់មានជាប់ Reletionship គឺអាចលុបបាន
        $condition->delete();

        $notification = array(
            'message' => __('messages.condition_deleted_successfully'), // ✅ ប្តូរសារ Notification
            'alert-type' => 'success'
        );
        return redirect()->route('all.condition')->with($notification);
    }

    /**
     * Search for a resource.
     * មុខងារស្វែងរក AJAX
     */
    public function searchCondition(Request $request)
    {
        // ✅ 1. Query the Condition model
        $query = Condition::query();

        // ✅ 2. Filter by condition_name if search term exists
        if ($request->has('search') && $request->search != '') {
            $query->where('condition_name', 'LIKE', '%' . $request->search . '%');
        }

        // 👉 Order by the newest first
        $query->orderBy('created_at', 'desc');

        // ✅ 3. Handle pagination
        $perPage = $request->perPage ?? 10; // Default to 10
        $isAll = $perPage === 'all';

        if ($isAll) {
            $conditions = $query->get();
        } else {
            $conditions = $query->paginate((int)$perPage);
        }

        $table = '';
        // ✅ 4. Loop through results to build HTML table rows
        foreach ($conditions as $key => $item) {
            $editBtn = '';
            $deleteBtn = '';
            
            // ✅ Edit Button (Check for 'condition.edit' permission)
            if (Auth::user()->can('condition.edit')) {
                $editBtn = '
                <button class="icon-edit transition-colors duration-200 dark:hover:text-blue-900 hover:text-blue-900 focus:outline-none">
                    <a href="' . route('edit.condition', $item->id) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                </button>';
            } else {
                // Disabled Edit Button
                $editBtn = '
                <button class="text-gray-400 cursor-not-allowed" disabled title="No permission to edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </button>';
            }
            
            // ✅ Delete Button (Check for 'condition.delete' permission)
            if (Auth::user()->can('condition.delete')) {
                $deleteBtn = '
                <button type="button" class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-red-500 hover:text-red-500 focus:outline-none">
                    <a href="' . route('delete.condition', $item->id) . '" id="delete">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </a>
                </button>';
            } else {
                // Disabled Delete Button
                $deleteBtn = '
                <button type="button" class="text-gray-400 cursor-not-allowed" disabled title="No permission to delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>';
            }
            
            // ✅ 5. Build the table row (TR) string, **without the slug column**
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                <td class="p-4 py-5">' . $item->condition_name . '</td>
                <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at)) . '</td>
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                        ' . $editBtn . $deleteBtn . '
                    </div>
                </td>
            </tr>';
        }

        // ✅ 6. Generate pagination links HTML
        $pagination = $isAll ? '<div class="text-sm text-slate-500"></div>' : $conditions->links('pagination::tailwind')->toHtml();

        // ✅ 7. Return the final JSON response
        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }
}