<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShiftReportExport implements FromView
{
    protected $shifts;
    protected $startDate;
    protected $endDate;

    // constructor ថ្មី ទទួលយកទិន្នន័យ $shifts (ដែល Controller បានទាញ)
    public function __construct($shifts, $startDate, $endDate)
    {
        $this->shifts = $shifts;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        // ប្រើ Blade View ដូចគ្នានឹង PDF 
        return view('admin.report.exports.shift_report_excel', [
            'shifts' => $this->shifts,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
}