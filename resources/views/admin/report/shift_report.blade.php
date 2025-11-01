@extends('admin/admin_dashboard')
@section('admin')

{{-- 1. ដាក់ Script និង Style សម្រាប់ Date Picker នៅខាងលើ --}}
<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid px-8">

    {{-- START: Page Title & Breadcrumb --}}
    <div class="flex flex-col items-start justify-between mb-6 sm:flex-row sm:items-center">
        <div>
            <h4 class="text-2xl font-semibold text-default">{{ __('messages.shift_report') }}</h4>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.review_cash_handling_accuracy') }}
            </p>
        </div>
        <div class="mt-2 text-sm sm:mt-0">
            <ol class="flex items-center space-x-1 text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('dashboard') }}" class="hover:text-primary">{{ __('messages.dashboard') }}</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="font-medium text-default">{{ __('messages.report') }}</li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="font-medium text-default">{{ __('messages.shift_report') }}</li>
            </ol>
        </div>
    </div>
    {{-- END: Page Title & Breadcrumb --}}

    {{-- START: Filter Card --}}
    <div class="mb-6 card-dynamic-bg rounded-xl shadow-lg">
        <div class="p-6">
            <form action="{{ route('report.shifts') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    {{-- Filter by User --}}
                    <div>
                        <label for="user_id" class="block mb-1 text-sm font-medium text-default">{{ __('messages.filter_by_cashier') }}</label>
                        <select name="user_id" id="user_id" class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default border-slate-300 dark:border-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">{{ __('messages.all_cashiers') }}</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $request->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Filter by Start Date --}}
                    <div>
                        <label for="start_date" class="block mb-1 text-sm font-medium text-default">{{ __('messages.start_date') }}</label>
                        <input type="text" name="start_date" id="start_date" class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default border-slate-300 dark:border-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" value="{{ $request->start_date }}" placeholder="Select Start Date">
                    </div>

                    {{-- Filter by End Date --}}
                    <div>
                        <label for="end_date" class="block mb-1 text-sm font-medium text-default">{{ __('messages.end_date') }}</label>
                        <input type="text" name="end_date" id="end_date" class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default border-slate-300 dark:border-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" value="{{ $request->end_date }}" placeholder="Select End Date">
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2 font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-lg shadow-sm bg-primary hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            {{ __('messages.filter') }}
                        </button>
                        <a href="{{ route('report.shifts') }}" class="w-full px-4 py-2 font-medium text-center transition duration-150 ease-in-out border rounded-lg text-default border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none">
                            {{ __('messages.reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- END: Filter Card --}}


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
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
                                            <span class="text-yellow-500">+$ {{ number_format(($shift->difference), 2) }}</span>
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
                    
                    @if($shifts->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $shifts->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- END: Main Report Table Card --}}

    </div>
    
</div>


{{-- ====================================================== --}}
{{-- START: HTML សម្រាប់ Shift Details Modal (លាក់ទុក) --}}
{{-- ====================================================== --}}
<div id="shiftDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center w-full h-full overflow-y-auto hidden" style="background-color: rgba(0,0,0,0.6);">
    
    {{-- Modal Content --}}
    <div class="relative w-full max-w-4xl mx-4 my-8 transition-transform duration-300 transform-gpu scale-95 card-dynamic-bg rounded-xl shadow-2xl" id="shiftDetailsModalContent">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-xl font-semibold text-default" id="modalTitle">
                {{ __('messages.shift_details') }}
            </h3>
            <button type="button" class="text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeShiftDetailsModal()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
            
            {{-- Loading Spinner (បង្ហាញពេលកំពុងទាញទិន្នន័យ) --}}
            <div id="modalLoading" class="text-center p-10">
                <svg class="inline w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67228 50 9.67228C27.4013 9.67228 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0492C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>

            {{-- Modal Content (បង្ហាញពេលទិន្នន័យមកដល់) --}}
            <div id="modalData" class="hidden">
                {{-- ផ្នែកសរុប (Summary) --}}
                
                {{-- ✅ START: កូដកែប្រែ --}}
                {{-- ខ្ញុំបានកែ 'class_exists=' ទៅជា 'class=' --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                {{-- ✅ END: កូដកែប្រែ --}}

                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.starting_cash_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalStartingCash"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.total_cash_sales_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-green-500" id="modalTotalCashSales"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.expected_cash_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-primary" id="modalExpectedCash"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.actual_cash_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalActualCash"></div>
                    </div>
                </div>

                {{-- ភាពខុសគ្នា (Difference) --}}
                <div id="modalDifferenceWrapper" class="p-4 mb-6 text-center rounded-lg">
                    <div class="text-sm font-medium uppercase text-default">{{ __('messages.difference') }}</div>
                    <div class="mt-1 text-3xl font-bold" id="modalDifference"></div>
                </div>

                {{-- តារាង Order លម្អិត --}}
                <h5 class="mb-4 text-lg font-semibold text-default">{{ __('messages.orders_in_this_shift') }}</h5>
                <div class="overflow-x-auto border rounded-lg border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left uppercase text-default">{{ __('messages.invoice_no') }}</th>
                                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left uppercase text-default">{{ __('messages.time') }}</th>
                                <th class="px-4 py-2 text-xs font-medium tracking-wider text-left uppercase text-default">{{ __('messages.payment_method') }}</th>
                                <th class="px-4 py-2 text-xs font-medium tracking-wider text-right uppercase text-default">{{ __('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700" id="modalOrderList">
                            {{-- JS នឹងបំពេញទិន្នន័យនៅទីនេះ --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Modal Footer --}}
        <div class="flex items-center justify-end p-5 border-t border-slate-200 dark:border-slate-700">
            <button type="button" class="px-5 py-2.5 font-medium text-gray-500 bg-white rounded-lg hover:text-gray-900 focus:z-10 dark:bg-slate-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-slate-600 border border-gray-200" onclick="closeShiftDetailsModal()">
                {{ __('messages.close') }}
            </button>
        </div>
    </div>
</div>
{{-- ====================================================== --}}
{{-- END: Shift Details Modal --}}
{{-- ====================================================== --}}


{{-- START: JavaScript សម្រាប់ Date Picker និង Modal --}}
{{-- ខ្ញុំបានដក @push('scripts') ចេញ ហើយដាក់ <script> ផ្ទាល់ --}}
<script>
    // សម្រាប់បើក Date Picker
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d",
        });
    });

    // សម្រាប់ Format លុយ
    function formatCurrency(number) {
        return '$ ' + parseFloat(number).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // សម្រាប់ Format ពេលវេលា
    function formatTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    const modal = document.getElementById('shiftDetailsModal');
    const modalContent = document.getElementById('shiftDetailsModalContent');
    const modalLoading = document.getElementById('modalLoading');
    const modalData = document.getElementById('modalData');

    // Function បើក Modal
    async function openShiftDetailsModal(shiftId) {
        // បង្ហាញ Modal និង Loading Spinner
        modal.classList.remove('hidden');
        modalData.classList.add('hidden');
        modalLoading.classList.remove('hidden');
        setTimeout(() => modalContent.classList.remove('scale-95'), 10); 

        try {
            // 1. ទាញទិន្នន័យពី Controller
            const response = await fetch(`/report/shift-details/${shiftId}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();

            // 2. បំពេញទិន្នន័យ (Populate Data)
            document.getElementById('modalTitle').innerText = `{{ __('messages.shift_details') }} #${data.shift.id} (${data.shift.user.name})`;
            
            // បំពេញ Summary Box
            document.getElementById('modalStartingCash').innerText = formatCurrency(data.shift.starting_cash);
            document.getElementById('modalTotalCashSales').innerText = formatCurrency(data.calculations.total_cash_sales);
            document.getElementById('modalExpectedCash').innerText = formatCurrency(data.calculations.expected_cash);
            document.getElementById('modalActualCash').innerText = formatCurrency(data.shift.ending_cash);

            // បំពេញ Difference Box
            const diffWrapper = document.getElementById('modalDifferenceWrapper');
            const diffText = document.getElementById('modalDifference');
            diffWrapper.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'bg-red-100', 'dark:bg-red-900/30', 'bg-yellow-100', 'dark:bg-yellow-900/30');
            diffText.classList.remove('text-green-600', 'text-red-600', 'text-yellow-600');

            if (data.shift.difference < 0) {
                diffText.innerText = `-${formatCurrency(Math.abs(data.shift.difference))} ({{ __('messages.short') }})`;
                diffText.classList.add('text-red-600', 'dark:text-red-400');
                diffWrapper.classList.add('bg-red-100', 'dark:bg-red-900/30');
            } else if (data.shift.difference > 0) {
                diffText.innerText = `+${formatCurrency(data.shift.difference)} ({{ __('messages.over') }})`;
                diffText.classList.add('text-yellow-600', 'dark:text-yellow-400');
                diffWrapper.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30');
            } else {
                diffText.innerText = `${formatCurrency(data.shift.difference)} ({{ __('messages.perfect') }})`;
                diffText.classList.add('text-green-600', 'dark:text-green-400');
                diffWrapper.classList.add('bg-green-100', 'dark:bg-green-900/30');
            }

            // 3. បំពេញតារាង Orders
            const orderListBody = document.getElementById('modalOrderList');
            orderListBody.innerHTML = ''; 

            if (data.orders.length > 0) {
                data.orders.forEach(order => {
                    const row = `
                        <tr class="text-sm text-default">
                            <td class="px-4 py-3 whitespace-nowrap">${order.invoice_no}</td>
                            <td class="px-4 py-3 whitespace-nowrap">${formatTime(order.created_at)}</td>
                            <td class="px-4 py-3 whitespace-nowrap">${order.payment_status}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-medium">${formatCurrency(order.total)}</td>
                        </tr>
                    `;
                    orderListBody.innerHTML += row;
                });
            } else {
                const row = `
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            {{ __('messages.no_orders_in_shift') }}
                        </td>
                    </tr>
                `;
                orderListBody.innerHTML = row;
            }

            // 4. បង្ហាញទិន្នន័យ និងលាក់ Loading
            modalLoading.classList.add('hidden');
            modalData.classList.remove('hidden');

        } catch (error) {
            console.error('Failed to fetch shift details:', error);
            modalLoading.innerHTML = `<p class="text-red-500">{{ __('messages.failed_to_load_details') }}</p>`;
        }
    }

    // Function បិទ Modal
    function closeShiftDetailsModal() {
        modalContent.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    // ចុចខាងក្រៅ Modal ដើម្បីបិទ
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeShiftDetailsModal();
        }
    });

</script>
{{-- END: JavaScript --}}
@endsection