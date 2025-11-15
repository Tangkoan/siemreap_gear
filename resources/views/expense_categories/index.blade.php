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
                    {{-- Icon ថ្មីសម្រាប់ Category --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    {{-- កែ Title --}}
                    <div class="px-2 text-3xl font-bold text-defalut">{{ __('messages.expense_category') }}</div> 
                </h2>

                <div class="flex items-center gap-x-2">
                    {{-- កែប៊ូតុង Add: ប្រើ <button> និង ID សម្រាប់ Modal --}}
                    @can('expense.category.add')
                    <button type="button" id="addCategoryBtn" class="bg-primary text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden">
                        {{ __('messages.add_expense_category') }}
                    </button>
                    @else
                    <button class="bg-primary py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50" disabled title="You don't have permission">
                        {{ __('messages.add_expense_category') }}
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
                                <input class="text-defalut card-dynamic-bg w-full pr-11 h-10 pl-3 py-2 text-sm border border-primary rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="ស្វែងរកតាមឈ្មោះ..." id="search" name="search" type="text" />
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
                                {{-- កែ Headers សម្រាប់ Category --}}
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">{!! __('messages.table_no') !!}</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ឈ្មោះប្រភេទ</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ការពិពណ៌នា</p></th>
                                <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-normal leading-none text-primary">ចំនួនប្រើប្រាស់</p></th>
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
<div id="categoryModal" class="fixed z-50 inset-0 overflow-y-auto modal-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel (ប្រើ class របស់អ្នក) --}}
        <div class="inline-block align-bottom card-dynamic-bg rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="categoryForm">
                @csrf
                <input type="hidden" id="categoryId" name="category_id">
                
                {{-- Modal Header --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-200">
                    <h3 class="text-lg leading-6 font-medium text-defalut" id="modalTitle">បន្ថែមប្រភេទថ្មី</h3>
                </div>

                {{-- Modal Body --}}
                <div class="card-dynamic-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="space-y-4">
                        
                         <div>
                            <label for="name" class="block text-sm font-medium text-defalut">ឈ្មោះប្រភេទ</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <span id="error_name" class="text-red-500 text-sm"></span>
                        </div>

                         <div>
                            <label for="description" class="block text-sm font-medium text-defalut">ការពិពណ៌នា (Optional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full text-defalut card-dynamic-bg rounded-md border-primary shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            <span id="error_description" class="text-red-500 text-sm"></span>
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
<script type="text/javascript">
    $(document).ready(function() {
        
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // --- 1. កំណត់ค่า TOAST (Green/Red) ទុក ---
        // នេះជាកូដសម្រាប់បង្កើត Toast ពណ៌បៃតង/ក្រហម ដូចវីដេអូ Product
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // នៅ góc លើ ខាងស្តាំ
            showConfirmButton: false, // គ្មានប៊ូតុង OK
            timer: 3000, // បាត់ទៅវិញក្នុង 3 វិនាទី
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        // --- ចប់ការកំណត់ค่า TOAST ---


        // --- ផ្នែកទី១: ផ្ទុកទិន្នន័យ (មិនផ្លាស់ប្តូរ) ---

        function fetchData(pageUrl = "{{ route('search.expense_category') }}") {
            let query = $('#search').val();
            let perPage = $('#perPage').val();

            $.ajax({
                url: pageUrl,
                type: "GET",
                data: {
                    search: query,
                    perPage: perPage
                },
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
            fetchData("{{ route('search.expense_category') }}?page=1");
        });

        $(document).on('click', '.pagination-wrapper a', function(e) {
            e.preventDefault();
            let pageUrl = $(this).attr('href');
            fetchData(pageUrl);
        });


        // --- ផ្នែកទី២: គ្រប់គ្រង Modal (Add, Edit, Delete) ---

        const modal = $('#categoryModal');
        const categoryForm = $('#categoryForm');
        
        $('#addCategoryBtn').on('click', function() {
            categoryForm[0].reset(); 
            $('#categoryId').val('');
            $('#modalTitle').text('បន្ថែមប្រភេទថ្មី');
            $('.text-red-500').text('');
            modal.removeClass('modal-hidden');
        });

        $('#closeModalBtn').on('click', function() {
            modal.addClass('modal-hidden');
        });

        $('tbody.tbody').on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let description = $(this).data('description');

            $('#modalTitle').text('កែសម្រួលប្រភេទ');
            $('#categoryId').val(id);
            $('#name').val(name);
            $('#description').val(description);
            $('.text-red-500').text('');
            modal.removeClass('modal-hidden');
        });

        // Submit Form (Add & Edit)
        categoryForm.on('submit', function(e) {
            e.preventDefault();
            $('#saveBtn').prop('disabled', true).text('កំពុងរក្សាទុក...');
            
            let categoryId = $('#categoryId').val();
            let url = categoryId ? "{{ url('api/expense-categories') }}/" + categoryId : "{{ route('expense_categories.json.store') }}";
            let method = categoryId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    modal.addClass('modal-hidden');
                    fetchData(); // ផ្ទុកទិន្នន័យក្នុងតារាងឡើងវិញ
                    
                    // === កែនៅទីនេះ (GREEN TOAST) ===
                    Toast.fire({
                        icon: 'success', // ពណ៌បៃតង
                        title: response.message
                    });
                    // === ចប់ការកែ ===
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.text-red-500').text(''); // Clear errors
                        $.each(errors, function(key, value) {
                            $('#error_' + key).text(value[0]);
                        });
                        // បង្ហាញ Error ក្នុង Form
                    } else {
                        // === កែនៅទីនេះ (RED TOAST) ===
                        Toast.fire({
                            icon: 'error', // ពណ៌ក្រហម
                            title: 'មានបញ្ហាកើតឡើង'
                        });
                        // === ចប់ការកែ ===
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

            // ផ្នែកសួរ "តើអ្នកប្រាកដទេ?" នៅតែប្រើ Modal ធំ គឺត្រឹមត្រូវហើយ
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
                        url: "{{ url('api/expense-categories') }}/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            fetchData();
                            
                            // === កែនៅទីនេះ (GREEN TOAST) ===
                            Toast.fire({
                                icon: 'success', // ពណ៌បៃតង
                                title: response.message
                            });
                            // === ចប់ការកែ ===
                        },
                        error: function(xhr) {
                            let message = 'មិនអាចលុបបានទេ';
                            // នេះជា error ពេលដែលលុប Category ដែលកំពុងប្រើ (ពី Controller)
                            if (xhr.status === 422 && xhr.responseJSON) {
                                message = xhr.responseJSON.message;
                            }
                            
                            // === កែនៅទីនេះ (RED TOAST) ===
                            Toast.fire({
                                icon: 'error', // ពណ៌ក្រហម
                                title: message
                            });
                            // === ចប់ការកែ ===
                        }
                    });
                }
            });
        });

    });
</script>
@endsection