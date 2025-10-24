<!DOCTYPE html>
<html lang="km" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- ត្រូវបញ្ចូល jQuery មុន Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SweetAlert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- ... aqi code khang leuk del mean srab ... --}}

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    @vite('resources/css/app.css')
    <link href="{{ asset('backend/assets/css/profile.css') }}" rel="stylesheet" />

    <title>Dashboard</title>

    {{-- Load theme on page load --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

  
    {{-- Alpine.js --}}
    <script defer src="{{ asset('backend/assets/js/cdn.min.js') }}"></script>
</head>

<body class="flex flex-col h-screen  bg-gray-100 dark:bg-gray-900 overflow-hidden ">

    {{-- Topbar (Header) --}}
    @include('admin.body.header')

    <div class="flex flex-1 overflow-hidden">

        {{-- Sidebar Navigation --}}
        <div class="static overflow-y-auto">
            @include('admin.body.sidebar')
        </div>
            

        {{-- Main Content Wrapper --}}
        {{-- 
            សំខាន់៖ ត្រូវរុំ @yield នៅក្នុង <main> ដែលមាន overflow-y-auto
        --}}
        <main class="flex-1 overflow-y-auto ">
            @yield('admin')
        </main>

    </div>


    {{-- Footer --}}
    {{-- @include('admin.body.footer') --}}
{{-- Scripts --}}
    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const themeToggleInput = document.getElementById('theme-toggle');
        const themeToggleLabel = document.querySelector('.toggle-switch-label');

        // Change theme by adding/removing .dark class on <html>
        function setTheme(isDarkMode) {
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                themeToggleInput.checked = true;
                themeToggleLabel?.setAttribute('aria-checked', 'true');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                themeToggleInput.checked = false;
                themeToggleLabel?.setAttribute('aria-checked', 'false');
                localStorage.setItem('theme', 'light');
            }
        }

        // On page load, set theme according to saved preference
        const savedTheme = localStorage.getItem('theme');
        setTheme(savedTheme === 'dark');

        // Event listener for toggle input
        themeToggleInput?.addEventListener('change', () => {
            setTheme(themeToggleInput.checked);
            createPieChart();
            createBarChart();
        });

        // Toggle sidebar menu
        menuButton?.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });

        // Optional: keyboard access on label
        themeToggleLabel?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                themeToggleInput.checked = !themeToggleInput.checked;
                setTheme(themeToggleInput.checked);
            }
        });

        // Your existing Chart.js init code (make sure charts update colors based on dark mode)
        // ... (keep your chart code as is, just ensure it reads dark mode from document.documentElement.classList)

        // Example Chart.js update colors check:
        function isDark() {
            return document.documentElement.classList.contains('dark');
        }
    </script>

    {{-- SweetAlert2 --}}
    <script src="{{ asset('backend/assets/js/sweetalert2.all.min.js') }}"></script>
    {{-- Custom code.js --}}
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    {{-- Validation --}}
    <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>
    {{-- Toastr --}}
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script>
        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    </script>
    


     <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarNav = document.getElementById('sidebar-nav');
            if (!sidebarNav) return;

            const dropdownGroups = sidebarNav.querySelectorAll('.relative.group');

            dropdownGroups.forEach(group => {
                const dropdownMenu = group.querySelector('.absolute');
                if (!dropdownMenu) return;

                group.addEventListener('mouseenter', () => {
                    const rect = group.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;

                    // If not enough space below, pop up
                    if (spaceBelow < dropdownMenu.offsetHeight) {
                        dropdownMenu.classList.remove('top-0');
                        dropdownMenu.classList.add('bottom-0');
                    }
                });

                group.addEventListener('mouseleave', () => {
                    // Reset to default state
                    dropdownMenu.classList.remove('bottom-0');
                    dropdownMenu.classList.add('top-0');
                });
            });
        });
    </script>
</body>

</html>