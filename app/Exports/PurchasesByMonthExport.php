<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PurchasesByMonthExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $month;
    protected $search;

    public function __construct(string $month, ?string $search)
    {
        $this->month = $month;
        $this->search = $search;
    }

    public function collection()
    {
        $startDate = Carbon::parse($this->month)->startOfMonth();
        $endDate = Carbon::parse($this->month)->endOfMonth();
        $query = Purchase::with('supplier')->whereBetween('purchase_date', [$startDate, $endDate])->orderBy('id', 'desc');
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_no', 'like', "%{$this->search}%")
                  ->orWhereHas('supplier', function ($q2) {
                      $q2->where('name', 'like', "%{$this->search}%");
                  });
            });
        }
        return $query->get();
    }

    public function headings(): array { return ['Date', 'Invoice No', 'Supplier', 'Amount', 'Payment Status']; }
    
    public function map($purchase): array
    {
        return [
            Carbon::parse($purchase->purchase_date)->format('d-m-Y'),
            $purchase->invoice_no,
            $purchase->supplier->name ?? 'N/A',
            $purchase->total,
            $purchase->payment_status,
        ];
    }
}