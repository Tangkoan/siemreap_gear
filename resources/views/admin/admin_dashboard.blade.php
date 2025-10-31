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

    @if(isset($shopInfo) && $shopInfo->favicon)
        {{-- បើមាន Favicon ក្នុង Database, វានឹងបង្ហាញនៅទីនេះ ជាមួយ Cache Busting --}}
        <link rel="icon" href="{{ url('upload/shop_info/' . $shopInfo->favicon) }}?v={{ $shopInfo->updated_at->timestamp }}">
    @else
        {{-- បើមិនមាន, វានឹងប្រើ Favicon ដើមរបស់អ្នក (Fallback) --}}
        <link rel="icon" href="{{ asset('image/favicon.ico') }}" type="image/x-icon">
    @endif
    
    {{-- ✅ START: DYNAMIC STYLES (កូដថ្មីដែលមាន Card Logic) --}}
    @auth
        @php
            // ✅ Function សម្រាប់បំប្លែង HEX ទៅ RGBA (សម្រាប់ Opacity)
            function hexToRgba($hex, $alpha) {
                $hex = str_replace('#', '', $hex);
                if(strlen($hex) == 3) {
                   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
                } else {
                   $r = hexdec(substr($hex,0,2));
                   $g = hexdec(substr($hex,2,2));
                   $b = hexdec(substr($hex,4,2));
                }
                $alpha = $alpha / 100;
                return "rgba($r, $g, $b, $alpha)";
            }

            // កំណត់តម្លៃ Default (រួមទាំង Card)
            $defaults = [
                'light_primary_color' => '#4F46E5', 'light_text_color' => '#1F2937',
                'light_bg_type' => 'default', 'light_bg_color' => '#F3F4F6', 'light_bg_image' => null,
                'dark_primary_color' => '#6366F1', 'dark_text_color' => '#F9FAFB',
                'dark_bg_type' => 'default', 'dark_bg_color' => '#111827', 'dark_bg_image' => null,
                
                'light_card_type' => 'default', 'light_card_color1' => '#FFFFFF', 'light_card_opacity' => 80, 'light_card_color2' => '#F9FAFB', 'light_card_gradient_dir' => 'to right',
                'dark_card_type' => 'default', 'dark_card_color1' => '#1F2937', 'dark_card_opacity' => 80, 'dark_card_color2' => '#111827', 'dark_card_gradient_dir' => 'to right',
            
            ];
            
            $s = array_merge($defaults, Auth::user()->appearance_settings ?? []);


            // យក Settings មកបង្ហាញក្នុង Form (FIXED - បន្ថែម Input Defaults)
            $defaults = [
                'light_primary_color' => '#4F46E5', 'light_text_color' => '#1F2937', 'light_bg_type' => 'default', 'light_bg_color' => '#F3F4F6', 'light_bg_image' => null,
                'dark_primary_color' => '#6366F1', 'dark_text_color' => '#F9FAFB', 'dark_bg_type' => 'default', 'dark_bg_color' => '#111827', 'dark_bg_image' => null,
                
                'light_card_type' => 'default', 'light_card_color1' => '#FFFFFF', 'light_card_opacity' => 80, 'light_card_color2' => '#F9FAFB', 'light_card_gradient_dir' => 'to right',
                'dark_card_type' => 'default', 'dark_card_color1' => '#1F2937', 'dark_card_opacity' => 80, 'dark_card_color2' => '#111827', 'dark_card_gradient_dir' => 'to right',
                
                // ✅ START: បន្ថែម Default Values ដែលបាត់សម្រាប់ Input
                'light_input_color' => '#FFFFFF', 
                'light_input_opacity' => 80,
                'dark_input_color' => '#1F2937', 
                'dark_input_opacity' => 80,
                // ✅ END: បន្ថែម Default Values ដែលបាត់សម្រាប់ Input
            ];
            $s = array_merge($defaults, Auth::user()->appearance_settings ?? []);

            // --- Logic សម្រាប់ Background គោល (Main Background) ---
            $light_bg_final = $s['light_bg_type'] == 'color' ? $s['light_bg_color'] : $defaults['light_bg_color'];
            $dark_bg_final = $s['dark_bg_type'] == 'color' ? $s['dark_bg_color'] : $defaults['dark_bg_color'];
            $light_image_final = ($s['light_bg_type'] == 'image' && $s['light_bg_image']) ? 'url(' . asset($s['light_bg_image']) . ')' : 'none';
            $dark_image_final = ($s['dark_bg_type'] == 'image' && $s['dark_bg_image']) ? 'url(' . asset($s['dark_bg_image']) . ')' : 'none';

            // --- ✅ START: Logic ថ្មីសម្រាប់ Card Background ---
            // Light Card
            $light_card_bg_final = 'rgba(255, 255, 255, 0.8)'; // Default bg-white/80
            if ($s['light_card_type'] === 'solid') {
                $light_card_bg_final = hexToRgba($s['light_card_color1'], $s['light_card_opacity']);
            } elseif ($s['light_card_type'] === 'gradient') {
                $light_card_bg_final = "linear-gradient({$s['light_card_gradient_dir']}, {$s['light_card_color1']}, {$s['light_card_color2']})";
            }
            // Dark Card
            $dark_card_bg_final = 'rgba(30, 41, 59, 0.8)'; // Default bg-slate-800/80
            if ($s['dark_card_type'] === 'solid') {
                $dark_card_bg_final = hexToRgba($s['dark_card_color1'], $s['dark_card_opacity']);
            } elseif ($s['dark_card_type'] === 'gradient') {
                $dark_card_bg_final = "linear-gradient({$s['dark_card_gradient_dir']}, {$s['dark_card_color1']}, {$s['dark_card_color2']})";
            }
            // --- ✅ END: Logic ថ្មីសម្រាប់ Card Background ---

        @endphp

        {{-- នេះគឺជាកូដដែលបង្កើត CSS Variables (បាន Update) --}}
        <style id="dynamic-user-styles">
            :root {
                /* Main Background & Colors */
                --primary-light: {{ $s['light_primary_color'] }};
                --text-light: {{ $s['light_text_color'] }};
                --bg-light: {{ $light_bg_final }};
                --bg-image-light: {{ $light_image_final }};

                --primary-dark: {{ $s['dark_primary_color'] }};
                --text-dark: {{ $s['dark_text_color'] }};
                --bg-dark: {{ $dark_bg_final }};
                --bg-image-dark: {{ $dark_image_final }};

                /* ✅ Card Backgrounds */
                --card-bg-light: {!! $light_card_bg_final !!};
                --card-bg-dark: {!! $dark_card_bg_final !!};
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

            /* ✅ អនុវត្ត Card Background */
            .card-dynamic-bg {
                background-color: var(--card-bg-light); /* Fallback */
                background: var(--card-bg-light); /* For Gradients */
            }
            .dark .card-dynamic-bg {
                background-color: var(--card-bg-dark);
                background: var(--card-bg-dark);
            }

            /* បង្កើត Helper Classes សម្រាប់ប្រើប្រាស់ */
            .text-primary { color: var(--primary-light); }
            .dark .text-primary { color: var(--primary-dark); }
            .bg-primary { background-color: var(--primary-light); }
            .dark .bg-primary { background-color: var(--primary-dark); }
            .border-primary { border-color: var(--primary-light); }
            .dark .border-primary { border-color: var(--primary-dark); }
            .ring-primary { --tw-ring-color: var(--primary-light); }
            .dark .ring-primary { --tw-ring-color: var(--primary-dark); }
            .icon-primary {
                stroke: var(--primary-light);
                fill: var(--primary-light);
            }
            .dark .icon-primary {
                stroke: var(--primary-dark);
                fill: var(--primary-dark);
            }
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

    {{-- ... (Scripts ទាំងអស់នៅដដែល មិនចាំបាច់កែទេ) ... --}}
    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const themeToggleInput = document.getElementById('theme-toggle');
        const themeToggleLabel = document.querySelector('.toggle-switch-label');
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
        const savedTheme = localStorage.getItem('theme');
        setTheme(savedTheme === 'dark');
        themeToggleInput?.addEventListener('change', () => {
            setTheme(themeToggleInput.checked);
            if (typeof createPieChart === 'function') {
                createPieChart();
            }
            if (typeof createBarChart === 'function') {
                createBarChart();
            }
        });
        menuButton?.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
        themeToggleLabel?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                themeToggleInput.checked = !themeToggleInput.checked;
                setTheme(themeToggleInput.checked);
            }
        });
        function isDark() {
            return document.documentElement.classList.contains('dark');
        }
    </script>
    <script src="{{ asset('backend/assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>
    <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>
    <script>
        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info': toastr.info("{{ Session::get('message') }}"); break;
                case 'success': toastr.success("{{ Session::get('message') }}"); break;
                case 'warning': toastr.warning("{{ Session::get('message') }}"); break;
                case 'error': toastr.error("{{ Session::get('message') }}"); break;
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
                    if (spaceBelow < dropdownMenu.offsetHeight) {
                        dropdownMenu.classList.remove('top-0');
                        dropdownMenu.classList.add('bottom-0');
                    }
                });
                group.addEventListener('mouseleave', () => {
                    dropdownMenu.classList.remove('bottom-0');
                    dropdownMenu.classList.add('top-0');
                });
            });
        });
    </script>
</body>
</html>

