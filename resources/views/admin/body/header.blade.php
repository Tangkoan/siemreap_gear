<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggleBtn = document.getElementById("theme-toggle");
        const thumb = document.getElementById("toggle-thumb");
        const icon = document.getElementById("toggle-icon");

        function setSunIcon() {
            icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                class="text-yellow-400" fill="currentColor"
                d="M12 4V2m0 20v-2m8-8h2M2 12h2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414M17.95 17.95l-1.414-1.414M6.05 6.05L4.636 7.464M12 8a4 4 0 100 8 4 4 0 000-8z" />`;
            icon.classList.add('text-yellow-400');
        }

        function setMoonIcon() {
            icon.classList.remove('text-yellow-400');
            icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 009.79 9.79z" />`;
        }

        function updateUI(isDark) {
            // Move thumb
            thumb.classList.toggle("translate-x-8", isDark);
            thumb.classList.toggle("translate-x-1", !isDark);

            // Fade icon
            icon.classList.add("scale-75", "opacity-0");
            setTimeout(() => {
                icon.innerHTML = '';
                isDark ? setMoonIcon() : setSunIcon();
                icon.classList.remove("scale-75", "opacity-0");
                icon.classList.add("scale-100", "opacity-100");
            }, 200);
        }

        // Initial load
        const isDarkInit = localStorage.getItem("theme") === "dark";
        document.documentElement.classList.toggle("dark", isDarkInit);
        updateUI(isDarkInit);

        toggleBtn.addEventListener("click", () => {
            const isDark = document.documentElement.classList.toggle("dark");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateUI(isDark);
        });

        // Stock Alert Notification Logic
        const messageBtn = document.getElementById('messageBtn');
        const dropdown = document.getElementById('messageDropdown');
        const notificationCountSpan = document.getElementById('notificationCount');
        const notificationContentDiv = document.getElementById('notificationContent');

        // Function to fetch and display stock alerts
        async function fetchStockAlerts() {
            try {
                const response = await fetch("{{ route('stock.alerts') }}");
                const data = await response.json();

                if (data.status === 'success') {
                    notificationCountSpan.textContent = data.count;
                    notificationContentDiv.innerHTML = ''; // Clear previous notifications

                    if (data.count > 0) {
                        data.products.forEach(product => {
                            const alertItem = document.createElement('div');
                            alertItem.classList.add(
                                'flex', 'items-start', 'px-4', 'py-3', 'border-b', 'border-gray-200', 'dark:border-gray-700',
                                'hover:bg-gray-100', 'dark:hover:bg-gray-700', 'cursor-pointer'
                            );

                            alertItem.innerHTML = `
                                <div class="flex-shrink-0 mt-1 icon-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>

                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 mb-1">
                                        Product Name: <span class="text-indigo-600 dark:text-indigo-400">${product.product_name || 'N/A'}</span> </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        In Stock: <strong>${product.product_store}</strong> (Alert Threshold: ${product.stock_alert})
                                    </p>
                                    
                                </div>
                            `;
                            notificationContentDiv.appendChild(alertItem);
                        });
                    } else {
                        const noAlertsMessage = document.createElement('p');
                        noAlertsMessage.classList.add('px-4', 'py-2', 'text-sm', 'text-gray-700', 'dark:text-gray-200', 'text-center', 'italic');
                        noAlertsMessage.textContent = "No stock alerts at the moment. All good!";
                        notificationContentDiv.appendChild(noAlertsMessage);
                    }
                }
            } catch (error) {
                console.error('Error fetching stock alerts:', error);
                notificationContentDiv.innerHTML = '<p class="px-4 py-2 text-sm text-red-500 text-center">Failed to load notifications. Please try again later.</p>';
            }
        }

        // Fetch alerts when the page loads
        fetchStockAlerts();

        // Optionally, fetch alerts periodically (e.g., every 60 seconds)
        // setInterval(fetchStockAlerts, 60000);

        messageBtn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
            fetchStockAlerts();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!messageBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>

