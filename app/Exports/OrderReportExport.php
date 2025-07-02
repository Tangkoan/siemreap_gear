<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // យក Order ជាមួយ customer relation (eager load) ដើម្បីចូលដំណឹង customer name
        $orders = Order::with('customer')->get();

        // បង្កើត Collection ថ្មីដែលមាន field ត្រឹមត្រូវ និងបញ្ចូល customer name
        return $orders->map(function($order, $key) {
            return [
                'No' => $key + 1,
                'Customer Name' => $order->customer ? $order->customer->name : 'N/A', // ប្រាកដថា customer មានទិន្នន័យ
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
