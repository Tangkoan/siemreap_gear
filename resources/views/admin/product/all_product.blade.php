@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-full p-0">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-xl text-default flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <div class="px-2">{{ __('messages.product') }}</div>
                    </h2>
                    <div class="flex items-center gap-x-2">
                        @can('product.import')
                        <!-- បើមានសិទ្ធ -->
                        <button type="button"
                        class="button-imaport py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                        <a href="{{ route('import.product') }}">{{ __('messages.import') }}</a>
                        </button>
                        @else
                        <!-- បើអត់មានសិទ្ធ -->
                        <button class="button-imaport py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none "
                        disabled title="You don't have permission to access Import">
                        {{ __('messages.import') }}
                        </button>
                        @endcan

                        @can('product.export')
                            <a href="{{ route('export') }}" class="button-export py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden">
                                {{ __('messages.export') }}
                            </a>
                        @else
                            <button class="button-export py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50" disabled title="You don't have permission">
                                {{ __('messages.export') }}
                            </button>
                        @endcan

                        @can('product.add')
                            <a href="{{ route('add.product') }}" class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden">
                                {{ __('messages.add_product') }}
                            </a>
                        @else
                            <button class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50" disabled title="You don't have permission">
                                {{ __('messages.add_product') }}
                            </button>
                        @endcan
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-slate-600">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class="h-10 border dark:bg-gray-900 dark:text-white border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="all">{{ __('messages.all') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="ml-3">
                            <div class="w-72 relative">
                                <div class="relative">
                                    <input class="dark:text-white dark:bg-gray-900 bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="dark:bg-gray-900 absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper overflow-x-auto overflow-y-auto max-h-[500px] lg:max-h-none">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{!! __('messages.table_no') !!}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.image') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.product_code') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.product_name') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.category') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.condition_name') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.price') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.supplier') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __(key: 'messages.inventory') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __(key: 'messages.status') }}</p></th>
                                    <th class="sticky top-0 dark:bg-gray-900 p-4 border-b border-slate-200 bg-slate-50"><p class="text-sm font-normal leading-none text-slate-500">{{ __('messages.table_action') }}</p></th>
                                </tr>
                            </thead>
                            <tbody>
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
            function fetchData(pageUrl = "{{ route('search.product') }}") {
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
                fetchData("{{ route('search.product') }}?page=1"); // Reset to page 1
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