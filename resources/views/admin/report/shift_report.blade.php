@extends('admin/admin_dashboard')
@section('admin')

{{-- 1. Script និង Style សម្រាប់ Date Picker --}}
<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/flatpickr.min.css">

{{-- 2. Style សម្រាប់ Loading Overlay --}}
<style>
    #report-data-container.loading {
        opacity: 0.5;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
</style>

<div class="container-fluid p-4 pt-6 md:p-6">

    {{-- START: Page Title & Breadcrumb --}}
    <div class="flex flex-col items-start justify-between mb-6 sm:flex-row sm:items-center">
        <div class="flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <h4 class="text-2xl font-bold text-default py-2 px-2">{{ __('messages.shift_report') }}</h4>
            {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.review_cash_handling_accuracy') }}
            </p> --}}
        </div>
        {{-- <div class="mt-2 text-sm sm:mt-0">
            <ol class="flex items-center space-x-1 text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('dashboard') }}" class="hover:text-primary">{{ __('messages.dashboard') }}</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="font-medium text-default">{{ __('messages.report') }}</li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="font-medium text-default">{{ __('messages.shift_report') }}</li>
            </ol>
        </div> --}}
    </div>
    {{-- END: Page Title & Breadcrumb --}}

    
    {{-- START: Filter Card --}}
    <div class="mb-6 card-dynamic-bg rounded-xl shadow-lg">
        <div class="p-6">
            <form action="{{ route('report.shifts') }}" method="GET" id="shiftReportForm">
                
                {{-- 3. កែសម្រួល Grid ទៅ md:grid-cols-3 --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
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
                        
                        {{-- ✅ កូដកែប្រែ៖ បានបន្ថែម default value (ថ្ងៃនេះ) --}}
                        <input type="text" name="start_date" id="start_date" class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default border-slate-300 dark:border-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" 
                            value="{{ $request->start_date ?? now()->format('Y-m-d') }}" 
                            placeholder="Select Start Date">
                    </div>

                    {{-- Filter by End Date --}}
                    <div>
                        <label for="end_date" class="block mb-1 text-sm font-medium text-default">{{ __('messages.end_date') }}</label>
                        
                        {{-- ✅ កូដកែប្រែ៖ បានបន្ថែម default value (ថ្ងៃនេះ) --}}
                        <input type="text" name="end_date" id="end_date" class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default border-slate-300 dark:border-slate-700 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" 
                            value="{{ $request->end_date ?? now()->format('Y-m-d') }}" 
                            placeholder="Select End Date">
                    </div>

                    {{-- 4. ដកប៊ូតុង Filter និង Reset ចេញ --}}
                </div>

                {{-- ផ្នែក Export ទុកដដែល --}}
                <div class="flex justify-end pt-4 mt-4 border-t border-slate-200 dark:border-slate-700 space-x-3">
                    <span class="flex items-center pr-2 text-sm font-medium text-default">{{ __('messages.export_as') }}:</span>

                    {{-- ប៊ូតុង Excel --}}
                    <button type="submit" class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 focus:outline-none"
                        onclick="setFormAction('{{ route('report.shifts.export.excel') }}')">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.63 8.23l1.88-1.88-1.88-1.88a.5.5 0 01.7-.71l1.88 1.88 1.88-1.88a.5.5 0 11.7.71L8.23 6.63l1.88 1.88a.5.5 0 01-.7.71L7.33 7.33l-1.88 1.88a.5.5 0 01-.7-.71l1.88-1.88zM12 11.5a.5.5 0 01.5.5v1a.5.5 0 01-1 0v-1a.5.5 0 01.5-.5zM10.5 13a.5.5 0 00-1 0v1a.5.5 0 001 0v-1zM14 13.5a.5.5 0 01.5.5v1a.5.5 0 01-1 0v-1a.5.5 0 01.5-.5z"/></svg>
                        Excel
                    </button>

                    {{-- ប៊ូតុង PDF --}}
                    <button type="submit" class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg shadow-sm hover:bg-red-700 focus:outline-none"
                        onclick="setFormAction('{{ route('report.shifts.export.pdf') }}')">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a2 2 0 012-2h10a2 2 0 110 4H5a2 2 0 01-2-2zm6-4a1 1 0 00-1-1H4a1 1 0 100 2h4a1 1 0 001-1zM9 5a1 1 0 011-1h4a1 1 0 110 2h-4a1 1 0 01-1-1zM3 5a1 1 0 000 2h.01a1 1 0 100-2H3z" clip-rule="evenodd" /></svg>
                        PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- END: Filter Card --}}

    
    {{-- 5. បន្ថែម ID ទៅ Wrapper ហើយប្រើ @include --}}
    <div id="report-data-container" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- ទិន្នន័យដំបូងនឹងត្រូវបាន Load មកទីនេះ --}}
        @include('admin.report._report_content') 
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
            
            {{-- Loading Spinner --}}
            <div id="modalLoading" class="text-center p-10">
                <svg class="inline w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67228 50 9.67228C27.4013 9.67228 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0492C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>

            {{-- Modal Content (បង្ហាញពេលទិន្នន័យមកដល់) --}}
            <div id="modalData" class="hidden">
                
                <h5 class="mb-4 text-lg font-semibold text-default">{{ __('messages.cash_reconciliation') }}</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">1. {{ __('messages.starting_cash_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalStartingCash"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">2. {{ __('messages.total_cash_sales_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-green-500" id="modalTotalCashSales"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-primary/10 dark:bg-primary/20 border border-primary">
                        <div class="text-sm font-medium text-primary">{{ __('messages.expected_cash_label') }} (1+2)</div>
                        <div class="mt-1 text-2xl font-semibold text-primary" id="modalExpectedCash"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.actual_cash_label') }}</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalActualCash"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.total_qr_sales_label') }} (Non-Cash)</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalTotalQRSales"></div>
                    </div>
                    <div class="p-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.total_card_sales_label') }} (Non-Cash)</div>
                        <div class="mt-1 text-2xl font-semibold text-default" id="modalTotalCardSales"></div>
                    </div>
                </div>

                {{-- ភាពខុសគ្នា (Difference) --}}
                <div id="modalDifferenceWrapper" class="p-4 mb-6 text-center rounded-lg">
                    <div class="text-sm font-medium uppercase text-default">{{ __('messages.cash_difference_label') }}</div>
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


