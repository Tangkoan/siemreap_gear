<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Expense;
use App\Models\ExpenseCategory; // សំខាន់ណាស់
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // សំខាន់ណាស់ សម្រាប់ Transaction
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display the payroll management page.
     * This page lists all active employees.
     */
    public function index()
    {
        // 1. ស្វែងរកខែ/ឆ្នាំ បច្ចុប្បន្ន (ឧ: "Nov-2025")
        $currentMonthYear = Carbon::now()->format('M-Y');

        // 2. ទាញយកបុគ្គលិក "Active" ទាំងអស់
        // 3. ភ្ជាប់ (Eager Load) ជាមួយ payrolls "តែក្នុងខែនេះ"
        $employees = Employee::where('status', 'active')
            ->with(['payrolls' => function ($query) use ($currentMonthYear) {
                // ស្វែងរកតែ payrolls ដែលមាន month_year ស្មើនឹង "Nov-2025"
                $query->where('month_year', $currentMonthYear);
            }])
            ->orderBy('name', 'asc')
            ->get();

        // ពេលបញ្ជូនទៅ View, $employee->payrolls នឹងមានន័យថា:
        // - បើ $employee->payrolls->count() > 0 (បានន័យថា បើកហើយ)
        // - បើ $employee->payrolls->count() == 0 (បានន័យថា មិនទាន់បើក)

        return view('payrolls.index', compact('employees', 'currentMonthYear'));
    }

    /**
     * Store a new payroll payment.
     * This will create TWO records: one in 'payrolls' and one in 'expenses'.
     */
    /**
     * Store a new payroll payment.
     * This will create TWO records: one in 'payrolls' and one in 'expenses'.
     */
    public function store(Request $request)
    {
        // 1. VALIDATION ធម្មតា (Check ទទេ)
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'month_year' => 'required|string|max:50',
            'bonus' => 'required|numeric|min:0',
            'deduction' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // --- 2. VALIDATION (រារាំងខែអនាគត) ---
        try {
            // បម្លែង Text (ឧ: "Dec-2025") ទៅជា Object Date (2025-12-01)
            $submittedMonth = Carbon::createFromFormat('M-Y', $request->month_year)->startOfMonth();
            // បម្លែង Payment Date (2025-08-01) ទៅជា Object Date
            $paymentDate = Carbon::parse($request->payment_date)->startOfDay();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: รูปแบบខែ/ឆ្នាំ មិនត្រឹមត្រូវ! (ឧ: Nov-2025)'
            ], 422);
        }
        
        $currentMonth = Carbon::now()->startOfMonth();

        // Check: បើ Submitted Month (Dec-2025) ធំជាង (gt) Current Month (Nov-2025)
        if ($submittedMonth->gt($currentMonth)) {
            return response()->json([
                'message' => 'Error: មិនអាចបើកប្រាក់ខែសម្រាប់ខែអនាគតបានទេ!'
            ], 422);
        }
        
        // --- 3. VALIDATION ថ្មី (រារាំង Payment Date ខុសតក្កវិជ្ជា) ---
        // Check: ថ្ងៃទូទាត់ (Payment Date) មិនអាចនៅមុន (lt) ថ្ងៃដំបូងនៃខែដែលបើក (Month Year)
        // ឧ: Pay Date (Aug-01) គឺ "lt" (តូចជាង) Month Year (Nov-01) -> ERROR
        if ($paymentDate->lt($submittedMonth)) {
            return response()->json([
                'message' => 'Error: ថ្ងៃទូទាត់ (' . $paymentDate->format('d-M-Y') . ') មិនអាចនៅមុនខែ (' . $request->month_year . ') ដែលអ្នកចង់បើកបានទេ!'
            ], 422);
        }

        // --- 4. VALIDATION (រារាំងការបើកស្ទួន) ---
        $existingPayroll = Payroll::where('employee_id', $request->employee_id)
                                  ->where('month_year', $request->month_year)
                                  ->exists(); 

        if ($existingPayroll) {
            return response()->json([
                'message' => 'Error: បុគ្គលិកនេះ បានទទួលប្រាក់ខែសម្រាប់ ' . $request->month_year . ' រួចរាល់ហើយ!'
            ], 422); 
        }

        // --- 5. ដំណើរការ DATABASE TRANSACTION ---
        try {
            DB::beginTransaction();

            $employee = Employee::find($request->employee_id);
            $baseSalary = $employee->base_salary;
            $bonus = $request->bonus;
            $deduction = $request->deduction;
            $netSalary = ($baseSalary + $bonus) - $deduction;

            // 5a. បង្កើត Record ក្នុងតារាង Payrolls
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'payment_date' => $paymentDate, // ប្រើ $paymentDate object
                'month_year' => $request->month_year,
                'base_salary' => $baseSalary,
                'bonus' => $bonus,
                'deduction' => $deduction,
                'net_salary' => $netSalary,
                'notes' => $request->notes,
            ]);

            // 5b. ស្វែងរក Category "Salary"
            $salaryCategory = ExpenseCategory::where('name', 'Salary')
                                             ->orWhere('name', 'ប្រាក់ខែ')
                                             ->first();
            
            if (!$salaryCategory) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Error: សូមបង្កើត Expense Category ឈ្មោះ "Salary" ឬ "ប្រាក់ខែ" ជាមុនសិន!'
                ], 422);
            }

            // 5c. បង្កើត Record ក្នុងតារាង Expenses
            Expense::create([
                'expense_category_id' => $salaryCategory->id,
                'user_id' => Auth::id(),
                'description' => 'ប្រាក់ខែ ' . $employee->name . ' (' . $request->month_year . ')',
                'amount' => $netSalary,
                'expense_date' => $paymentDate, // ប្រើ $paymentDate object
                'notes' => 'Payroll ID: ' . $payroll->id
            ]);

            DB::commit();

            return response()->json(['message' => 'បើកប្រាក់ខែឲ្យ ' . $employee->name . ' ជោគជ័យ!'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'មានបញ្ហាកើតឡើង! Error: ' . $e->getMessage()
            ], 500);
        }
    }
}