<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class IncomeOutcomeExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        // យើងបញ្ជូន Array ធំមួយឈ្មោះ 'data' ទៅកាន់ view
        return view('admin.report.income_expense.export_template', [
            'data' => $this->data
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Income Outcome Report';
    }
}