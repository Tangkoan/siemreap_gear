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
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>

                            <div class="px-2">Purchase Pending Due</div>
                        </h2>
                        <div>

                        </div>
                    </div>



                    <div class="overflow-x-auto">
                        <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <label for="perPage" class="text-sm text-slate-600">Show</label>
                                    <select id="perPage" name="perPage"
                                        class="dark:bg-gray-800 dark:text-white h-10 border border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
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
                                            class="dark:bg-gray-800 dark:text-white bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                                            placeholder="Search for Name" id="search" name="search" type="text" />



                                        <button
                                            class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center dark:bg-gray-800 bg-white rounded "
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

                        <div class="table-wrapper overflow-y-auto max-h-[450px]">
                            <table class="w-full text-left table-auto min-w-max">
                                <thead>
                                    <tr>

                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                N<sup>0</sup>
                                            </p>
                                        </th>



                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Name
                                            </p>
                                        </th>
                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Purchase Date
                                            </p>
                                        </th>
                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Payment
                                            </p>
                                        </th>
                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Total
                                            </p>
                                        </th>

                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Pay
                                            </p>
                                        </th>
                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Due
                                            </p>
                                        </th>




                                        <th class="sticky top-0 dark:bg-gray-800 p-4 border-b border-slate-200 bg-slate-50">
                                            <p class="text-sm font-normal leading-none text-slate-500">
                                                Action
                                            </p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alldue as $key => $item)
                                        <tr class="hover:bg-slate-50 border-b border-slate-200">
                                            <td class="p-4 py-5 font-semibold text-sm text-slate-800">{{ $key + 1 }}</td>



                                            {{-- <td class="p-4 py-5 text-sm text-black">
                                                <img src="{{ asset($item->customer->image) }}"
                                                    style="width:50px; height: 40px;">
                                            </td> --}}

                                            <td class="p-4 py-5 text-sm text-black">{{ $item['supplier']['name'] }}</td>

                                            <td class="p-4 py-5 text-sm text-black">{{ $item->purchase_date }}
                                            </td>

                                            <td class="p-4 py-5 text-sm text-black ">{{ $item->payment_status }}</td>

                                            <td class="p-4 py-5 text-sm text-black">{{ $item->total  }}</td>
                                            <td class="p-4 py-5 text-sm text-black">{{ $item->pay }}</td>
                                            <td class="p-4 py-5 text-sm text-black">{{ $item->due }}</td>

                                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                <div class="flex items-center gap-x-6">

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
            function orderDue(id) {
                $.ajax({
                    type: 'GET',
                    url: '/order/due/' + id,
                    dataType: 'json',
                    success: function (data) {
                        $('#due').val(data.due); // បញ្ចូលចំនួន due
                        $('#order_id').val(id); // បញ្ចូល ID ទៅ input hidden (បើមាន)
                    }
                });
            }
        </script>

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
            $(document).ready(function () {
                function fetchData(page = 1) {
                    let query = $('#search').val();
                    let perPage = $('#perPage').val();

                    $.ajax({
                        url: "{{ route('search.purchase_pending_due') }}?page=" + page,
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