<!DOCTYPE html>
<html lang="km" class=""> {{-- Remove 'dark' class from here, let script handle it --}}

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- ត្រូវបញ្ចូល jQuery មុន Select2 --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SweetAlert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset('backend/assets/css/toastr.min.css') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    @vite('resources/css/app.css')
    <link href="{{ asset('backend/assets/css/profile.css') }}" rel="stylesheet" />

    <title>Dashboard</title>
    
    {{-- ✅ START: DYNAMIC STYLES (កូដថ្មីពី Canvas) --}}
    {{-- ដាក់កូដនេះនៅទីនេះ ដើម្បីឲ្យវាអាចកំណត់ Background ពេល Load ទំព័រ --}}
    @auth
        @php
            // កំណត់តម្លៃ Default
            $defaults = [
                'light_primary_color' => '#4F46E5', // indigo-600
                'light_text_color'    => '#1F2937', // gray-800
                'light_bg_type'       => 'default',
                'light_bg_color'      => '#F3F4F6', // gray-100 (Default BG)
                'light_bg_image'      => null,

                'dark_primary_color'  => '#6366F1', // indigo-500
                'dark_text_color'     => '#F9FAFB', // gray-50
                'dark_bg_type'        => 'default',
                'dark_bg_color'       => '#111827', // gray-900 (Default BG)
                'dark_bg_image'       => null,
            ];
            
            // បញ្ចូលការកំណត់របស់ User ទៅលើ Default
            $s = array_merge($defaults, Auth::user()->appearance_settings ?? []);

            // កំណត់ Background ពិតប្រាកដដោយផ្អែកលើ Type
            $light_bg_final = $s['light_bg_type'] == 'color' ? $s['light_bg_color'] : $defaults['light_bg_color'];
            $dark_bg_final = $s['dark_bg_type'] == 'color' ? $s['dark_bg_color'] : $defaults['dark_bg_color'];
            
            // កំណត់ Background Image
            $light_image_final = ($s['light_bg_type'] == 'image' && $s['light_bg_image']) ? 'url(' . asset($s['light_bg_image']) . ')' : 'none';
            $dark_image_final = ($s['dark_bg_type'] == 'image' && $s['dark_bg_image']) ? 'url(' . asset($s['dark_bg_image']) . ')' : 'none';

        @endphp

        {{-- នេះគឺជាកូដដែលបង្កើត CSS Variables --}}
        <style id="dynamic-user-styles">
            :root {
                /* Light Mode Variables */
                --primary-light: {{ $s['light_primary_color'] }};
                --text-light: {{ $s['light_text_color'] }};
                --bg-light: {{ $light_bg_final }};
                --bg-image-light: {{ $light_image_final }};

                /* Dark Mode Variables */
                --primary-dark: {{ $s['dark_primary_color'] }};
                --text-dark: {{ $s['dark_text_color'] }};
                --bg-dark: {{ $dark_bg_final }};
                --bg-image-dark: {{ $dark_image_final }};
            }

            /* អនុវត្ត (Apply) Variables ទាំងនោះ */
            body {
                background-color: var(--bg-light);
                color: var(--text-light);
                background-image: var(--bg-image-light);
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }

            .dark body {
                background-color: var(--bg-dark);
                color: var(--text-dark);
                background-image: var(--bg-image-dark);
            }

            /* បង្កើត Helper Classes សម្រាប់ប្រើប្រាស់ */
            .text-primary { color: var(--primary-light); }
            .dark .text-primary { color: var(--primary-dark); }

            .bg-primary { background-color: var(--primary-light); }
            .dark .bg-primary { background-color: var(--primary-dark); }
            
            .border-primary { border-color: var(--primary-light); }
            .dark .border-primary { border-color: var(--primary-dark); }

            .ring-primary { 
                --tw-ring-color: var(--primary-light);
            }
            .dark .ring-primary {
                --tw-ring-color: var(--primary-dark);
            }
            
            /* សម្រាប់ Icons (SVG) */
            .icon-primary {
                stroke: var(--primary-light); /* សម្រាប់ stroke icons */
                fill: var(--primary-light);   /* សម្រាប់ fill icons */
            }
            .dark .icon-primary {
                stroke: var(--primary-dark);
                fill: var(--primary-dark);
            }

            /* ជួសជុលពណ៌ Text គោល (បើចាំបាច់) */
            .text-default { color: var(--text-light); }
            .dark .text-default { color: var(--text-dark); }
        </style>
    @endauth
    {{-- ✅ END: DYNAMIC STYLES --}}


    {{-- Load theme on page load --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

 
    {{-- Alpine.js --}}
    <script src="{{ asset('backend/assets/js/cdn.min.js') }}"></script>
</head>


{{-- ❌ START: លុបកូដ PHP ចាស់ចោល --}}
{{-- 
@php
    $bgStyle = '';
    // ... (កូដចាស់ទាំងអស់ត្រូវបានលុប) ...
@endphp
--}}
{{-- ❌ END: លុបកូដ PHP ចាស់ចោល --}}


{{-- ✅ នេះគឺជា BODY TAG ថ្មី ដែលពឹងផ្អែកលើ CSS Variables --}}
<body class="font-sans antialiased transition-colors duration-300">

    
    {{-- Topbar (Header) --}}
    @include('admin.body.header')

    <div class="flex flex-1 overflow-hidden">

        {{-- Sidebar Navigation --}}
        <div class="static overflow-y-auto">
            @include('admin.body.sidebar')
        </div>
            

        {{-- Main Content Wrapper --}}
        <main class="flex-1 overflow-y-auto ">
            @yield('admin')
        </main>

    </div>


    {{-- Footer --}}
    {{-- @include('admin.body.footer') --}}
{{-- Scripts --}}
    {{-- ... (Scripts ផ្សេងៗនៅដដែល) ... --}}
    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const themeToggleInput = document.getElementById('theme-toggle');
        const themeToggleLabel = document.querySelector('.toggle-switch-label');

        // Change theme by adding/removing .dark class on <html>
        function setTheme(isDarkMode) {
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                if(themeToggleInput) themeToggleInput.checked = true;
                themeToggleLabel?.setAttribute('aria-checked', 'true');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                if(themeToggleInput) themeToggleInput.checked = false;
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
            
            // Check if charts exist before trying to create them
            if (typeof createPieChart === 'function') {
                createPieChart();
            }
            if (typeof createBarChart === 'function') {
                createBarChart();
            }
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
