{{-- START: Honesty Summary Card --}}
<div class="lg:col-span-1">
    <div class="card-dynamic-bg rounded-xl shadow-lg h-full">
        <div class="p-6">
            <h5 class="text-xl font-semibold text-default mb-4">{{ __('messages.cashier_honesty_summary') }}</h5>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                {{ __('messages.summary_desc') }}
            </p>
            <div class="space-y-4">
                @forelse($honestySummary as $summary)
                <div class="flex justify-between items-center">
                    <span class="font-medium text-default">{{ $summary->user->name ?? 'Unknown' }}</span>
                    
                    @if($summary->total_difference < 0)
                        <span class="font-bold text-red-500">
                            -$ {{ number_format(abs($summary->total_difference), 2) }} ({{ __('messages.short') }})
                        </span>
                    @elseif($summary->total_difference > 0)
                        <span class="font-bold text-yellow-500">
                            +$ {{ number_format($summary->total_difference, 2) }} ({{ __('messages.over') }})
                        </span>
                    @else
                        <span class="font-bold text-green-500">
                            $ {{ number_format($summary->total_difference, 2) }} ({{ __('messages.perfect') }})
                        </span>
                    @endif
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">{{ __('messages.no_data_for_summary') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
{{-- END: Honesty Summary Card --}}

{{-- START: Main Report Table Card --}}
<div class="lg:col-span-2">
    <div class="overflow-x-auto card-dynamic-bg rounded-xl shadow-lg">
        <div class="min-w-full overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-default">{{ __('messages.cashier') }}</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-default">{{ __('messages.shift_duration') }}</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right uppercase text-default">{{ __('messages.expected_cash') }}</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right uppercase text-default">{{ __('messages.actual_cash') }}</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right uppercase text-default">{{ __('messages.difference') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer" 
                            data-shift-id="{{ $shift->id }}"
                            onclick="openShiftDetailsModal({{ $shift->id }})">
                        
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-default">{{ $shift->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Shift ID: {{ $shift->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-default">{{ \Carbon\Carbon::parse($shift->start_time)->format('d-M-Y H:i') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">to {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-right whitespace-nowrap text-default">
                                $ {{ number_format($shift->starting_cash + $shift->total_sales_cash, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right whitespace-nowrap text-default">
                                $ {{ number_format($shift->ending_cash, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-right whitespace-nowrap">
                                @if($shift->difference < 0)
                                    <span class="text-red-500">-$ {{ number_format(abs($shift->difference), 2) }}</span>
                                @elseif($shift->difference > 0)
                                    <span class="text-yellow-500">+$ {{ number_format($shift->difference, 2) }}</span>
                                @else
                                    <span class="text-green-500">$ {{ number_format($shift->difference, 2) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('messages.no_shifts_found_criteria') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- ✅ សំខាន់៖ ត្រូវប្រាកដថា Pagination ដំណើរការជាមួយ Query String --}}
            @if($shifts->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $shifts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
{{-- END: Main Report Table Card --}}