<header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
    <div class="container mx-auto flex justify-between items-center p-3">

        {{-- Left Side: Logo & Sidebar Toggle --}}
        <div class="flex items-center space-x-2">
            
            {{-- FIXED: Logo is now clearly visible with a solid color --}}
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    Siem Reap Gear
                </span>
            </a>

            <button id="toggleSidebar" class="hidden md:flex items-center p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </button>
        </div>

        {{-- Right Side: Actions --}}
        <div class="flex items-center space-x-2 sm:space-x-4">
            
            {{-- FIXED: POS Button is now a strong, solid blue for high visibility --}}
            @can('pos.menu')
                <a href="{{ route('pos') }}" class="hidden sm:block bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('messages.pos') }}
                </a>
            @else
                <button class="hidden sm:block bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-semibold py-2 px-4 rounded-lg shadow-md cursor-not-allowed" disabled title="You don't have permission to access POS">
                    {{ __('messages.pos') }}
                </button>
            @endcan

            {{-- Theme Toggle --}}
                    <button id="theme-toggle" class="w-16 h-8 rounded-full bg-slate-200 dark:bg-slate-700 relative flex items-center transition-colors duration-500">
                        <span id="toggle-thumb" class="absolute left-1 w-6 h-6 bg-white dark:bg-slate-800 rounded-full shadow-md transform transition-transform duration-300 flex items-center justify-center">
                            <svg id="toggle-icon" class="w-4 h-4 text-slate-700 dark:text-white transition-all duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"></svg>
                        </span>
                    </button>
            {{-- End Theme Toggle --}}
            

            {{-- Language Switcher --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center justify-center p-2 rounded-full text-slate-600  dark:text-slate-300  transition-colors focus:outline-none">
                        
                        {{-- បន្ថែមលក្ខខណ្ឌ @if ដើម្បីបង្ហាញទង់ជាតិតាមភាសាដែលកំពុងប្រើ --}}
                        @if(app()->getLocale() == 'km')
                            <img src="https://flagcdn.com/w20/kh.png" srcset="https://flagcdn.com/w40/kh.png 2x" width="30" alt="Khmer">
                        @else
                            <img src="https://flagcdn.com/w20/gb.png" srcset="https://flagcdn.com/w40/gb.png 2x" width="30" alt="English">
                        @endif

                        <span class="ml-2 text-xs font-semibold">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>

                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 rounded-xl bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl py-2 z-50 origin-top-right ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                            <img src="https://flagcdn.com/w20/gb.png" srcset="https://flagcdn.com/w40/gb.png 2x" width="20" alt="English">
                            <span>English</span>
                        </a>
                        <a href="{{ route('language.switch', 'km') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                            <img src="https://flagcdn.com/w20/kh.png" srcset="https://flagcdn.com/w40/kh.png 2x" width="20" alt="Khmer">
                            <span>ភាសាខ្មែរ</span>
                        </a>
                    </div>
                </div>
            {{-- End Language --}}


            {{-- Stock Alert Notification --}}
                    <div class="relative">
                        <button id="messageBtn" type="button" class="relative p-2 rounded-full text-slate-600 bg-slate-200/60 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/60 dark:bg-slate-700/60 transition-colors focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span id="notificationCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/3 -translate-y-1/3">0</span>
                        </button>
                        <div id="messageDropdown" class="hidden absolute right-0 z-50 mt-2 w-72 origin-top-right rounded-lg bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl ring-1 ring-black ring-opacity-5">
                            <div class="py-1" id="notificationContent">
                                <div class="flex items-start px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                                    <div class="flex-shrink-0 mt-1 text-amber-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Loading alerts...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            {{-- End Stock Alert --}}


            {{-- User Profile --}}
                    @php
                        $id = Auth::user()->id;
                        $adminData = App\Models\User::find($id);
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="group flex items-center space-x-2 focus:outline-none">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-transparent group-hover:border-blue-500 transition-colors"
                                src="{{ !empty($adminData->photo) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}" alt="User Profile">
                            <div class="font-medium text-left hidden lg:block">
                                <div class="text-sm text-slate-800 dark:text-slate-200 transition-colors">{{ $adminData->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Administrator</div>
                            </div>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 rounded-xl bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl py-2 z-50 origin-top-right ring-1 ring-black ring-opacity-5">
                            <div class="px-4 py-2">
                                <p class="text-sm text-slate-700 dark:text-slate-200">{{ __('messages.signed_in_as') }}</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $adminData->name }}</p>
                            </div>
                            <hr class="border-slate-200 dark:border-slate-700">
                            <div class="py-1">
                                <a href="{{ route('admin.admin_profile_view') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    <span>{{ __('messages.my_account') }}</span>
                                </a>
                                <a href="{{ route('change.password') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                    <span>{{ __('messages.change_password') }}</span>
                                </a>
                            </div>
                            <hr class="border-slate-200 dark:border-slate-700">
                            <div class="py-1">
                                <a href="{{ route('admin.logout') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-500 hover:bg-red-500/10 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" /></svg>
                                    <span>{{ __('messages.logout') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
            {{-- End My Profile --}}


            {{-- Mobile Menu Button (Hamburger) --}}
                    <button id="menu-button" class="md:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
            {{-- End Mobile Menu Button --}}


        </div>
    </div>
</header>