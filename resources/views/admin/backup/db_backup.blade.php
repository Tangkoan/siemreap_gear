@extends('admin/admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-full p-0">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-default flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    All Backups
                </h2>
                <div>
                    {{-- ✅ កែ URL ឱ្យប្រើ route() และ permission check ដែលត្រឹមត្រូវ --}}
                    <a href="{{ route('admin.backup.now') }}" class="icon-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        Backup Now
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <label for="perPage" class="text-sm text-slate-600">Show</label>
                            <select id="perPage" name="perPage" class="dark:bg-gray-800 dark:text-white h-10 border border-slate-300 rounded text-sm text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="w-full max-w-sm min-w-[200px] relative">
                            <input class="dark:bg-gray-800 dark:text-white bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md" placeholder="Search for File Name" id="search" name="search" type="text" />
                            <div class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-wrapper overflow-y-auto max-h-[500px]">
                    <table class="w-full text-left table-auto min-w-max">
                        <thead class="bg-slate-50 dark:bg-gray-800">
                            <tr>
                                <th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-800 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">N<sup>0</sup></th>
                                <th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-800 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">File Name</th>
                                <th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-800 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Size</th>
                                <th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-800 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Path</th>
                                <th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-800 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Action</th>
                            </tr>
                        </thead>
                        {{-- ✅ លុប @foreach ចេញ។ AJAX នឹងបំពេញទិន្នន័យនៅទីនេះ --}}
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center p-10 text-slate-500">
                                    Loading data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- ✅ បន្ថែម div សម្រាប់ pagination --}}
                <div id="pagination-links" class="pt-4"></div>
            </div>
        </div>
    </div>
</div>

{{-- Script សម្រាប់ពិនិត្យមើលស្ថានភាព Backup --}}
@if (session('start_backup_check'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.info('Backup process has started. The page will reload automatically when finished.');
            let interval = setInterval(function() {
                fetch("{{ route('backup.status') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'completed') {
                            clearInterval(interval);
                            toastr.success(data.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error checking backup status:', error);
                        clearInterval(interval);
                    });
            }, 5000);
        });
    </script>
@endif

{{-- Script សម្រាប់ Search និង Pagination --}}
<script type="text/javascript">
    $(document).ready(function() {
        function fetchData(page = 1) {
            let query = $('#search').val();
            let perPage = $('#perPage').val();

            $.ajax({
                // ✅ កែ URL ឱ្យហៅទៅ Route ថ្មី
                url: "{{ route('backup.search') }}?page=" + page,
                type: "GET",
                data: {
                    search: query,
                    perPage: perPage
                },
                success: function(data) {
                    $('tbody').html(data.table);
                    $('#pagination-links').html(data.pagination);
                },
                error: function() {
                     $('tbody').html('<tr><td colspan="5" class="text-center p-5 text-red-500">Failed to load data. Please check the console for errors.</td></tr>');
                }
            });
        }

        fetchData();

        // Debounce search input to avoid too many requests
        let searchTimeout;
        $('#search').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetchData(1);
            }, 500); // Wait 500ms after user stops typing
        });

        $('#perPage').on('change', function() {
            fetchData(1);
        });

        // Note: Pagination links are not generated by this setup,
        // as it's a simple info text. For full pagination links,
        // you would need to use Laravel's Paginator instance and render it.
    });
</script>
@endsection
