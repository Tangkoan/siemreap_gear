<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PurchasesReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Purchase::with('supplier');
        $filters = $this->filters;

        if (!empty($filters['date'])) {
            $query->whereDate('purchase_date', $filters['date']);
        }

        if (!empty($filters['month'])) {
            $carbonMonth = Carbon::parse($filters['month']);
            $query->whereYear('purchase_date', $carbonMonth->year)
                  ->whereMonth('purchase_date', $carbonMonth->month);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('purchase_date', $filters['year']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Invoice No',
            'Supplier',
            'Subtotal',
            'Discount',
            'Shipping',
            'VAT',
            'Grand Total',
            'Payment Status',
        ];
    }

    public function map($purchase): array
    {
        return [
            Carbon::parse($purchase->purchase_date)->format('d-m-Y'),
            $purchase->invoice_no,
            $purchase->supplier->name ?? 'N/A',
            $purchase->sub_total,
            $purchase->discount,
            $purchase->shipping,
            $purchase->vat,
            $purchase->total,
            $purchase->payment_status,
        ];
    }
}