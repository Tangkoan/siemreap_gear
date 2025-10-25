@extends('admin/admin_dashboard')
@section('admin')


    <style>
        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61; /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-full p-0">
                <div class="flex justify-between">
                    <h2 class="text-xl text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <div class="px-2">{{ __('messages.supplier') }}</div>
                    </h2>
                    <div>
                        <button type="button" class="icon-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none" @if (!Auth::user()->can('supplier.add')) disabled @endif>
                            <a href="{{ Auth::user()->can('supplier.add') ? route('add.supplier') : '#' }}" class="{{ !Auth::user()->can('supplier.add') ? 'pointer-events-none text-gray-400' : '' }}">
                                {{ __('messages.add_supplier') }}
                            </a>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <label for="perPage" class="text-sm text-slate-600">{{ __('messages.show') }}</label>
                                    <select id="perPage" name="perPage" class="h-10 border dark:bg-gray-900/80 bg-white/80 dark:text-white border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">{{ __('messages.all') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="w-full max-w-sm min-w-[200px] relative">
                                <div class="relative">
                                    <input class="dark:bg-gray-900/80 bg-white/80 dark:text-white bg-white w-full pr-11 h-10 pl-3 py-2  placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center  rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper overflow-y-auto max-h-[500px] dark:bg-gray-900/80 bg-white/80 rounded-md">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead >
                                <tr>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{!! __('messages.table_no') !!}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.name') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.email') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.phone') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.notes') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 "><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.table_action') }}</p></th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper mt-4"></div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // --- កូដផ្សេងៗទៀត (មិនកែប្រែ) ---
            $('.toggle-password').on('click', function() {
                // ... (your toggle password logic remains unchanged)
            });

            // --- កូដសម្រាប់ Fetch Data និង Pagination (កែសម្រួល) ---
            function fetchData(pageUrl = "{{ route('search.supplier') }}") {
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
                fetchData("{{ route('search.supplier') }}?page=1"); // Reset to page 1
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