@extends('admin/admin_dashboard')
@section('admin')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <div class="container mx-auto p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform "> --}}
                    <div class="lg:col-span-full p-0">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                            </svg>

                            <div class="px-2">Pending Orders</div>
                        </h2>
                        <div>
    {{-- 
                            <button type="button"
                                class=" button-imaport  py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent    focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">

                                <a href="{{ route('import.product') }}">Import</a>
                            </button>

                            <button type="button"
                                class="button-export py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent  focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
                                Export
                            </button> --}}

                            {{-- <button type="button"
                                class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent   focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
                                <a href="{{ route('add.product') }}">Add Product</a>
                            </button> --}}
                        </div>
                    </div>



                    <div class="overflow-x-auto">
                        <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <label for="perPage" class="text-sm text-slate-600">Show</label>
                                    <select id="perPage" name="perPage"
                                        class="h-10 border border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                        <option value="6" selected>6</option>
                                        <option value="10">10</option> <!-- ✅ Default -->
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="w-full max-w-sm min-w-[200px] relative">
                                    <div class="relative">
                                        <input
                                            class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                            placeholder="Search for name" id="search" name="search" type="text" />
                                        <button
                                            class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
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

                        <div
                            class="table-wrapper overflow-y-auto max-h-[450px]">
                            <table class="w-full text-left table-auto min-w-max">
                                <thead>
                                    <tr>

                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                N<sup>0</sup>
                                            </p>
                                        </th>



                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Name
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Order Date
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Payment
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Invoice
                                            </p>
                                        </th>

                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Pay
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Status
                                            </p>
                                        </th>




                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Action
                                            </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $item)
                                        <tr class="hover:bg-slate-50 border-b border-slate-200">
                                            <td class="p-4 py-5 font-semibold text-sm text-slate-800">{{ $key + 1 }}</td>



                                            {{-- <td class="p-4 py-5 text-sm text-black"> 
                                                <img src="{{ asset($item->customer->image) }}" style="width:50px; height: 40px;">
                                            </td> --}}

                                            <td class="p-4 py-5 text-sm text-black">{{ $item['customer']['name'] }}</td>

                                            <td class="p-4 py-5 text-sm text-black">{{ $item->order_date }}
                                            </td>

                                            <td class="p-4 py-5 text-sm text-black ">{{ $item->payment_status }}</td>

                                            <td class="p-4 py-5 text-sm text-black">{{ $item->invoice_no }}</td>
                                            <td class="p-4 py-5 text-sm text-black">{{ $item->pay }}</td>
                                            <td class="p-4 py-5 text-sm text-black">{{ $item->order_status }}</td>

                                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                <div class="flex items-center gap-x-6">


                                                    <button
                                                        class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                                                        <a href="{{ route('edit.category', $item->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                            </svg>
                                                        </a>
                                                    </button>

                                                    <button
                                                        class="icon-edit text-gray-500 transition-colors duration-200 dark:hover:text-gray-800 dark:text-gray-300 hover:text-gray-800 focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 5v14m3-14v14m4-14v14m4-14v14m3-14v14m3-14v14" />
                                                        </svg>
                                                    </button>

                                                    <button
                                                        class="icon-detail text-gray-500 transition-colors duration-200 dark:hover:text-gray-800 dark:text-gray-300 hover:text-gray-800 focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>

                                                    </button>

                                                    <button type="button"
                                                        class=" icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                                                        <a href="{{ route('delete.category', $item->id) }}" id="delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </a>
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
                        url: "{{ route('search.order') }}?page=" + page,
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