{{-- ====================================================== --}}
{{-- START: SCRIPT (កូដកែប្រែថ្មី) --}}
{{-- បានរួមបញ្ចូល JavaScript ទាំងអស់ (Modal, AJAX, Bug Fix) មកកន្លែងតែមួយ --}}
{{-- ====================================================== --}}
<script>
    // == 1. Global Helper Functions (សម្រាប់ Modal និង Export) ==
    
    // Function សម្រាប់ Form Export (Excel/PDF)
    function setFormAction(actionUrl) {
        document.getElementById('shiftReportForm').action = actionUrl;
    }

    // Functions សម្រាប់ Format លេខ និង ពេលវេលា ក្នុង Modal
    function formatCurrency(number) {
        return '$ ' + parseFloat(number).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function formatTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // Functions សម្រាប់បើក/បិទ Modal (ត្រូវតែនៅ Global ព្រោះ HTML 'onclick' ហៅវា)
    const modal = document.getElementById('shiftDetailsModal');
    const modalContent = document.getElementById('shiftDetailsModalContent');
    const modalLoading = document.getElementById('modalLoading');
    const modalData = document.getElementById('modalData');

    async function openShiftDetailsModal(shiftId) {
        modal.classList.remove('hidden');
        modalData.classList.add('hidden');
        modalLoading.classList.remove('hidden');
        setTimeout(() => modalContent.classList.remove('scale-95'), 10); 

        try {
            const response = await fetch(`/report/shift-details/${shiftId}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();

            document.getElementById('modalTitle').innerText = `{{ __('messages.shift_details') }} #${data.shift.id} (${data.shift.user.name})`;
            
            // បំពេញ Summary Box
            document.getElementById('modalStartingCash').innerText = formatCurrency(data.shift.starting_cash);
            document.getElementById('modalTotalCashSales').innerText = formatCurrency(data.calculations.total_cash_sales);
            document.getElementById('modalExpectedCash').innerText = formatCurrency(data.calculations.expected_cash);
            document.getElementById('modalActualCash').innerText = formatCurrency(data.shift.ending_cash);
            document.getElementById('modalTotalQRSales').innerText = formatCurrency(data.calculations.total_qr_sales);
            document.getElementById('modalTotalCardSales').innerText = formatCurrency(data.calculations.total_card_sales);

            // គណនា Difference 
            const diffWrapper = document.getElementById('modalDifferenceWrapper');
            const diffText = document.getElementById('modalDifference');
            diffWrapper.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'bg-red-100', 'dark:bg-red-900/30', 'bg-yellow-100', 'dark:bg-yellow-900/30');
            diffText.classList.remove('text-green-600', 'text-red-600', 'text-yellow-600');

            // ប្រើ correctDifference ពីព្រោះ data.shift.difference ជា data ចាស់
            const correctDifference = parseFloat(data.shift.ending_cash) - parseFloat(data.calculations.expected_cash);

            if (correctDifference < 0) {
                diffText.innerText = `-${formatCurrency(Math.abs(correctDifference))} ({{ __('messages.short') }})`;
                diffText.classList.add('text-red-600', 'dark:text-red-400');
                diffWrapper.classList.add('bg-red-100', 'dark:bg-red-900/3D');
            } else if (correctDifference > 0) {
                diffText.innerText = `+${formatCurrency(correctDifference)} ({{ __('messages.over') }})`;
                diffText.classList.add('text-yellow-600', 'dark:text-yellow-400');
                diffWrapper.classList.add('bg-yellow-100', 'dark:bg-yellow-900/3D');
            } else {
                diffText.innerText = `${formatCurrency(correctDifference)} ({{ __('messages.perfect') }})`;
                diffText.classList.add('text-green-600', 'dark:text-green-400');
                diffWrapper.classList.add('bg-green-100', 'dark:bg-green-900/3D');
            }

            // បំពេញតារាង Orders
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
                orderListBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            {{ __('messages.no_orders_in_shift') }}
                        </td>
                    </tr>
                `;
            }

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


    // == 2. Event Listeners (AJAX, Modal Click, etc.) ==
    document.addEventListener('DOMContentLoaded', function () {
        
        // ភ្ជាប់ Modal click event (ពេល DOM ready)
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeShiftDetailsModal();
                }
            });
        }

        // --- ផ្នែក AJAX Filter ---
        const reportContainer = document.getElementById('report-data-container');
        const form = document.getElementById('shiftReportForm');
        const userSelect = document.getElementById('user_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Debounce function
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Function សម្រាប់ទាញទិន្នន័យ (Fetch Data)
        async function fetchReportData(url = null) {
            
            // បើមិនមាន URL, បង្កើតវាពី Form
            if (!url) {
                // ✅ START: កូដកែប្រែ (វិធីសាស្ត្រថ្មី)
                // យើងនឹងបង្កើត URLSearchParams ដោយខ្លួនឯង ព្រោះ FormData ពេលខ្លះមានបញ្ហា
                const params = new URLSearchParams();
                
                // 1. យក User ID
                const userSelect = document.getElementById('user_id');
                if (userSelect && userSelect.value) {
                    params.append('user_id', userSelect.value);
                }

                // 2. យក Start Date
                const startDateInput = document.getElementById('start_date');
                if (startDateInput && startDateInput.value) {
                    params.append('start_date', startDateInput.value);
                }

                // 3. យក End Date
                const endDateInput = document.getElementById('end_date');
                if (endDateInput && endDateInput.value) {
                    params.append('end_date', endDateInput.value);
                }
                // ✅ END: កូដកែប្រែ

                url = `${window.location.pathname}?${params.toString()}`;
            }

            reportContainer.classList.add('loading');

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                reportContainer.innerHTML = data.html;

                // (URL នឹងមិនផ្លាស់ប្តូរ ដូចដែលអ្នកចង់បាន)
                // window.history.pushState({}, '', url); 

            } catch (error) {
                console.error('Failed to fetch report data:', error);
            } finally {
                reportContainer.classList.remove('loading');
            }
        }

        // បង្កើត Debounced version
        const debouncedFetch = debounce(fetchReportData, 400);

        // --- ភ្ជាប់ Listeners (ជួសជុលកំហុស) ---
        
        // 1. User Dropdown
        if (userSelect) {
            userSelect.addEventListener('change', () => debouncedFetch());
        }

        // 2. Start Date (Initialize តែម្តង)
        if (startDateInput) {
            flatpickr(startDateInput, { 
                dateFormat: "Y-m-d",
                // ✅ START: កូដកែប្រែ
                onChange: function(selectedDates, dateStr, instance) {
                // ✅ END: កូដកែប្រែ
                    debouncedFetch();
                }
            });
        }

        // 3. End Date (Initialize តែម្តង)
        if (endDateInput) {
            flatpickr(endDateInput, { 
                dateFormat: "Y-m-d",
                // ✅ START: កូដកែប្រែ
                onChange: function(selectedDates, dateStr, instance) {
                // ✅ END: កូដកែប្រែ
                    debouncedFetch();
                }
            });
        }
        
        // 4. Pagination
        if (reportContainer) {
            reportContainer.addEventListener('click', function(e) {
                // ពិនិត្យមើលថាតើអ្វីដែលបានចុចគឺជា Link នៅក្នុង Pagination
                if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                    e.preventDefault(); // បញ្ឈប់ការ Reload Page
                    const url = e.target.href; // យក URL ពី Link នោះ
                    
                    if(url) {
                        fetchReportData(url); // ហៅ Function ជាមួយ URL ថ្មី
                    }
                }
            });
        }
    });
</script>
{{-- ====================================================== --}}
{{-- END: SCRIPT --}}
{{-- ====================================================== --}}
@endsection