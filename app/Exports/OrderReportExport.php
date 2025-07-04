<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderReportExport implements FromCollection, WithHeadings
{
    protected $date, $month, $year;

    public function __construct($date = null, $month = null, $year = null)
    {
        $this->date = $date;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $query = Order::with('customer');

        if ($this->date) {
            $query->whereDate('order_date', $this->date);
        } elseif ($this->month && $this->year) {
            $query->whereYear('order_date', $this->year)
                  ->whereMonth('order_date', $this->month);
        } elseif ($this->year) {
            $query->whereYear('order_date', $this->year);
        }

        $orders = $query->get();

        return $orders->map(function($order, $key) {
            return [
                'No' => $key + 1,
                'Customer Name' => $order->customer?->name ?? 'N/A',
                'Sale Date' => $order->order_date,
                'Sup Total' => $order->sup_total,
                'Total Products' => $order->total_products,
                'Invoice Number' => $order->invoice_no,
                'Total' => $order->total,
                'Payment Status' => $order->payment_status,
                'Pay' => $order->pay,
                'Due' => $order->due,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Customer Name',
            'Sale Date',
            'Sup Total',
            'Total Products',
            'Invoice Number',
            'Total',
            'Payment Status',
            'Pay',
            'Due',
        ];
    }
}
