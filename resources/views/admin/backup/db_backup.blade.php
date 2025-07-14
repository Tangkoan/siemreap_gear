@extends('admin/admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

{{-- ✅ Style ថ្មីសម្រាប់ UI ដែលបាន Inspire ពីគំរូរបស់អ្នក --}}
<style>
    .pill-nav-container {
        @apply inline-block bg-slate-100 dark:bg-slate-800 p-1.5 rounded-xl shadow-sm;
    }
    .pill-tab-button {
        @apply px-6 py-2.5 text-sm font-semibold rounded-lg transition-all duration-300 ease-in-out cursor-pointer;
    }
    .pill-tab-button.active {
        @apply bg-red-600 text-white shadow-lg shadow-red-500/20;
    }
    .pill-tab-button:not(.active) {
        @apply text-slate-600 dark:text-slate-300 hover:bg-slate-200/70 dark:hover:bg-slate-700/50;
    }
    .tab-panel {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Loading Skeleton */
    .skeleton-row td {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    .skeleton-item {
        @apply h-4 bg-slate-200 dark:bg-gray-700 rounded-md;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        50% { opacity: .5; }
    }
</style>

<div class="container mx-auto p-4 md:p-6">
    <div class="w-full bg-white dark:bg-gray-800/50 card-bg rounded-xl shadow-2xl shadow-slate-200/50 dark:shadow-black/20">
        
        <!-- ✅ Tab Navigation ថ្មី (Inspired by your image) -->
        <div class="p-6 flex flex-col items-center border-b border-slate-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-5">Backup Management</h2>
            <div class="pill-nav-container">
                <div class="flex items-center space-x-2">
                    <button data-target="#db-panel" class="pill-tab-button active">
                        Database
                    </button>
                    <button data-target="#project-panel" class="pill-tab-button">
                        Project
                    </button>
                </div>
            </div>
        </div>
        

        <!-- ✅ Tab Content Panels -->
        <div class="px-6 pb-6 pt-4">
            <!-- Panel 1: Database Backups -->
            <div id="db-panel" class="tab-panel">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200">បញ្ជី Database Backup</h3>
                    <a href="{{ route('admin.backup.now') }}" class="w-full md:w-auto icon-add py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Backup Database Now
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <input class="dark:bg-gray-700 dark:text-white bg-white w-full max-w-xs mb-3 h-10 pl-3 pr-10 py-2 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search Database Backups..." id="db_search" type="text" />
                    <div class="table-wrapper overflow-y-auto max-h-[400px] border border-slate-200 dark:border-gray-700 rounded-lg">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead class="bg-slate-50 dark:bg-gray-700/50"><tr><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">N<sup>0</sup></th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">File Name</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Size</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Path</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Action</th></tr></thead>
                            <tbody id="db_backup_tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Project Backups -->
            <div id="project-panel" class="tab-panel" style="display: none;">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-200">បញ្ជី Project File Backup</h3>
                    <a href="{{ route('admin.backup.project') }}" class="w-full md:w-auto icon-add py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all">
                        Backup Project Files Now
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <input class="dark:bg-gray-700 dark:text-white bg-white w-full max-w-xs mb-3 h-10 pl-3 pr-10 py-2 placeholder:text-slate-400 text-slate-700 text-sm border border-slate-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search Project Backups..." id="project_search" type="text" />
                    <div class="table-wrapper overflow-y-auto max-h-[400px] border border-slate-200 dark:border-gray-700 rounded-lg">
                        <table class="w-full text-left table-auto min-w-max">
                            <thead class="bg-slate-50 dark:bg-gray-700/50"><tr><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">N<sup>0</sup></th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">File Name</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Size</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Path</th><th class="p-4 sticky top-0 bg-slate-50 dark:bg-gray-700/50 z-10 border-b border-slate-200 dark:border-gray-700 text-sm font-semibold text-slate-500 dark:text-gray-300">Action</th></tr></thead>
                                <tbody id="project_backup_tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts for Backup Status Polling --}}
@if (session('start_backup_check'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.info('Database backup process has started...');
            let interval = setInterval(() => fetch("{{ route('backup.status') }}").then(res => res.json()).then(data => {
                if (data.status === 'completed') {
                    clearInterval(interval);
                    toastr.success(data.message);
                    setTimeout(() => window.location.reload(), 2000);
                }
            }).catch(err => { console.error(err); clearInterval(interval); }), 5000);
        });
    </script>
@endif
@if (session('start_project_backup_check'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.info('Project backup started. This may take a moment...');
            let interval = setInterval(() => fetch("{{ route('project.backup.status') }}").then(res => res.json()).then(data => {
                if (data.status === 'completed') {
                    clearInterval(interval);
                    toastr.success(data.message);
                    setTimeout(() => window.location.reload(), 2000);
                }
            }).catch(err => { console.error(err); clearInterval(interval); }), 10000);
        });
    </script>
@endif

{{-- Scripts for AJAX Tables & Tab Switching --}}
<script type="text/javascript">
    $(document).ready(function() {
        
        const skeletonRow = `
            <tr class="skeleton-row">
                <td><div class="skeleton-item w-8"></div></td>
                <td><div class="skeleton-item w-48"></div></td>
                <td><div class="skeleton-item w-20"></div></td>
                <td><div class="skeleton-item w-64"></div></td>
                <td><div class="skeleton-item w-24"></div></td>
            </tr>`.repeat(5);

        // --- ✅ Tab Switching Logic ថ្មី ---
        $('.pill-tab-button').on('click', function() {
            const $this = $(this);
            const targetPanel = $this.data('target');

            $('.pill-tab-button').removeClass('active');
            $this.addClass('active');
            
            $('.tab-panel').hide();
            $(targetPanel).show();
        });

        // --- Function for Database Backup Table ---
        function fetchDbData() {
            $('#db_backup_tbody').html(skeletonRow); // Show skeleton
            $.ajax({
                url: "{{ route('backup.search') }}",
                type: "GET",
                data: { search: $('#db_search').val() },
                success: function(data) { $('#db_backup_tbody').html(data.table); },
                error: function() { $('#db_backup_tbody').html('<tr><td colspan="5" class="text-center p-5 text-red-500">Failed to load data.</td></tr>'); }
            });
        }

        // --- Function for Project Backup Table ---
        function fetchProjectData() {
            $('#project_backup_tbody').html(skeletonRow); // Show skeleton
            $.ajax({
                url: "{{ route('backup.project.search') }}",
                type: "GET",
                data: { search: $('#project_search').val() },
                success: function(data) { $('#project_backup_tbody').html(data.table); },
                error: function() { $('#project_backup_tbody').html('<tr><td colspan="5" class="text-center p-5 text-red-500">Failed to load data.</td></tr>'); }
            });
        }

        // Initial data load
        fetchDbData();
        fetchProjectData();

        // Search event listeners (with debounce)
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
