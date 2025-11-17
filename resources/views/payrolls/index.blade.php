@extends('admin/admin_dashboard')
@section('admin')

{{-- ត្រូវតែមាន SweetAlert2 និង jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
    .tbody tr:hover { background-color: #cacaca61; }
    .dark .tbody tr:hover { background-color: #6d6d6d61; }
    .modal-hidden { display: none; }
</style>

<div class="container mx-auto p-6">
    <div class="lg:col-span-full p-0">
        
        {{-- 1. HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h2 class="text-xl text-defalut flex items-center">
                {{-- Icon សម្រាប់ Payroll --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m-6 2.25h6M12 9.75l.162.325a2.25 2.25 0 0 0 3.676 0L16.5 9.75m-4.5 0c.228-.452.486-.9.776-1.325a2.25 2.25 0 0 1 3.9 0c.29.425.548.873.776 1.325m-6 0h6m-6 0c.003.02.007.04.012.06m6 0c-.005-.02-.009-.04-.012-.06m-5.988 0h5.976c.005.02.009.04.012.06m-5.988 0c-.003-.02-.007-.04-.012-.06" />
                </svg>
                <div class="px-2 text-3xl font-bold text-defalut">បើកប្រាក់ខែ (Payroll)</div> 
            </h2>
            {{-- មិនចាំបាច់មានប៊ូតុង "Add New" ទេ --}}
        </div>

        {{-- 2. EMPLOYEE LIST (TABLE) --}}
        <div class="table-wrapper overflow-x-auto overflow-y-auto max-h-[700px] lg:max-h-none rounded-md card-dynamic-bg">
            <table class="w-full text-left table-auto min-w-max ">
                <thead>
                    <tr>
                        <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ឈ្មោះបុគ្គលិក</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">តួនាទី</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ប្រាក់ខែគោល</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ស្ថានភាពបើកប្រាក់ខែ</p></th>
                        <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">សកម្មភាព</p></th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    @forelse($employees as $employee)
                        <tr class="text-defalut" id="employee-row-{{ $employee->id }}">
                            
                            <td class="p-4 border-b border-slate-200"><p class="font-bold">{{ $employee->name }}</p></td>
                            <td class="p-4 border-b border-slate-200"><p class="font-normal">{{ $employee->position }}</p></td>
                            <td class="p-4 border-b border-slate-200"><p class="font-bold text-green-600">${{ number_format($employee->base_salary, 2) }}</p></td>
                            
                            {{-- 🟢 UPGRADE COLUMN "ស្ថានភាព" --}}
                            <td class="p-4 border-b border-slate-200">
                                @if($employee->payrolls->count() > 0)
                                    {{-- បើ $employee->payrolls មាន Record (count > 0) = បើកហើយ --}}
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-800 dark:text-green-200">
                                        Paid ({{ $employee->payrolls->first()->payment_date }})
                                    </span>
                                @else
                                    {{-- បើ $employee->payrolls មិនមាន Record (count == 0) = មិនទាន់បើក --}}
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-800 dark:text-yellow-200">
                                        Pending Payment
                                    </span>
                                @endif
                            </td>
                            
                            {{-- 🟢 UPGRADE COLUMN "សកម្មភាព" (ប៊ូតុង) --}}
                            <td class="p-4 border-b border-slate-200">
                                @if($employee->payrolls->count() > 0)
                                    {{-- បើបើកហើយ បង្ហាញប៊ូតុង สีប្រផេះ (Disabled) --}}
                                    <button type="button" 
                                            class="bg-gray-500 text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg"
                                            disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                                        Paid this month
                                    </button>
                                @else
                                    {{-- បើមិនទាន់បើក បង្ហាញប៊ូតុង สีแดง "Pay Salary" --}}
                                    <button type="button" 
                                            class="bg-primary text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg pay-btn"
                                            data-id="{{ $employee->id }}"
                                            data-name="{{ $employee->name }}"
                                            data-salary="{{ $employee->base_salary }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.98 9.98 0 0 0 10 18c2.25 0 4.367-.76 6.125-2.095a1.23 1.23 0 0 0 .41-1.412A9.98 9.98 0 0 0 10 12c-2.25 0-4.367.76-6.125 2.095Z" /></svg>
                                        Pay Salary
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-defalut border-b border-slate-200">
                                គ្មានបុគ្គលិក។ សូមចូលទៅ Employee Management ដើម្បីបន្ថែមបុគ្គលិកសិន។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 3. PAYROLL MODAL --}}
<div id="payrollModal" class="fixed z-50 inset-0 overflow-y-auto modal-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom card-dynamic-bg rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="payrollForm">
                @csrf
                <input type="hidden" id="employee_id" name="employee_id">
                
                {{-- Modal Header --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-medium text-defalut" id="modalTitle">Pay Salary for: </h3>
                </div>

                {{-- Modal Body --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- ផ្នែកខាងឆ្វេង (ព័ត៌មាន) --}}
                        <div class="space-y-4">
                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-defalut">ថ្ងៃទូទាត់</label>
                                <input type="date" name="payment_date" id="payment_date" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <span id="error_payment_date" class="text-red-500 text-sm"></span>
                            </div>
                            <div>
                                <label for="month_year" class="block text-sm font-medium text-defalut">សម្រាប់ខែ/ឆ្នាំ</label>
                                <input type="text" name="month_year" id="month_year" placeholder="e.g., Nov-2025" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <span id="error_month_year" class="text-red-500 text-sm"></span>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-defalut">កំណត់ចំណាំ (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                        
                        {{-- ផ្នែកខាងស្តាំ (ការគណនា) --}}
                        <div class="space-y-4 p-4 card-dynamic-bg border border-primary rounded-md">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-defalut">ប្រាក់ខែគោល ($)</label>
                                <input type="number" id="base_salary" name="base_salary_display" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary bg-gray-200 dark:bg-gray-700 shadow-sm sm:text-sm" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="bonus" class="block text-sm font-medium text-defalut">ប្រាក់បូក (+)</label>
                                <input type="number" step="0.01" name="bonus" id="bonus" value="0" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm calc-field" required>
                                <span id="error_bonus" class="text-red-500 text-sm"></span>
                            </div>
                            <div class="mb-4">
                                <label for="deduction" class="block text-sm font-medium text-defalut">ប្រាក់កាត់ (-)</label>
                                <input type="number" step="0.01" name="deduction" id="deduction" value="0" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm calc-field" required>
                                <span id="error_deduction" class="text-red-500 text-sm"></span>
                            </div>
                            <hr class="my-4 border-primary">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-defalut">ប្រាក់ខែសុទ្ធ (NET):</span>
                                <span id="net_salary" class="text-2xl font-bold text-green-600">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="card-dynamic-bg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                    <button type="submit" id="saveBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 sm:ml-3 sm:w-auto sm:text-sm">
                        ยืนยันការទូទាត់
                    </button>
                    <button type="button" id="closeModalBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-primary shadow-sm px-4 py-2 card-dynamic-bg text-base font-medium text-defalut hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. JAVASCRIPT --}}
<script type="text/javascript">
    $(document).ready(function() {
        
        // Setup CSRF Token
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Setup Toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        const modal = $('#payrollModal');
        const payrollForm = $('#payrollForm');
        const modalTitle = $('#modalTitle');

        // --- 1. បើក Modal ពេលចុច "Pay Salary" ---
        $('tbody.tbody').on('click', '.pay-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let salary = $(this).data('salary');

            payrollForm[0].reset();
            $('.text-red-500').text('');
            
            modalTitle.text('បើកប្រាក់ខែឲ្យ: ' + name);
            $('#employee_id').val(id);
            $('#base_salary').val(parseFloat(salary).toFixed(2));
            
            // បញ្ចូលថ្ងៃខែបច្ចុប្បន្ន
            $('#payment_date').val(new Date().toISOString().slice(0, 10));

            // បញ្ចូលខែ/ឆ្នាំ បច្ចុប្បន្ន
            const d = new Date();
            const month = d.toLocaleString('default', { month: 'short' }); // ឧ: Nov
            const year = d.getFullYear();
            $('#month_year').val(month + '-' + year);

            // Reset Bonus/Deduction
            $('#bonus').val(0);
            $('#deduction').val(0);
            
            // គណនា Net Salary
            calculateNetSalary();

            modal.removeClass('modal-hidden');
        });

        // --- 2. បិទ Modal ---
        $('#closeModalBtn').on('click', function() {
            modal.addClass('modal-hidden');
        });

        // --- 3. គណនា Net Salary (Real-time) ---
        function calculateNetSalary() {
            let base = parseFloat($('#base_salary').val()) || 0;
            let bonus = parseFloat($('#bonus').val()) || 0;
            let deduction = parseFloat($('#deduction').val()) || 0;
            let net = (base + bonus) - deduction;
            
            $('#net_salary').text('$' + net.toFixed(2));
        }
        
        // ពេលវាយបញ្ចូលក្នុងช่อง Bonus ឬ Deduction
        $('.calc-field').on('input', calculateNetSalary);

        // --- 4. Submit Form (AJAX) ---
        payrollForm.on('submit', function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('កំពុងដំណើរការ...');
            
            $.ajax({
                url: "{{ route('payrolls.json.store') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    modal.addClass('modal-hidden');
                    Toast.fire({
                        icon: 'success',
                        title: response.message // "បើកប្រាក់ខែឲ្យ ... ជោគជ័យ!"
                    });

                    
                    // អ្នកអាចបន្ថែម Logic ឧ: ប្តូរប៊ូតុង "Pay Salary" ទៅជា "Paid"
                    let employeeId = $('#employee_id').val();
                    let payButton = $('#employee-row-' + employeeId).find('.pay-btn');
                    // 1. ប្តូរ Text, បិទប៊ូតុង, ប្តូរสี
                    payButton.text('Paid this month')
                             .prop('disabled', true)
                             .removeClass('bg-primary')
                             .addClass('bg-gray-500');

                    // 2. ប្តូរ Icon ពី "Pay" ទៅ "Check Mark"
                    payButton.html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg> Paid this month');

                    // 3. ប្តូរ Status ក្នុងតារាងភ្លាមៗ
                    let statusCell = $('#employee-row-' + employeeId).find('td').eq(3); // Column ទី 4
                    let paymentDate = $('#payment_date').val();
                    statusCell.html('<span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-800 dark:text-green-200">Paid (' + paymentDate + ')</span>');
                    // --- 🟢 END UPGRADE JAVASCRIPT ---
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // បង្ហាញ Validation errors
                        let errors = xhr.responseJSON.errors;
                        $('.text-red-500').text('');
                        $.each(errors, function(key, value) {
                            $('#error_' + key).text(value[0]);
                        });

                        // បង្ហាញ Error ពិសេស (ឧ: រក Category មិនឃើញ)
                        if (xhr.responseJSON.message) {
                            Toast.fire({
                                icon: 'error',
                                title: xhr.responseJSON.message
                            });
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + xhr.responseJSON.message
                        });
                    }
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).text('ยืนยันការទូទាត់');
                }
            });
        });

    });
</script>
@endsection