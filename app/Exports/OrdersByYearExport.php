<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class OrdersByYearExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $year;
    protected $search;

    public function __construct(string $year, ?string $search)
    {
        $this->year = $year;
        $this->search = $search;
    }

    public function collection()
    {
        $startDate = Carbon::create($this->year)->startOfYear();
        $endDate = Carbon::create($this->year)->endOfYear();

        $query = Order::with('customer')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('id', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_no', 'like', "%{$this->search}%")
                  ->orWhereHas('customer', function ($q2) {
                      $q2->where('name', 'like', "%{$this->search}%");
                  });
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return ['Order Date', 'Invoice No', 'Customer Name', 'Amount', 'Payment Status', 'Order Status'];
    }

    public function map($order): array
    {
        return [
            Carbon::parse($order->order_date)->format('d-m-Y'),
            $order->invoice_no,
            $order->customer->name ?? 'N/A',
            $order->total,
            $order->payment_status,
            $order->order_status,
        ];
    }
}