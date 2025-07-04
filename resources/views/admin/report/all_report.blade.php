@extends('admin.admin_dashboard')
@section('admin')

    <div class="page-content py-8 px-4 dark:bg-gray-800 min-h-screen">
        <div class="container mx-auto">

            <!-- Page Title -->
            <div class="flex flex-col md:flex-row items-center justify-between mb-10">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">📊 Admin Report Filters</h1>
            </div>

            <!-- Filter Forms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Search by Date -->
                <div class="bg-white dark:bg-gray-700 shadow-xl rounded-2xl p-6">
                    {{-- <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data"> --}}
                        <form id="myForm" action="{{ route('admin.search.bydate') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">📅 Search By Date</h2>

                        <div class="mb-6">
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                            <input type="date" name="date"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400">
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            🔍 Search
                        </button>
                    </form>
                </div>

                <!-- Search by Month -->
                <div class="bg-white dark:bg-gray-700 shadow-xl rounded-2xl p-6">
                    <form id="myForm" action="{{ route('admin.search.bymonth') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">📆 Search By Month</h2>

                        <div class="mb-4">
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                            <select name="month"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400">
                                <option>Select Month</option>
                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                    <option value="{{ $month }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                            <select name="year_name"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400">
                                <option>Select Year</option>
                                @foreach (range(2022, 2026) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            🔍 Search
                        </button>
                    </form>
                </div>

                <!-- Search by Year -->
                <div class="bg-white dark:bg-gray-700 shadow-xl rounded-2xl p-6">
                    <form id="myForm" action="{{ route('admin.search.byyear') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">📈 Search By Year</h2>

                        <div class="mb-6">
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                            <select name="year"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400">
                                <option>Select Year</option>
                                @foreach (range(2022, 2026) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                            🔍 Search
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection