@extends('admin/admin_dashboard')
@section('admin')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <div class="container mx-auto p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform "> --}}
                <div class="lg:col-span-full p-0">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <div class="px-2">{{ __('messages.supplier') }}</div>
                        </h2>
                        <div>


                            {{-- <button type="button"
          class="icon-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent  focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
          <a href="{{ route('add.supplier') }}">Add Suppler</a>
        </button> --}}

                            <button type="button"
                                class="icon-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none"
                                @if (!Auth::user()->can('supplier.add')) disabled @endif>
                                <a href="{{ Auth::user()->can('supplier.add') ? route('add.supplier') : '#' }}"
                                    class="{{ !Auth::user()->can('supplier.add') ? 'pointer-events-none text-gray-400' : '' }}">
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
                                        <select id="perPage" name="perPage"
                                            class="h-10 border dark:bg-gray-800 dark:text-white border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                            <option value="10" selected>10</option> <!-- ✅ Default -->
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
                                        <input
                                            class="dark:bg-gray-800 dark:text-white bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                            placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                        <button
                                            class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center dark:bg-gray-800  bg-white rounded "
                                            type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                            {{-- class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 hover:bg-gray-800 shadow-md rounded-lg bg-clip-border">  --}}
                            <div class="table-wrapper overflow-y-auto max-h-[500px]">
                            <table class="w-full text-left table-auto min-w-max">
                                <thead class="bg-slate-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="sticky top-0   p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                               {!! __('messages.table_no') !!}
                                            </p>
                                        </th>

                                        <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                 {{ __('messages.name') }}
                                            </p>
                                        </th>
                                        <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                {{ __('messages.email') }}
                                            </p>
                                        </th>
                                        <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                {{ __('messages.phone') }}
                                            </p>
                                        </th>

                                        <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                {{ __('messages.notes') }}
                                            </p>
                                        </th>

   
                                        <th class="sticky top-0 p-4 border-b border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800">
                                            <p class="text-sm font-normal leading-none text-slate-500 ">
                                                {{ __('messages.table_action') }}
                                            </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    
                                </tbody>
                            </table>


                        </div>

                    </div>



                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.toggle-password').on('click', function() {
                    const targetId = $(this).data('target');
                    const passwordField = $('#' + targetId);
                    const icon = $(this).find('svg');

                    // Toggle the type attribute
                    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                    passwordField.attr('type', type);

                    // Toggle the eye icon
                    if (type === 'password') {
                        icon.html(
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                        ); // Eye open
                    } else {
                        icon.html(
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7A10.05 10.05 0 0112 5c.424 0 .84.037 1.246.109m 3.167 3.167a3 3 0 11-4.243 4.243m4.243-4.243a3 3 0 00-4.243 4.243M3 3l3.59 3.59m0 0a9.953 9.953 0 01.442-.442L21 21"></path>'
                        ); // Eye closed
                    }
                });
            });


            // Start Short data
            $(document).ready(function() {
                function fetchData(page = 1) {
                    let query = $('#search').val();
                    let perPage = $('#perPage').val();

                    $.ajax({
                        url: "{{ route('search.supplier') }}?page=" + page,
                        type: "GET",
                        data: {
                            search: query,
                            perPage: perPage
                        },
                        success: function(data) {
                            $('tbody').html(data.table);
                            $('#pagination-links').html(data.pagination);
                        }
                    });
                }

                // Trigger fetch on load
                fetchData(); // ✅ Fetch 10 by default

                // Search or perPage change
                $('#search, #perPage').on('keyup change', function() {
                    fetchData(); // Always page 1 when changed
                });

                // Pagination click
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    fetchData(page);
                });
            });

            // End
        </script>
@endsection
