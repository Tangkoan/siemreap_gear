<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display the main view page.
     */
    public function index()
    {
        return view('employees.index');
    }

    /**
     * Handle AJAX request for searching and pagination.
     */
    public function searchEmployees(Request $request)
    {
        $query = $request->input('search');
        $perPage = $request->input('perPage', 10) == 'all' ? 999999 : $request->input('perPage', 10);

        $employees = Employee::withCount('payrolls') // រាប់ចំនួន Payroll
            ->when($query, function ($q) use ($query) {
                // Search តាម name, phone, or position
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('position', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);

        // Render HTML ពី partial view
        $table = view('employees.partials.employees_table_rows', compact('employees'))->render();
        
        // Render pagination HTML
        $pagination = $employees->appends($request->all())->links()->toHtml(); 
        
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
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee = Employee::create($request->all());

        return response()->json(['message' => 'Employee created successfully!', 'employee' => $employee], 201);
    }

    /**
     * Get data for editing.
     */
    public function show(Employee $employee)
    {
        // Route Model Binding នឹងរក employee ឲ្យដោយស្វ័យប្រវត្តិ
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee->update($request->all());

        return response()->json(['message' => 'Employee updated successfully!', 'employee' => $employee]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // ពិនិត្យមើលថាតើ Employee នេះធ្លាប់បើកប្រាក់ខែឬនៅ
        if ($employee->payrolls()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete! This employee has payroll history.'
            ], 422); // 422 Unprocessable Entity
        }

        $employee->delete();
        
        return response()->json(['message' => 'Employee deleted successfully!']);
    }
}