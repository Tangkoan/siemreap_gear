<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
// use App\Models\Expense; // <-- ✅ មិនចាំបាច់ប្រើទៀតទេ
// use App\Models\ExpenseCategory; // <-- ✅ មិនចាំបាច់ប្រើទៀតទេ
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ✅ មិនចាំបាច់ប្រើទៀតទេ (បើលុប Expense)
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display the payroll management page.
     */
    public function index()
    {
        $currentMonthYear = Carbon::now()->format('M-Y');
        $employees = Employee::where('status', 'active')
            ->with(['payrolls' => function ($query) use ($currentMonthYear) {
                $query->where('month_year', $currentMonthYear);
            }])
            ->orderBy('name', 'asc')
            ->get();

        return view('payrolls.index', compact('employees', 'currentMonthYear'));
    }

    /**
     * Store a new payroll payment.
     * ✅ ឥឡូវនេះ វានឹងបង្កើតតែ Record នៅក្នុង 'payrolls' តែមួយគត់។
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
            $submittedMonth = Carbon::createFromFormat('M-Y', $request->month_year)->startOfMonth();
            $paymentDate = Carbon::parse($request->payment_date)->startOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: รูปแบบខែ/ឆ្នាំ មិនត្រឹមត្រូវ! (ឧ: Nov-2025)'
            ], 422);
        }
        
        $currentMonth = Carbon::now()->startOfMonth();

        if ($submittedMonth->gt($currentMonth)) {
            return response()->json([
                'message' => 'Error: មិនអាចបើកប្រាក់ខែសម្រាប់ខែអនាគតបានទេ!'
            ], 422);
        }
        
        // --- 3. VALIDATION ថ្មី (រារាំង Payment Date ខុសតក្កវិជ្ជា) ---
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

            // 5a. បង្កើត Record ក្នុងតារាង Payrolls (នៅដដែល)
            Payroll::create([
                'employee_id' => $employee->id,
                'payment_date' => $paymentDate, 
                'month_year' => $request->month_year,
                'base_salary' => $baseSalary,
                'bonus' => $bonus,
                'deduction' => $deduction,
                'net_salary' => $netSalary,
                'notes' => $request->notes,
            ]);

            // ❌ START: កូដដែលត្រូវដកចេញ ❌
            /*
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
                'expense_date' => $paymentDate,
                'notes' => 'Payroll ID: ' . $payroll->id 
            ]);
            */
            // ❌ END: កូដដែលត្រូវដកចេញ ❌

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