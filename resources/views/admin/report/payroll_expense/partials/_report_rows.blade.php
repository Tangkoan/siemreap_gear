@forelse($payrolls as $key => $item)
    <tr class="text-defalut hover:bg-gray-100 dark:hover:bg-gray-800">
        <td class="p-4 border-b border-slate-200">
            {{ $payrolls->firstItem() + $key }}
        </td>
        <td class="p-4 border-b border-slate-200">
            {{ \Carbon\Carbon::parse($item->payment_date)->format('d-M-Y') }}
        </td>
        <td class="p-4 border-b border-slate-200 font-bold">
            {{-- 🟢 UPGRADE: ទាញឈ្មោះពី 'employee' relationship --}}
            {{ $item->employee->name ?? 'N/A' }}
        </td>
        <td class="p-4 border-b border-slate-200">
            {{-- 🟢 UPGRADE: បង្ហាញ 'month_year' --}}
            {{ $item->month_year }}
        </td>
        <td class="p-4 border-b border-slate-200">
            {{-- 🟢 UPGRADE: បង្ហាញ 'base_salary' --}}
            ${{ number_format($item->base_salary, 2) }}
        </td>
        <td class="p-4 border-b border-slate-200">
            {{-- 🟢 UPGRADE: បង្ហាញ 'bonus' និង 'deduction' --}}
            <span classtext-green-500">(+${{ number_format($item->bonus, 2) }}</span> / 
            <span class="text-red-500">-${{ number_format($item->deduction, 2) }})</span>
        </td>
        <td class="p-4 border-b border-slate-200 text-right font-bold text-red-500">
            {{-- 🟢 UPGRADE: បង្ហាញ 'net_salary' --}}
            -${{ number_format($item->net_salary, 2) }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-8 text-center text-gray-500">
            មិនមានទិន្នន័យបើកប្រាក់ខែ សម្រាប់រយៈពេលនេះទេ។
        </td>
    </tr>
@endforelse