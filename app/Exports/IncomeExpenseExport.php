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
    protected $summary;

    public function __construct($sales_details, $purchase_details, $other_expenses, $summary)
    {
        $this->sales_details = $sales_details;
        $this->purchase_details = $purchase_details;
        $this->other_expenses = $other_expenses;
        $this->summary = $summary;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 1. បង្កើត Collection សម្រាប់សរុប (Summary)
        $summaryCollection = collect([
            ['type' => 'SUMMARY_HEADER', 'details' => 'Summary'],
            ['type' => 'SUMMARY_ROW', 'details' => 'Total Revenue:', 'amount' => $this->summary['total_revenue']],
            ['type' => 'SUMMARY_ROW', 'details' => 'Total Expenses:', 'amount' => $this->summary['total_expenses']],
            ['type' => 'SUMMARY_ROW', 'details' => 'Profit / Loss:', 'amount' => $this->summary['profit_or_loss']],
            ['type' => 'SPACER'], // សម្រាប់បង្កើតជួរដកឃ្លា
        ]);

        // 2. បង្កើត Collection សម្រាប់ចំណូល (Income)
        $incomeCollection = collect([['type' => 'SECTION_HEADER', 'details' => 'Income Details (Sales)']]);
        $incomeData = $this->sales_details->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->order->order_date)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Invoice: {$item->order->invoice_no})",
                'qty' => $item->quantity,
                'price' => $item->unitcost,
                'total' => $item->total,
                'category' => 'Income'
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
                'total' => $item->total,
                'category' => 'Expense'
            ];
        });
        $otherExpenseData = $this->other_expenses->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->date)->format('d-m-Y'),
                'details' => $item->details,
                'qty' => '-',
                'price' => '-',
                'total' => $item->amount,
                'category' => 'Expense'
            ];
        });

        // 4. បញ្ចូល Collection ទាំងអស់ចូលគ្នាតាមលំដាប់
        return $summaryCollection
            ->merge($incomeCollection)
            ->merge($incomeData)
            ->merge($expenseCollection)
            ->merge($purchaseData)
            ->merge($otherExpenseData);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // កំណត់ឈ្មោះ cột ក្នុង Excel
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
     *
     * @return array
     */
    public function map($row): array
    {
        // កំណត់ថាតើទិន្នន័យក្នុងមួយជួរៗត្រូវបង្ហាញដូចម្តេច
        switch ($row['type']) {
            case 'SUMMARY_HEADER':
            case 'SECTION_HEADER':
                return [
                    $row['details'], // បង្ហាញតែនៅ cột ទីមួយ
                    '', '', '', ''
                ];
            case 'SUMMARY_ROW':
                return [
                    $row['details'],
                    $row['amount'], // បង្ហាញនៅ cột ទីពីរ
                    '', '', ''
                ];
            case 'DATA_ROW':
                return [
                    $row['date'],
                    $row['details'],
                    $row['qty'],
                    is_numeric($row['price']) ? number_format($row['price'], 2) : $row['price'],
                    is_numeric($row['total']) ? number_format($row['total'], 2) : $row['total'],
                ];
            case 'SPACER':
            default:
                return ['', '', '', '', '']; // សម្រាប់ជួរដកឃ្លា
        }
    }

    public function styles(Worksheet $sheet)
    {
        // កំណត់ Style សម្រាប់ Header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->mergeCells('A1:E1'); // Merge cells for the main title if needed

        // អ្នកអាចបន្ថែម style សម្រាប់ប្រភេទ row នីមួយៗនៅទីនេះ
        // ឧទាហរណ៍ ធ្វើឲ្យ header ដិត
        $currentRow = 1;
        foreach ($this->collection() as $row) {
            if ($row['type'] === 'SUMMARY_HEADER' || $row['type'] === 'SECTION_HEADER') {
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
                $sheet->mergeCells("A{$currentRow}:E{$currentRow}");
            }
            if ($row['type'] === 'SUMMARY_ROW' && strpos($row['details'], 'Profit') !== false) {
                 $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
            }
            $currentRow++;
        }
        
        return [];
    }
}