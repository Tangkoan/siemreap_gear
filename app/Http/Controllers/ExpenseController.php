<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display the main view page.
     */
    public function index(Request $request)
    {
        // ត្រូវទាញយក categories សម្រាប់ប្រើក្នុង Modal
        $categories = ExpenseCategory::orderBy('name', 'asc')->get();
        return view('expenses.index', compact('categories'));
    }

    /**
     * Handle AJAX request for searching and pagination.
     * (នេះជា Function ថ្មីដែលត្រូវនឹងកូដ Product របស់អ្នក)
     */
    public function searchExpenses(Request $request)
    {
        $query = $request->input('search');
        // កំណត់ perPage (default 10)
        $perPage = $request->input('perPage', 10) == 'all' ? 999999 : $request->input('perPage', 10);

        $expenses = Expense::with(['category', 'user']) // 'user' គឺអ្នកដែលបានកត់ត្រា
            ->when($query, function ($q) use ($query) {
                // Search តាម description ឬ តាម category name
                $q->where('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  });
            })
            ->orderBy('expense_date', 'desc')
            ->paginate($perPage);

        // Render HTML ពី partial view
        $table = view('expenses.partials.expenses_table_rows', compact('expenses'))->render();
        
        // Render pagination HTML
        // យើងត្រូវ custom pagination view បន្តិចបើអ្នកចង់ឲ្យដូចគំរូ
        $pagination = $expenses->appends($request->all())->links()->toHtml(); 
        
        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
        ]);
    }

    // ... បន្ថែម functions ផ្សេងទៀត (store, show, update, destroy) ...
    // ... ដូចដែលខ្ញុំបានផ្ដល់ឲ្យក្នុង response មុន ...
    // (សូមប្រាកដថា function ទាំងនោះមាននៅទីនេះ)
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $expense = Expense::create($request->all() + ['user_id' => Auth::id()]);
        
        return response()->json(['message' => 'Expense created successfully!', 'expense' => $expense], 201);
    }

    public function show(Expense $expense)
    {
        return response()->json($expense);
    }

    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $expense->update($request->all());
        return response()->json(['message' => 'Expense updated successfully!', 'expense' => $expense]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully!']);
    }
}