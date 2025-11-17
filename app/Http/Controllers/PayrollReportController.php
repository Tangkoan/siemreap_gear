<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollReportController extends Controller
{
    /**
     * Display the main view for the payroll expense report.
     */
    public function index()
    {
        return view('admin.report.payroll_expense.index');
    }

    /**
     * 🟢 UPGRADE: Private Function ថ្មី សម្រាប់ Month Range
     * មុខងារនេះ នឹងទាញយក Query គោល សម្រាប់ប្រើទាំង AJAX និង Exports
     */
    private function getReportDataQuery(Request $request)
    {
        // កំណត់ Default ទៅជា "ខែនេះ" ដល់ "ខែនេះ"
        $startMonth = $request->input('start_month', Carbon::now()->format('Y-m'));
        $endMonth = $request->input('end_month', Carbon::now()->format('Y-m'));

        // 1. បម្លែង "Y-m" ទៅជា Date Object
        // ឧ: "2025-11" -> "2025-11-01 00:00:00"
        $startDateCarbon = Carbon::parse($startMonth)->startOfMonth()->startOfDay();
        // ឧ: "2025-11" -> "2025-11-30 23:59:59"
        $endDateCarbon = Carbon::parse($endMonth)->endOfMonth()->endOfDay();

        // 2. ចាប់ផ្តើម Query ពី Payroll (Source of Truth)
        $query = Payroll::with('employee')
                        ->whereBetween('payment_date', [$startDateCarbon, $endDateCarbon]);

        // 3. ធ្វើទ្រង់ទ្រាយ Date សម្រាប់បង្ហាញ
        $formattedDate = $startDateCarbon->format('F Y');
        if ($startMonth != $endMonth) {
            $formattedDate .= ' ដល់ ' . $endDateCarbon->format('F Y');
        }

        return [
            'query' => $query,
            'formattedDate' => $formattedDate,
            'startDate' => $startDateCarbon, // សម្រាប់ Export
            'endDate' => $endDateCarbon,   // សម្រាប់ Export
        ];
    }


    /**
     * Get data for the payroll expense report via AJAX.
     * 🟢 UPGRADE: ប្រើ Month Range Filter ថ្មី
     */
    public function getPayrollReportData(Request $request)
    {
        // 1. ហៅ Private function ដើម្បីយក Query
        $reportData = $this->getReportDataQuery($request);
        $query = $reportData['query'];

        // 2. គណនា KPIs (មុនពេល Paginate)
        $kpiQuery = clone $query;
        $kpiData = $kpiQuery->get();
        
        $totalSpending = $kpiData->sum('net_salary');
        $totalPayments = $kpiData->count();

        // 3. Paginate
        $payrolls = $query->latest('payment_date')->paginate(15)->withQueryString();

        // 4. Render HTML (មិនផ្លាស់ប្តូរ)
        $tableHtml = view('admin.report.payroll_expense.partials._report_rows', ['payrolls' => $payrolls])->render();

        return response()->json([
            'table' => $tableHtml,
            'pagination' => $payrolls->links()->toHtml(),
            'formattedDate' => $reportData['formattedDate'],
            'kpis' => [
                'totalSpending' => '$' . number_format($totalSpending, 2),
                'totalPayments' => number_format($totalPayments) . ' payments',
            ]
        ]);
    }

    // --- 🟢 UPGRADE: មុខងារ EXPORT ថ្មី ---

    /**
     * Export Payroll Report to Excel.
     */
    public function exportExcel(Request $request)
    {
        // 🟢 UPGRADE: ប្រើ 'start_month' និង 'end_month'
        $startMonth = $request->input('start_month', Carbon::now()->format('Y-m'));
        $endMonth = $request->input('end_month', Carbon::now()->format('Y-m'));
        
        $fileName = 'Payroll-Report-' . $startMonth . '-to-' . $endMonth . '.xlsx';
        
        // ហៅ Export Class ដែលយើងបានបង្កើត
        return Excel::download(new PayrollReportExport($startMonth, $endMonth), $fileName);
    }

    /**
     * Export Payroll Report to PDF.
     */
    public function exportPdf(Request $request)
    {
        // 1. ហៅ Private function ដើម្បីយក Query
        $reportData = $this->getReportDataQuery($request);
        
        // 2. យកទិន្នន័យ "ទាំងអស់" (មិន Paginate)
        $payrolls = $reportData['query']->orderBy('payment_date', 'asc')->get();
        $totalNetSalary = $payrolls->sum('net_salary');
        
        $data = [
            'payrolls' => $payrolls,
            'startDate' => $reportData['startDate']->format('d-M-Y'), // ប្រើ Date ពេញ
            'endDate' => $reportData['endDate']->format('d-M-Y'),     // ប្រើ Date ពេញ
            'totalNetSalary' => $totalNetSalary,
        ];

        // 3. ហៅ View (មិនផ្លាស់ប្តូរ)
        $pdf = Pdf::loadView('admin.report.payroll_expense.partials._report_export_view', $data);
        
        $fileName = 'Payroll-Report-' . $reportData['startDate']->format('Ym') . '-' . $reportData['endDate']->format('Ym') . '.pdf';
        
        return $pdf->setPaper('a4', 'landscape')->download($fileName);
    }
}