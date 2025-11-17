@extends('admin/admin_dashboard')
@section('admin')

{{-- 
    សំខាន់: ខ្ញុំសន្មត់ថាអ្នកបានដំឡើង SweetAlert2 
    (សម្រាប់ confirmation ពេលលុប) និង Toastr (សម្រាប់ notification)
    បើមិនទាន់មានទេ អ្នកអាចប្រើ alert() ធម្មតា 
--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<style>
    .tbody tr:hover {
        background-color: #cacaca61;
    }
    .dark .tbody tr:hover {
        background-color: #6d6d6d61;
    }
    .modal-hidden {
        display: none;
    }
</style>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-full p-0">
            
            {{-- 1. HEADER --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-xl text-defalut flex items-center">
                    {{-- Icon ថ្មីសម្រាប់ Employee --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-2.253 9.337 9.337 0 0 0-2.253-4.121m-6.504 5.274c.247.03.495.052.75.072m-1.125-2.247a4.5 4.5 0 0 1-1.13-1.897l-8.932-8.931a1.875 1.875 0 0 1 0-2.652L4.487 2.1a1.875 1.875 0 0 1 2.652 0l8.932 8.931a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685Z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v.01M15 12v.01M11.25 11.25v.01M12.75 12.75v.01M10.5 13.5v.01M13.5 10.5v.01" />
                    </svg>
                    {{-- កែ Title --}}
                    <div class="px-2 text-3xl font-bold text-defalut">ការគ្រប់គ្រងបុគ្គលិក</div> 
                </h2>

                <div class="flex items-center gap-x-2">
                    {{-- កែប៊ូតុង Add: ប្រើ <button> និង ID សម្រាប់ Modal --}}
                    @can('employee.add')
                    <button type="button" id="addEmployeeBtn" class="bg-primary text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden">
                        បន្ថែមបុគ្គលិកថ្មី
                    </button>
                    @else
                    <button class="bg-primary py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50" disabled title="You don't have permission">
                        បន្ថែមបុគ្គលិកថ្មី
                    </button>
                    @endcan
                </div>
            </div>

            {{-- 2. CONTROLS (Search & PerPage) --}}
            <div class="overflow-x-auto">
                <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <label for="perPage" class="text-sm text-defalut">{{ __('messages.show') }}</label>
                            <select id="perPage" name="perPage" class="h-10 border card-dynamic-bg border-primary rounded text-sm text-defalut focus:outline-none focus:ring-1 focus:ring-slate-400">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="all">{{ __('messages.all') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="w-72 relative">
                            <div class="relative">
                                <input class="text-defalut card-dynamic-bg w-full pr-11 h-10 pl-3 py-2 text-sm border border-primary rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="ស្វែងរកតាមឈ្មោះ, តួនាទី..." id="search" name="search" type="text" />
                                <button class=" absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center rounded" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. TABLE --}}
                <div class="table-wrapper overflow-x-auto overflow-y-auto max-h-[500px] lg:max-h-none rounded-md card-dynamic-bg">
                    <table class="w-full text-left table-auto min-w-max ">
                        <thead>
                            <tr>
                                {{-- កែ Headers សម្រាប់ Employee --}}
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">{!! __('messages.table_no') !!}</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ឈ្មោះបុគ្គលិក</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">លេខទូរស័ព្ទ</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">តួនាទី</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ប្រាក់ខែគោល</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ស្ថានភាព</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">{{ __('messages.table_action') }}</p></th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            {{-- ទិន្នន័យនឹងមកពី AJAX --}}
                        </tbody>
                    </table>
                </div>

                {{-- 4. PAGINATION --}}
                <div class="pagination-wrapper mt-4"></div>

            </div>
        </div>
    </div>
</div>


{{-- 5. ADD/EDIT MODAL --}}
<div id="employeeModal" class="fixed z-50 inset-0 overflow-y-auto modal-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel (ប្រើ class របស់អ្នក) --}}
        <div class="inline-block align-bottom card-dynamic-bg rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="employeeForm">
                @csrf
                <input type="hidden" id="employeeId" name="employee_id">
                
                {{-- Modal Header --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-medium text-defalut" id="modalTitle">បន្ថែមបុគ្គលិកថ្មី</h3>
                </div>

                {{-- Modal Body --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                         <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-defalut">ឈ្មោះបុគ្គលិក</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <span id="error_name" class="text-red-500 text-sm"></span>
                        </div>

                         <div>
                            <label for="phone" class="block text-sm font-medium text-defalut">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" id="phone" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <span id="error_phone" class="text-red-500 text-sm"></span>
                        </div>

                         <div>
                            <label for="position" class="block text-sm font-medium text-defalut">តួនាទី</label>
                            <input type="text" name="position" id="position" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <span id="error_position" class="text-red-500 text-sm"></span>
                        </div>
                        
                        <div>
                            <label for="base_salary" class="block text-sm font-medium text-defalut">ប្រាក់ខែគោល ($)</label>
                            <input type="number" step="0.01" name="base_salary" id="base_salary" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <span id="error_base_salary" class="text-red-500 text-sm"></span>
                        </div>

                        <div>
                            <label for="join_date" class="block text-sm font-medium text-defalut">ថ្ងៃចូលធ្វើការ</label>
                            <input type="date" name="join_date" id="join_date" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <span id="error_join_date" class="text-red-500 text-sm"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="status" class="block text-sm font-medium text-defalut">ស្ថានភាព</label>
                            <select name="status" id="status" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <span id="error_status" class="text-red-500 text-sm"></span>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="card-dynamic-bg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                    <button type="submit" id="saveBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 sm:ml-3 sm:w-auto sm:text-sm">
                        រក្សាទុក
                    </button>
                    <button type="button" id="closeModalBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-primary shadow-sm px-4 py-2 card-dynamic-bg text-base font-medium text-defalut hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- 6. JAVASCRIPT (jQuery) --}}
<script type"text/javascript">
    $(document).ready(function() {
        
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // --- 1. កំណត់ค่า TOAST (Green/Red) ទុក ---
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

        // --- ផ្នែកទី១: ផ្ទុកទិន្នន័យ ---

        function fetchData(pageUrl = "{{ route('search.employee') }}") {
            let query = $('#search').val();
            let perPage = $('#perPage').val();

            $.ajax({
                url: pageUrl,
                type: "GET",
                data: { search: query, perPage: perPage },
                success: function(data) {
                    $('tbody.tbody').html(data.table);
                    $('.pagination-wrapper').html(data.pagination);
                },
                error: function(xhr) {
                    console.log('Error fetching data:', xhr);
                }
            });
        }
        fetchData();

        $('#search, #perPage').on('keyup change', function() {
            fetchData("{{ route('search.employee') }}?page=1");
        });

        $(document).on('click', '.pagination-wrapper a', function(e) {
            e.preventDefault();
            fetchData($(this).attr('href'));
        });


        // --- ផ្នែកទី២: គ្រប់គ្រង Modal (Add, Edit, Delete) ---

        const modal = $('#employeeModal');
        const employeeForm = $('#employeeForm');
        
        // បើក Modal សម្រាប់ Add
        $('#addEmployeeBtn').on('click', function() {
            employeeForm[0].reset(); 
            $('#employeeId').val('');
            $('#modalTitle').text('បន្ថែមបុគ្គលិកថ្មី');
            $('#status').val('active'); // Set default status
            $('.text-red-500').text('');
            modal.removeClass('modal-hidden');
        });

        // បិទ Modal
        $('#closeModalBtn').on('click', function() {
            modal.addClass('modal-hidden');
        });

        // បើក Modal សម្រាប់ Edit
        $('tbody.tbody').on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            
            // ប្រើ AJAX $.get ព្រោះ data ស្មុគស្មាញជាង
            $.get("{{ url('api/employees') }}/" + id, function(data) {
                $('#modalTitle').text('កែសម្រួលបុគ្គលិក');
                $('#employeeId').val(data.id);
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#position').val(data.position);
                $('#base_salary').val(data.base_salary);
                $('#join_date').val(data.join_date);
                $('#status').val(data.status);
                
                $('.text-red-500').text('');
                modal.removeClass('modal-hidden');
            });
        });

        // Submit Form (Add & Edit)
        employeeForm.on('submit', function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('កំពុងរក្សាទុក...');
            
            let employeeId = $('#employeeId').val();
            let url = employeeId ? "{{ url('api/employees') }}/" + employeeId : "{{ route('employees.json.store') }}";
            let method = employeeId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    modal.addClass('modal-hidden');
                    fetchData(); // ផ្ទុកទិន្នន័យក្នុងតារាងឡើងវិញ
                    Toast.fire({
                        icon: 'success', // ពណ៌បៃតង
                        title: response.message
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.text-red-500').text(''); // Clear errors
                        $.each(errors, function(key, value) {
                            $('#error_' + key).text(value[0]);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error', // ពណ៌ក្រហម
                            title: 'មានបញ្ហាកើតឡើង'
                        });
                    }
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).text('រក្សាទុក');
                }
            });
        });

        // លុបទិន្នន័យ (Delete)
        $('tbody.tbody').on('click', '.delete-btn', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'តើអ្នកប្រាកដទេ?',
                text: "អ្នកមិនអាចយកទិន្នន័យនេះមកវិញបានទេ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'យល់ព្រម, លុបវា!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('api/employees') }}/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            fetchData();
                            Toast.fire({
                                icon: 'success', // ពណ៌បៃតង
                                title: response.message
                            });
                        },
                        error: function(xhr) {
                            let message = 'មិនអាចលុបបានទេ';
                            if (xhr.status === 422 && xhr.responseJSON) {
                                message = xhr.responseJSON.message; // ឧ: "Cannot delete! This employee has payroll history."
                            }
                            Toast.fire({
                                icon: 'error', // ពណ៌ក្រហម
                                title: message
                            });
                        }
                    });
                }
            });
        });

    });
</script>
@endsection