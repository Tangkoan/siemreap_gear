<?php

namespace App\Exports;

use App\Models\Shift;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class ShiftReportExport implements FromView, ShouldAutoSize
{
    protected $userId;
    protected $startDate;
    protected $endDate;

    public function __construct($userId, $startDate, $endDate)
    {
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }



    public function view(): View
    {
        // 1. ប្រើ Logic ដូចគ្នានឹង ReportController
        $query = Shift::where('status', 'closed')
                        ->with([
                            'user',
                            'orders' => function($orderQuery) {
                                $orderQuery->where('order_status', 'complete');
                            },
                            'orders.orderDetails',
                            'orders.orderDetails.product'
                        ]) // <-- ✅ កូដកែប្រែ
                        ->latest();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween(DB::raw('DATE(start_time)'), [$this->startDate, $this->endDate]);
        }

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $shifts = $query->get();

        // 3. បញ្ជូនទិន្នន័យទៅកាន់ View សម្រាប់ Export
        return view('admin.report.exports.shift_report_excel', [
            'shifts' => $shifts
        ]);
    }
    
}