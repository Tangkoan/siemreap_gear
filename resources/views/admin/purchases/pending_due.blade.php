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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <div class="px-2 text-3xl font-bold text-defalut">{{ __('messages.purchase_pending_due') }}</div>
                    </h2>
                    <div>
                        {{-- Buttons here if any --}}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm text-defalut">{{ __('messages.show') }}</label>
                                <select id="perPage" name="perPage" class="text-defalut card-dynamic-bg h-10 border border-primary rounded text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
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
                                    <input class="text-defalut card-dynamic-bg w-full pr-11 h-10 pl-3 py-2 text-sm border border-primary rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="{{ __('messages.search') }}" id="search" name="search" type="text" />
                                    <button class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center  rounded" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-primary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper overflow-y-auto max-h-[520px] card-dynamic-bg rounded-md">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead>
                                <tr >
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{!! __('messages.table_no') !!}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.supplier_name') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.invoice') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.purchase_date') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.payment_method') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.total') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.pay') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.due') }}</p></th>
                                    <th class="sticky top-0 p-4 border-b border-slate-200 "><p class="text-sm font-medium leading-none text-primary">{{ __('messages.table_action') }}</p></th>
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
        // --- កូដផ្សេងៗទៀត (មិនកែប្រែ) ---
        function orderDue(id) {
            $.ajax({
                type: 'GET',
                url: '/order/due/' + id,
                dataType: 'json',
                success: function (data) {
                    $('#due').val(data.due);
                    $('#order_id').val(id);
                }
            });
        }

        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                // ... (your toggle password logic remains unchanged)
            });

            // --- កូដសម្រាប់ Fetch Data និង Pagination (កែសម្រួល) ---
            function fetchData(pageUrl = "{{ route('search.purchase_pending_due') }}") {
                let query = $('#search').val();
                let perPage = $('#perPage').val();

                $.ajax({
                    url: pageUrl, // Use the provided pageUrl
                    type: "GET",
                    data: {
                        search: query,
                        perPage: perPage
                    },
                    success: function (data) {
                        $('tbody').html(data.table);
                        // ✅ ជំហានទី២: កែ target ឱ្យចង្អុលទៅ class ថ្មី
                        $('.pagination-wrapper').html(data.pagination);
                    }
                });
            }

            fetchData();

            $('#search, #perPage').on('keyup change', function () {
                fetchData("{{ route('search.purchase_pending_due') }}?page=1"); // Reset to page 1
            });

            // ✅ ជំហានទី៣: ធ្វើឱ្យ Pagination Links ដំណើរការ
            $(document).on('click', '.pagination-wrapper a', function (e) {
                e.preventDefault();
                let pageUrl = $(this).attr('href');
                fetchData(pageUrl);
            });
        });
    </script>
@endsection