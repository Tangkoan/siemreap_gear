<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Display the main view page.
     */
    public function index()
    {
        return view('expense_categories.index');
    }

    /**
     * Handle AJAX request for searching and pagination.
     */
    public function searchCategories(Request $request)
    {
        $query = $request->input('search');
        $perPage = $request->input('perPage', 10) == 'all' ? 999999 : $request->input('perPage', 10);

        $categories = ExpenseCategory::withCount('expenses') // 1. រាប់ចំនួន expense ដែលពាក់ព័ន្ធ
            ->when($query, function ($q) use ($query) {
                // Search តាម name ឬ description
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);

        // Render HTML ពី partial view
        $table = view('expense_categories.partials.categories_table_rows', compact('categories'))->render();
        
        // Render pagination HTML
        $pagination = $categories->appends($request->all())->links()->toHtml(); 
        
        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = ExpenseCategory::create($request->all());

        return response()->json(['message' => 'Category created successfully!', 'category' => $category], 201);
    }

    /**
     * Get data for editing.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        // Route Model Binding នឹងរក category ឲ្យដោយស្វ័យប្រវត្តិ
        return response()->json($expenseCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('expense_categories')->ignore($expenseCategory->id), // 2. ត្រូវប្រាកដថា unique លើកលែងតែខ្លួនឯង
            ],
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $expenseCategory->update($request->all());

        return response()->json(['message' => 'Category updated successfully!', 'category' => $expenseCategory]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // 3. ពិនិត្យមើលថាតើ Category នេះមាន Expenses ភ្ជាប់ជាមួយឬអត់
        // នេះសំខាន់ណាស់! យើងមិនគួរលុប Category ដែលកំពុងប្រើប្រាស់ទេ
        if ($expenseCategory->expenses()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete! This category is currently in use by ' . $expenseCategory->expenses()->count() . ' expenses.'
            ], 422); // 422 Unprocessable Entity
        }

        $expenseCategory->delete();
        
        return response()->json(['message' => 'Category deleted successfully!']);
    }
}