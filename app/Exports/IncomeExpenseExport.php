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
    protected $stock_adjustments;
    protected $payrolls; 
    protected $summary;

    /**
     * @param mixed $sales_details
     * @param mixed $purchase_details
     * @param mixed $other_expenses
     * @param mixed $stock_adjustments
     * @param mixed $summary
     */
    public function __construct(
        $sales_details,
        $purchase_details,
        $other_expenses,
        $stock_adjustments,
        $payrolls,
        $summary
    ) {
        $this->sales_details = $sales_details;
        $this->purchase_details = $purchase_details;
        $this->other_expenses = $other_expenses;
        $this->stock_adjustments = $stock_adjustments;
        $this->payrolls = $payrolls;
        $this->summary = $summary;
    }

    /**
     * ✅ ប្រើហ្វាល់ Blade ដែលយើងបាន Fix រួចហើយ
     * វិធីនេះធានាថា Excel និង PDF របស់អ្នក មានទិន្នន័យដូចគ្នាបេះបិទ
     */
    public function view(): View
    {
        return view('admin.report.income_expense.income_expense_pdf', [
            'sales_details' => $this->sales_details,
            'purchase_details' => $this->purchase_details,
            'other_expenses' => $this->other_expenses,
            'stock_adjustments' => $this->stock_adjustments,
            'payrolls' => $this->payrolls,
            'summary' => $this->summary,
        ]);
    }

    /**
     * ✅ កំណត់ Style សម្រាប់ Excel (ស្រដៀងនឹង PDF)
     */
    public function styles(Worksheet $sheet)
    {
        // កំណត់ Font សម្រាប់ Sheet ទាំងមូល (ប្រសិនបើចង់)
        // $sheet->getParent()->getDefaultStyle()->getFont()->setName('Khmer OS Battambang');

        // Style សម្រាប់ Summary
        $sheet->getStyle('A3:B5')->getFont()->setBold(true);
        $sheet->getStyle('A3:A5')->getAlignment()->setHorizontal('right');
        
        // Style សម្រាប់ Section Headers (Income/Expense Details)
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFe6e6e6');
        
        // ស្វែងរក Row របស់ Expense Details (នេះគ្រាន់តែជាការស្មាន)
        // អ្នកអាចកែលេខ 20 នេះ ទៅតាមចំនួនទិន្នន័យជាក់ស្តែង
        $expenseHeaderRow = count($this->sales_details) + count($this->stock_adjustments->where('type', 'sale_return')) + 10; 
        
        $sheet->getStyle('A' . $expenseHeaderRow)->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . $expenseHeaderRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFe6e6e6');

        // Style សម្រាប់ Table Headers (Date, Description...)
        $sheet->getStyle('A8:E8')->getFont()->setBold(true);
        $sheet->getStyle('A' . ($expenseHeaderRow + 1) . ':E' . ($expenseHeaderRow + 1))->getFont()->setBold(true);

        return [
            // Example: Style ជួរ Profit/Loss
            5 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // 1. បង្កើត Collection សម្រាប់សរុប (Summary)
        $summaryCollection = collect([
            ['type' => 'SUMMARY_HEADER', 'details' => 'Summary Report'],
            // ✅ បាន Un-comment
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

        // ✅ បន្ថែម Sale Returns (ជាការកាត់បន្ថយចំណូល)
        $saleReturnData = $this->stock_adjustments->where('type', 'sale_return')->map(function ($item) {
            return [
                'type' => 'DATA_ROW',
                'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                'details' => "{$item->product->product_name} (Sale Return)",
                'qty' => $item->quantity,
                'price' => $item->product->selling_price ?? 0,
                'total' => -1 * $item->quantity * ($item->product->selling_price ?? 0) 
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
                'details' => $item->description,
                'qty' => '-',
                'price' => '-',
                'total' => $item->amount
            ];
        });

        $payrollExpenseData = $this->payrolls->map(function ($item) {
            return [
                'type'  => 'DATA_ROW',
                'date'  => Carbon::parse($item->payroll_date)->format('d-m-Y'),
                'details' => "Payroll: " . ($item->employee->name ?? 'Unknown Employee'),
                'qty'   => '-',
                'price' => '-',
                'total' => $item->net_salary ?? $item->total_salary, // តើ Column ឈ្មោះអ្វី?
            ];
        });

        

        
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

        // ✅ បន្ថែម Purchase Returns (ជាការកាត់បន្ថយចំណាយ)
            $purchaseReturnData = $this->stock_adjustments->where('type', 'purchase_return')->map(function ($item) {
                return [
                    'type' => 'DATA_ROW',
                    'date' => Carbon::parse($item->created_at)->format('d-m-Y'),
                    'details' => "{$item->product->product_name} (Purchase Return)",
                    'qty' => $item->quantity,
                    'price' => $item->product->buying_price ?? 0,
                    'total' => -1 * $item->quantity * ($item->product->buying_price ?? 0) 
                ];
            });


        // 4. បញ្ចូល Collection ទាំងអស់ចូលគ្នាតាមលំដាប់
        return $summaryCollection
            ->merge($incomeCollection)
            ->merge($incomeData)
            ->merge($saleReturnData)
            ->merge($expenseCollection)
            ->merge($purchaseData)
            ->merge($otherExpenseData)
            ->merge($payrollExpenseData)   // ✅ បន្ថែម Payrolls នៅទីនេះ
            ->merge($clearedStockData)
            ->merge($purchaseReturnData);
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
                    $row['price'] ?? 0.00,
                    $row['total'] ?? 0.00,
                    // isset($row['price']) && is_numeric($row['price'])?number_format($row['price'], 2) : $row['price'],
                    // isset($row['total']) && is_numeric($row['total'])?number_format($row['total'], 2) : $row['total'],
                ];
            case 'SPACER':
            default:
                return ['', '', '', '', ''];
        }
    }

    
}
