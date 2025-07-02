<!DOCTYPE html>
<html lang="km" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
    <link href="{{ asset('backend/assets/css/profile.css') }}" rel="stylesheet" />

    <script defer src="{{ asset('backend/assets/js/cdn.min.js') }}"></script>

</head>

<body class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

    @include('admin.body.header')

    <div class="flex flex-1 flex-col md:flex-row">
        @include('admin.body.sidebar')

        <main class="flex-1 p-6"> @yield('admin')
        </main>
    </div>

    {{-- @include('admin.body.footer') --}}


    <script src="{{ asset('backend/assets/js/chart.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>

    <script>
        // Toastr Messages
        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info': toastr.info(" {{ Session::get('message') }} "); break;
                case 'success': toastr.success(" {{ Session::get('message') }} "); break;
                case 'warning': toastr.warning(" {{ Session::get('message') }} "); break;
                case 'error': toastr.error(" {{ Session::get('message') }} "); break;
            }
        @endif

        // ✅ STEP 3: បង្រួម Theme Toggle Logic ទាំងអស់មកត្រឹមមួយកន្លែង
        document.addEventListener('DOMContentLoaded', () => {

            const themeToggleButton = document.getElementById('theme-toggle-button'); // ប្រើ ID ថ្មីសម្រាប់ Button
            const toggleThumb = document.getElementById('toggle-thumb');
            const toggleIcon = document.getElementById('toggle-icon');
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.getElementById('menu-button');
            const body = document.body;

            // --- Theme Toggling Logic ---
            const setSunIcon = () => {
                toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-8.66h1M3.34 12H2m15.36 6.36l.71.71M6.34 6.34l-.71-.71m12.02 0l.71-.71M6.34 17.66l-.71.71M12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>`;
            };

            const setMoonIcon = () => {
                toggleIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1 1 11.21 3c.09.26.19.52.3.77a9 9 0 0 0 9.49 9.02z"/>`;
            };

            const updateThemeUI = (isDark) => {
                // Animate Thumb
                toggleThumb.classList.toggle('translate-x-8', isDark);
                toggleThumb.classList.toggle('translate-x-1', !isDark);

                // Animate and change Icon
                toggleIcon.classList.add('scale-75', 'opacity-0');
                setTimeout(() => {
                    isDark ? setSunIcon() : setMoonIcon();
                    toggleIcon.classList.remove('scale-75', 'opacity-0');
                }, 150);

                // Update Charts if they exist
                if (typeof createPieChart === 'function') {
                    createPieChart();
                    createBarChart();
                }
            };

            // Initial UI State on page load
            const initialIsDark = document.documentElement.classList.contains('dark');
            updateThemeUI(initialIsDark);

            // Event Listener for the toggle button
            themeToggleButton.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateThemeUI(isDark);
            });


            // --- Other Logic ---

            // Mobile menu toggle
            if (menuButton && sidebar) {
                menuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
            }

            // Active Navigation Link
            // (Your existing activateNavLink logic can go here)

            // Chart.js Configuration
            // (Your existing chart creation logic can go here, just make sure they are defined as functions)
            // e.g., const createPieChart = () => { ... };
            // e.g., const createBarChart = () => { ... };

            // Initial chart creation
            if (typeof createPieChart === 'function') {
                createPieChart();
                createBarChart();
            }
        });

    </script>
</body>

</html>