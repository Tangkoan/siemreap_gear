@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>



    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-full p-0">
                <div class="flex justify-between">
                    <h2 class="text-xl text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                        </svg>
                        <div class="px-2">{{ __('messages.category') }}</div>
                    </h2>
                    <div>
                        <div>
                            <button type="button" class="bg-primary text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none" @if (!Auth::user()->can('category.add')) disabled @endif>
                                <a href="{{ Auth::user()->can('category.add') ? route('add.category') : '#' }}" class="{{ !Auth::user()->can('category.add') ? 'pointer-events-none text-gray-400' : '' }}">
                                    {{ __('messages.product_category') }}
                                </a>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-defalut">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class=" card-dynamic-bg text-defalut h-10 border border-slate-300 dark:border-black-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">{{ __('messages.all') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="w-full max-w-sm min-w-[200px] relative">
                                <div class="relative">
                                    <input class=" card-dynamic-bg text-defalut   w-full pr-11 h-10 pl-3 py-2  placeholder:text-slate-400 text-slate-700 text-sm border-slate-300  rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search_for_category') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center   rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="table-wrapper overflow-y-auto max-h-[500px] rounded-md"> --}}
                    <div class="table-wrapper overflow-x-auto overflow-y-auto max-h-[500px] lg:max-h-none card-dynamic-bg  rounded-md">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr>
                                    <th class="p-4 sticky top-0  z-10   dark:border-gray-700">
                                        <p class="text-sm  leading-none text-primary font-bold">{!! __('messages.table_no') !!}</p>
                                    </th>
                                    <th class="p-4 sticky top-0   z-10   dark:border-gray-700">
                                        <p class="text-sm font-bold leading-none text-primary">{{ __('messages.table_category_name') }}</p>
                                    </th>
                                    <th class="p-4 sticky top-0  z-10   dark:border-gray-700">
                                        <p class="text-sm font-bold leading-none text-primary">{{ __('messages.table_category_slug') }}</p>
                                    </th>
                                    <th class="p-4 sticky top-0  z-10   dark:border-gray-700">
                                        <p class="text-sm font-bold leading-none text-primary">{{ __('messages.table_created') }}</p>
                                    </th>
                                    <th class="p-4 sticky top-0  z-10   dark:border-gray-700">
                                        <p class="text-sm font-bold leading-none text-primary">{{ __('messages.table_action') }}</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper mt-4 "></div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // --- កូដ Delete (មិនកែប្រែ) ---
        $(document).on('click', '.btn-delete-category', function () {
            let id = $(this).data('id');
            let $row = $(this).closest('tr');
            if (confirm("Are you sure you want to delete this category?")) {
                $.ajax({
                    url: '/category/ajax-delete/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.status === 'success') {
                            $row.remove();
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Something went wrong!');
                    }
                });
            }
        });

        // --- កូដផ្សេងៗទៀត (មិនកែប្រែ) ---
        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                // ... (your toggle password logic remains unchanged)
            });

            // --- កូដសម្រាប់ Fetch Data និង Pagination (កែសម្រួល) ---
            function fetchData(pageUrl = "{{ route('search.category') }}") {
                let query = $('#search').val();
                let perPage = $('#perPage').val();
                $.ajax({
                    url: pageUrl, // Use the provided pageUrl
                    type: "GET",
                    data: {
                        search: query,
                        perPage: perPage
                    },
                    success: function(data) {
                        $('tbody').html(data.table);
                        // ✅ ជំហានទី២: កែ target ឱ្យចង្អុលទៅ class ថ្មី
                        $('.pagination-wrapper').html(data.pagination);
                    }
                });
            }

            fetchData();

            $('#search, #perPage').on('keyup change', function() {
                fetchData("{{ route('search.category') }}?page=1"); // Reset to page 1
            });

            // ✅ ជំហានទី៣: ធ្វើឱ្យ Pagination Links ដំណើរការ
            $(document).on('click', '.pagination-wrapper a', function(e) {
                e.preventDefault();
                let pageUrl = $(this).attr('href');
                fetchData(pageUrl);
            });
        });
    </script>
@endsection