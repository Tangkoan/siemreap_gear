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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl text-default flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div class="px-2">{{ __('messages.condition') }}</div>
                    </h2>
                    <div>
                        <button type="button" class="bg-primary text-white py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none" @if (!Auth::user()->can('condition.add')) disabled @endif>
                            <a href="{{ Auth::user()->can('condition.add') ? route('add.condition') : '#' }}" class="{{ !Auth::user()->can('condition.add') ? 'pointer-events-none text-gray-400' : '' }}">
                                {{ __('messages.add_condition') }}
                            </a>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 mt-1 pl-3 gap-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm sm:text-sm text-defalut">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class="card-dynamic-bg text-defalut h-10 border border-primary rounded text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto ml-0 sm:ml-3">
                            <div class="w-full max-w-sm min-w-[140px] relative">
                                <div class="relative">
                                    <input class="card-dynamic-bg text-defalut w-full pr-11 h-10 pl-3 py-2 placeholder:text-slate-400  text-sm sm:text-sm border border-primary rounded transition duration-200 ease focus:outline-none  shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center rounded" type="button" aria-label="search">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 sm:w-8 sm:h-8 text-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="table-wrapper overflow-auto max-h-[500px] rounded-md"> --}}
                    <div class="table-wrapper overflow-x-auto overflow-y-auto max-h-[500px] lg:max-h-none card-dynamic-bg rounded-md">
                        <table class="w-full text-left table-auto min-w-[640px] sm:min-w-full">
                            <thead >
                                <tr>
                                    <th class="p-3 sticky top-0  z-10 border-b border-primary">
                                        <p class="text-sm font-meduim leading-none text-primary">{!! __('messages.table_no') !!}</p>
                                    </th>
                                    <th class="p-3 sticky top-0  z-10 border-b border-primary">
                                        <p class="text-sm font-meduim leading-none text-primary">{{ __('messages.condition_name') }}</p>
                                    </th>
                                    <th class="p-3 sticky top-0  z-10 border-b border-primary">
                                        <p class="text-sm font-meduim leading-none text-primary">{{ __('messages.table_created') }}</p>
                                    </th>
                                    <th class="p-3 sticky top-0  z-10 border-b border-primary">
                                        <p class="text-sm font-meduim leading-none text-primary">{{ __('messages.table_action') }}</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper mt-4">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            function fetchData(pageUrl = "{{ route('search.condition') }}") {
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
                        $('tbody').html(data.table);
                        // ✅ ជំហានទី២: កែ target ឱ្យចង្អុលទៅ class ថ្មី
                        $('.pagination-wrapper').html(data.pagination);
                    }
                });
            }

            fetchData();

            $('#search, #perPage').on('keyup change', function() {
                fetchData("{{ route('search.condition') }}?page=1"); // Reset to page 1
            });

            // ✅ ជំហានទី៣: ធ្វើឱ្យ Pagination Links ដំណើរការ
            // កូដនេះនឹងចាំ lắng nghe រាល់ការចុចលើ Link `<a>` ដែលស្ថិតនៅក្នុង `.pagination-wrapper`
            $(document).on('click', '.pagination-wrapper a', function(e) {
                e.preventDefault();
                let pageUrl = $(this).attr('href');
                fetchData(pageUrl);
            });
        });
    </script>
@endsection