{{-- 
    This view is used by both Excel and PDF Exports
    It is a pure HTML Table 
--}}
<style>
    /* * 🟢 UPGRADE: Removed 'Khmer OS' and using standard web-safe fonts.
     * This ensures PDF compatibility.
    */
    body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background-color: #f4f4f4; text-align: left; }
    .text-right { text-align: right; }
    .text-bold { font-weight: bold; }
    .text-green { color: #28a745; }
    .text-red { color: #dc3545; }
    .header-info { margin-bottom: 20px; }
    h2 { font-size: 18px; }
    p { font-size: 14px; }
</style>

{{-- 🟢 UPGRADE: All text translated to English --}}
<div class="header-info">
    <h2>Payroll Expense Report</h2>
    <p>From Date: {{ $startDate }} To Date: {{ $endDate }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Payment Date</th>
            <th>Employee</th>
            <th>For Month/Year</th>
            <th>Base Salary</th>
            <th>Bonus (+)</th>
            <th>Deduction (-)</th>
            <th class="text-right">Net Salary</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payrolls as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->payment_date)->format('d-M-Y') }}</td>
                <td>{{ $item->employee->name ?? 'N/A' }}</td>
                <td>{{ $item->month_year }}</td>
                <td>${{ number_format($item->base_salary, 2) }}</td>
                <td class="text-green">${{ number_format($item->bonus, 2) }}</td>
                <td class="text-red">-${{ number_format($item->deduction, 2) }}</td>
                <td class="text-right text-bold">${{ number_format($item->net_salary, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    No data found for this period.
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right text-bold">Total Net Salary:</td>
            <td class="text-right text-bold">${{ number_format($totalNetSalary, 2) }}</td>
        </tr>
    </tfoot>
</table>