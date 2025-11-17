<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PayrollReportExport implements FromView, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

   // 🟢 UPGRADE: ជំនួស Constructor នេះ
    public function __construct($startMonth, $endMonth)
    {
        // ឧ: "2025-11" -> "2025-11-01 00:00:00"
        $this->startDate = $startMonth ? Carbon::parse($startMonth)->startOfMonth() : Carbon::now()->startOfMonth();
        
        // ឧ: "2025-12" -> "2025-12-31 23:59:59"
        $this->endDate = $endMonth ? Carbon::parse($endMonth)->endOfMonth() : Carbon::now()->endOfMonth();
    }
    // 🟢 END UPGRADE
    public function view(): View
    {
        // 1. នេះគឺជា Query តែមួយគត់ដែលយើងប្រើ (Source of Truth)
        $query = Payroll::with('employee')
                        ->whereBetween('payment_date', [$this->startDate, $this->endDate]);

        $payrolls = $query->orderBy('payment_date', 'asc')->get();
        
        $totalNetSalary = $payrolls->sum('net_salary');

        // 2. យើងនឹងបង្កើត View មួយសម្រាប់ Export (ប្រើរួមគ្នាដោយ PDF)
        return view('admin.report.payroll_expense.partials._report_export_view', [
            'payrolls' => $payrolls,
            'startDate' => $this->startDate->format('d-M-Y'),
            'endDate' => $this->endDate->format('d-M-Y'),
            'totalNetSalary' => $totalNetSalary,
        ]);
    }
}