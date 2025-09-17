<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class IncomeExpenseExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sales_details;
    protected $purchase_details;
    protected $other_expenses;
    protected $stock_adjustments; // ✅ បានបន្ថែម Property នេះ
    protected $summary;

    /**
     * @param mixed $sales_details
     * @param mixed $purchase_details
     * @param mixed $other_expenses
     * @param mixed $stock_adjustments
     * @param mixed $summary
     */
    public function __construct($sales_details, $purchase_details, $other_expenses, $stock_adjustments, $summary)
    {
        $this->sales_details = $sales_details;
        $this->purchase_details = $purchase_details;
        $this->other_expenses = $other_expenses;
        $this->stock_adjustments = $stock_adjustments; // ✅ ទទួលค่า stock_adjustments
        $this->summary = $summary;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // 1. បង្កើត Collection សម្រាប់សរុប (Summary)
        $summaryCollection = collect([
            ['type' => 'SUMMARY_HEADER', 'details' => 'Summary Report'],
            // ✅ បាន Un-comment និងកែไขส่วนนี้
            ['type' => 'SUMMARY_ROW', 'details' => 'Total Revenue:', 'amount' => '$' . $this->summary['total_revenue']],
            ['type' => 'SUMMARY_ROW', 'details' => 'Total Expenses:', 'amount' => '$' . $this->summary['total_expenses']],
            ['type' => 'SUMMARY_ROW', 'details' => 'Profit / Loss:', 'amount' => '$' . $this->summary['profit_or_loss']],
            ['type' => 'SPACER'], // សម្រាប់បង្កើតជួរដកឃ្លា
        ]);

        // 2. បង្កើត Collection សម្រាប់ចំណូល (Income)
        $incomeCollection = collect([['type' => 'SECTION_HEADER', 'details' => 'Income Details']]);

        $incomeData = $this->sales_details->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->order->order_date)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Invoice: {$item->order->invoice_no})",
                'qty' => $item->quantity,
                'price' => $item->unitcost,
                'total' => $item->total
            ];
        });

        // ✅ បន្ថែមข้อมูล Sale Returns (ជាការកាត់បន្ថយចំណូល)
        $saleReturnData = $this->stock_adjustments->where('type', 'sale_return')->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Sale Return)",
                'qty' => $item->quantity,
                'price' => $item->product->selling_price ?? 0,
                'total' => -1 * $item->quantity * ($item->product->selling_price ?? 0) // តម្លៃติดลบ
            ];
        });


        // 3. បង្កើត Collection សម្រាប់ចំណាយ (Expense)
        $expenseCollection = collect([
            ['type' => 'SPACER'],
            ['type' => 'SECTION_HEADER', 'details' => 'Expense Details']
        ]);

        $purchaseData = $this->purchase_details->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->purchase->purchase_date)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Purchase: {$item->purchase->invoice_no})",
                'qty' => $item->quantity,
                'price' => $item->purchase_price,
                'total' => $item->total
            ];
        });

        $otherExpenseData = $this->other_expenses->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->date)->format('d-m-Y'),
                'details' => $item->details,
                'qty' => '-',
                'price' => '-',
                'total' => $item->amount
            ];
        });

        // ✅ បន្ថែមข้อมูล Cleared Stock (ជាការចំណាយ)
        $clearedStockData = $this->stock_adjustments->where('type', 'clear_stock')->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Cleared Stock / Loss)",
                'qty' => $item->quantity,
                'price' => $item->product->buying_price ?? 0,
                'total' => $item->quantity * ($item->product->buying_price ?? 0)
            ];
        });

        // ✅ បន្ថែមข้อมูล Purchase Returns (ជាការកាត់បន្ថយចំណាយ)
            // $purchaseReturnData = $this->stock_adjustments->where('type', 'purchase_return')->map(function ($item) {
            //     return [
            //         'type' => 'DATA_ROW',
            //         'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
            //         'details' => "{$item->product->product_name} (Purchase Return)",
            //         'qty' => $item->quantity,
            //         'price' => $item->product->buying_price ?? 0,
            //         'total' => -1 * $item->quantity * ($item->product->buying_price ?? 0) // តម្លៃติดลบ
            //     ];
            // });


        // 4. បញ្ចូល Collection ទាំងអស់ចូលគ្នាតាមលំដាប់
        return $summaryCollection
            ->merge($incomeCollection)
            ->merge($incomeData)
            ->merge($saleReturnData)
            ->merge($expenseCollection)
            ->merge($purchaseData)
            ->merge($otherExpenseData)
            ->merge($clearedStockData);
            // ->merge($purchaseReturnData);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Details',
            'Quantity',
            'Price',
            'Total'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        switch ($row['type']) {
            case 'SUMMARY_HEADER':
            case 'SECTION_HEADER':
                return [
                    $row['details'],
                    '',
                    '',
                    '',
                    ''
                ];
            case 'SUMMARY_ROW':
                return [
                    $row['details'],
                    $row['amount'],
                    '',
                    '',
                    ''
                ];
            case 'DATA_ROW':
                return [
                    $row['date'] ?? '',
                    $row['details'] ?? '',
                    $row['qty'] ?? '',
                    isset($row['price']) && is_numeric($row['price']) ? '$' . number_format($row['price'], 2) : $row['price'],
                    isset($row['total']) && is_numeric($row['total']) ? '$' . number_format($row['total'], 2) : $row['total'],
                ];
            case 'SPACER':
            default:
                return ['', '', '', '', ''];
        }
    }

    public function styles(Worksheet $sheet)
    {
        // กำหนด Style สำหรับ Header หลัก
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        $currentRow = 1;
        foreach ($this->collection() as $row) {
            if ($row['type'] === 'SUMMARY_HEADER' || $row['type'] === 'SECTION_HEADER') {
                $sheet->mergeCells("A{$currentRow}:E{$currentRow}");
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle("A{$currentRow}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFD3D3D3');
            }
            if ($row['type'] === 'SUMMARY_ROW') {
                $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
            }
            // ทำให้แถว Profit/Loss ដិត
            if ($row['type'] === 'SUMMARY_ROW' && strpos($row['details'], 'Profit') !== false) {
                $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true)->setSize(12);
            }
            $currentRow++;
        }

        // กำหนด Style สำหรับ Header ของตารางข้อมูล
        $sheet->getStyle('A6:E6')->getFont()->setBold(true); // สมมติว่า Header ของ Income เริ่มที่แถว 6
        $sheet->getStyle('A' . ($this->sales_details->count() + $this->stock_adjustments->where('type', 'sale_return')->count() + 9) . ':E' . ($this->sales_details->count() + $this->stock_adjustments->where('type', 'sale_return')->count() + 9))->getFont()->setBold(true); // Style header ของ Expense

        return [];
    }
}
