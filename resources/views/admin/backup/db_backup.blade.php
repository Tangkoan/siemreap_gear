@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- ✅ Modern UI Styles for 2025 --}}
    <style>
        /* Main card with glassmorphism effect for dark mode */
        .card-ui-2025 {
            @apply card-dynamic-bg backdrop-blur-xl border border-primary shadow-2xl shadow-slate-200/40 dark:shadow-black/20;
        }

        /* Pill-style tab buttons */
        .tab-button-pill {
            @apply px-4 py-2 text-sm rounded-lg transition-colors duration-200 ease-in-out;
        }

        /* Active state for pill tabs */
        .tab-button-pill.active-tab {
            @apply bg-primary text-defalut shadow-md;
        }

        .tab-button-pill:not(.active-tab) {
            @apply text-defalut;
        }

        /* Animation for tab content panels */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-panel {
            animation: fadeIn 0.4s ease-in-out;
        }


        .tbody tr:hover {
            background-color: #cacaca61;
        }

        /* សម្រាប់ Dark Mode (បើអ្នកមាន) */
        .dark .tbody tr:hover {
            background-color: #6d6d6d61;
            /* នេះជាពណ៌ gray-800 របស់ Tailwind */
        }
    </style>

    <div class="container mx-auto p-4 md:p-6">
        <div class="w-full card-ui-2025 rounded-2xl">

            <div class="px-6 py-5 border-b border-primary  flex flex-col md:flex-row justify-between items-center gap-4">


                <h2 class="text-xl  text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <div class="px-2 text-3xl font-bold text-defalut"> {{ __(key: 'messages.backup_management') }}</div>

                </h2>

                <div id="tab-container" class="inline-flex card-dynamic-bg p-1.5 rounded-xl shadow-sm">
                    <button data-target="#db-panel" data-active-classes="bg-red-600 text-white shadow-lg"
                        data-inactive-classes="text-slate-600 dark:text-slate-300" id="db-tab"
                        class="tab-button px-5 py-2.5 rounded-lg  text-sm transition-all" type="button">
                        {{ __(key: 'messages.database') }}
                    </button>
                    <button data-target="#project-panel" data-active-classes="bg-red-600 text-white shadow-lg"
                        data-inactive-classes="text-slate-600 dark:text-slate-300" id="project-tab"
                        class="tab-button px-5 py-2.5 rounded-lg  text-sm transition-all" type="button">
                        {{ __(key: 'messages.project') }}
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div id="db-panel" class="tab-panel">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <div class="relative w-full md:w-auto">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-defalut" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="db_search"
                                class="block w-full md:w-80 p-2.5 pl-10 text-sm text-defalut border border-primary rounded-lg card-dynamic-bg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="{{ __(key: 'messages.search') }}">
                        </div>
                        <a href="{{ route('admin.backup.now') }}"
                            class="w-full md:w-auto text-center py-2.5 px-5 text-sm font-medium bg-primary text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 transition shadow-md hover:shadow-lg">
                            {{ __(key: 'messages.backup_database_now') }}
                        </a>
                    </div>

                    <div class="border-none card-dynamic-bg rounded-xl overflow-hidden">
                        <div class="overflow-y-auto max-h-[450px]">
                            <table class="w-full text-sm text-left text-defalut">
                                <thead class="text-xs card-dynamic-bg text-defalut uppercase  sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.no') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.file_name') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.size') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.path') }}</th>
                                        <th scope="col" class="px-6 py-3 text-center">{{ __(key: 'messages.action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="db_backup_tbody" class="tbody divide-y divide-primary">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="project-panel" class="tab-panel" style="display: none;">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <div class="relative w-full md:w-auto">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-defalut" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="project_search"
                                class="block w-full md:w-80 p-2.5 pl-10 text-sm text-defalut border border-primary rounded-lg card-dynamic-bg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                placeholder="{{ __(key: 'messages.search') }}">
                        </div>
                        <a href="{{ route('admin.backup.project') }}"
                            class="w-full md:w-auto text-center py-2.5 px-5 text-sm font-medium text-white rounded-md bg-primary focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 transition shadow-md hover:shadow-lg">
                            {{ __(key: 'messages.backup_project_file_now') }}
                        </a>
                    </div>

                    <div class="border-none border-primary card-dynamic-bg rounded-xl overflow-hidden">
                        <div class="overflow-y-auto max-h-[450px]">
                            <table class="w-full text-sm text-left text-defalut">
                                <thead class="text-xs text-defalut uppercase card-dynamic-bg sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.no') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.file_name') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.size') }}</th>
                                        <th scope="col" class="px-6 py-3">{{ __(key: 'messages.path') }}</th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            {{ __(key: 'messages.table_action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="project_backup_tbody" class="tbody divide-y divide-primary">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ⛔️ NO CHANGES WERE MADE TO YOUR LOGIC/SCRIPTS BELOW ⛔️ --}}

    {{-- Scripts for Backup Status Polling --}}
    @if (session('start_backup_check'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.info('Database backup process has started...');
                let interval = setInterval(() => fetch("{{ route('backup.status') }}").then(res => res.json()).then(
                    data => {
                        if (data.status === 'completed') {
                            clearInterval(interval);
                            toastr.success(data.message);
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    }).catch(err => {
                    console.error(err);
                    clearInterval(interval);
                }), 5000);
            });
        </script>
    @endif
    @if (session('start_project_backup_check'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.info('Project backup started. This may take a moment...');
                let interval = setInterval(() => fetch("{{ route('project.backup.status') }}").then(res => res.json())
                    .then(data => {
                        if (data.status === 'completed') {
                            clearInterval(interval);
                            toastr.success(data.message);
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    }).catch(err => {
                        console.error(err);
                        clearInterval(interval);
                    }), 10000);
            });
        </script>
    @endif

    {{-- Scripts for AJAX Tables & Tab Switching --}}
    <script type="text/javascript">
        $(document).ready(function() {


            // --- ✅ NEW & IMPROVED Tab Switching Logic ---
            $('.tab-button').on('click', function() {
                const clickedButton = $(this);
                const targetPanel = clickedButton.data('target');

                // 1. Deactivate ALL buttons first
                $('.tab-button').each(function() {
                    const button = $(this);
                    // Remove active classes and add inactive classes
                    button.removeClass(button.data('active-classes')).addClass(button.data(
                        'inactive-classes'));
                });

                // 2. Activate ONLY the clicked button
                // Remove inactive classes and add active classes
                clickedButton.removeClass(clickedButton.data('inactive-classes')).addClass(clickedButton
                    .data('active-classes'));

                // 3. Show the correct content panel
                $('.tab-panel').hide();
                $(targetPanel).show();
            });

            // --- Initial State on Page Load ---
            // Automatically click the first tab to make it active when the page loads
            $('.tab-button').first().trigger('click');


            // --- Tab Switching Logic ---
            $('#db-tab').on('click', function() {
                $('.tab-panel').hide();
                $('#db-panel').show();
                $('.tab-button-pill').removeClass('active-tab');
                $(this).addClass('active-tab');
            });

            $('#project-tab').on('click', function() {
                $('.tab-panel').hide();
                $('#project-panel').show();
                $('.tab-button-pill').removeClass('active-tab');
                $(this).addClass('active-tab');
            });

            // --- Function for Database Backup Table ---
            function fetchDbData() {
                $.ajax({
                    url: "{{ route('backup.search') }}",
                    type: "GET",
                    data: {
                        search: $('#db_search').val()
                    },
                    success: function(data) {
                        $('#db_backup_tbody').html(data.table);
                    },
                    error: function() {
                        $('#db_backup_tbody').html(
                            '<tr><td colspan="5" class="text-center p-5 text-red-500">Failed to load data.</td></tr>'
                            );
                    }
                });
            }

            // --- Function for Project Backup Table ---
            function fetchProjectData() {
                $.ajax({
                    url: "{{ route('backup.project.search') }}",
                    type: "GET",
                    data: {
                        search: $('#project_search').val()
                    },
                    success: function(data) {
                        $('#project_backup_tbody').html(data.table);
                    },
                    error: function() {
                        $('#project_backup_tbody').html(
                            '<tr><td colspan="5" class="text-center p-5 text-red-500">Failed to load data.</td></tr>'
                            );
                    }
                });
            }

            // Initial data load for both tables
            fetchDbData();
            fetchProjectData();

            // Search event listeners (with debounce for performance)
            let dbSearchTimeout;
            $('#db_search').on('keyup', function() {
                clearTimeout(dbSearchTimeout);
                dbSearchTimeout = setTimeout(fetchDbData, 500);
            });

            let projectSearchTimeout;
            $('#project_search').on('keyup', function() {
                clearTimeout(projectSearchTimeout);
                projectSearchTimeout = setTimeout(fetchProjectData, 500);
            });
        });
    </script>
@endsection
