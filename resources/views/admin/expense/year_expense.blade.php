@extends('admin/admin_dashboard')
@section('admin')

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <div class="container mx-auto p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform ">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                            <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                            </svg>
                            <div class="px-2">Year Expense</div>
                        </h2>


                        <div>




                            <button type="button"
                                class="icon-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none"
                                @if(!Auth::user()->can('expense.add')) disabled @endif>
                                <a href="{{ Auth::user()->can('expense.add') ? route('add.expense') : '#' }}"
                                    class="{{ !Auth::user()->can('expense.add') ? 'pointer-events-none text-gray-400' : '' }}">
                                    Add Expense
                                </a>
                            </button>
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
                                            placeholder="Search for supplier" id="search" name="search" type="text" />
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

                        @php
    $year = date("Y");
    $expenseyear = App\Models\Expense::where('year', $year)->sum('amount');

                        @endphp

                        <h4 style="color:rgb(20, 9, 9); font-size: 30px; text-align: center; padding: 20px;"> Year Total :
                            ${{ $expenseyear }}</h4>

                        <div div class="table-wrapper overflow-y-auto max-h-[370px]">
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
                                                Details
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Amount
                                            </p>
                                        </th>
                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Month
                                            </p>
                                        </th>



                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Create
                                            </p>
                                        </th>

                                        <th class="p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500 ">
                                                Action
                                            </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($yearexpense as $key => $item)

                                        <tr class="hover:bg-slate-50 border-b border-slate-200">
                                            <td class="p-4 py-5">
                                                <p class="block font-semibold text-sm text-slate-800">{{ $key + 1 }}</p>
                                            </td>

                                            <td class="p-4 py-5">
                                                <p class="text-sm text-slate-500">{{ $item->details }}</p>
                                            </td>
                                            <td class="p-4 py-5">
                                                {{-- <p class="text-sm text-slate-500">{{ $item->email ? $item->email : 'None' }}
                                                </p> --}}
                                                <p class="text-sm {{ $item->amount ? 'text-slate-500' : 'text-red-500' }}">
                                                    {{ $item->amount ?? 'None' }}
                                                </p>
                                            </td>
                                            <td class="p-4 py-5">
                                                <p class="text-sm text-slate-500">{{ $item->year }}</p>
                                            </td>

                                            <td class="p-4 py-5">
                                                <p class="text-sm text-slate-500">{{ $item->created }}</p>
                                            </td>


                                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                <div class="flex items-center gap-x-6">


                                                    <button
                                                        class="text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                                                        <a href="{{ route('edit.supplier', $item->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
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
            $(document).ready(function () {
                $('.toggle-password').on('click', function () {
                    const targetId = $(this).data('target');
                    const passwordField = $('#' + targetId);
                    const icon = $(this).find('svg');

                    // Toggle the type attribute
                    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                    passwordField.attr('type', type);

                    // Toggle the eye icon
                    if (type === 'password') {
                        icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'); // Eye open
                    } else {
                        icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7A10.05 10.05 0 0112 5c.424 0 .84.037 1.246.109m 3.167 3.167a3 3 0 11-4.243 4.243m4.243-4.243a3 3 0 00-4.243 4.243M3 3l3.59 3.59m0 0a9.953 9.953 0 01.442-.442L21 21"></path>'); // Eye closed
                    }
                });
            });


            // Start Short data
            $(document).ready(function () {
                function fetchData(page = 1) {
                    let query = $('#search').val();
                    let perPage = $('#perPage').val();

                    $.ajax({
                        url: "{{ route('search.month') }}?page=" + page,
                        type: "GET",
                        data: {
                            search: query,
                            perPage: perPage
                        },
                        success: function (data) {
                            $('tbody').html(data.table);
                            $('#pagination-links').html(data.pagination);
                        }
                    });
                }

                // Trigger fetch on load
                fetchData(); // ✅ Fetch 10 by default

                // Search or perPage change
                $('#search, #perPage').on('keyup change', function () {
                    fetchData(); // Always page 1 when changed
                });

                // Pagination click
                $(document).on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    fetchData(page);
                });
            });

            // End

        </script>

@endsection