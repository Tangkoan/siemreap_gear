
<!-- ✅ Fix: Early dark mode before CSS load -->
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
    });
</script>



<header class="dark:bg-gray-800 text-white p-2 shadow-sm duration-300 bg-white">
    <div class="container mx-auto flex justify-between items-center">

        <!-- បន្ថែម items-center នៅទីនេះ -->
        <div class="flex items-center">
            <div>
                <a href="{{ route('dashboard') }}" class="flex items-center px-2">
                    <b>
                        <div class="text-black text-2xl dark:text-white">Siem Reap Gear</div>
                    </b>
                </a>
            </div>
            <div>
                <!-- បើចង់អោយបង្ហាញគ្រប់ទំហំ -->
                <button id="toggleSidebar" class="hidden md:flex items-center  px-2 text-black dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            {{-- <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 dark:text-gray-300">🌙</span>
                <button id="theme-toggle"
                    class="relative inline-flex items-center h-6 w-11 rounded-full transition-colors duration-300 focus:outline-none bg-gray-300 dark:bg-gray-600 shadow-inner">
                    <span id="toggle-circle"
                        class="inline-block w-5 h-5 transform bg-white rounded-full shadow-md transition-transform duration-300 translate-x-1 dark:translate-x-5">
                    </span>
                </button>
                <span class="text-sm text-yellow-400">☀️</span>
            </div> --}}

            

        </div>





        <div class="flex items-center space-x-4">
            @can('pos.menu')
                <!-- បើមានសិទ្ធ -->
                <button
                    class="color-primary text-white font-semibold py-2 px-4 rounded-md shadow-md transition-colors duration-200 hidden md:block">
                    <a href="{{ route('pos') }}">POS</a>
                </button>
            @else
                <!-- បើអត់មានសិទ្ធ -->
                <button
                    class="bg-gray-400 text-white font-semibold py-2 px-4 rounded-md shadow-md cursor-not-allowed hidden md:block"
                    disabled title="You don't have permission to access POS">
                    POS
                </button>
            @endcan

            <div class="flex items-center">
                <button id="theme-toggle"
                    class="text-black dark:text-white w-16 h-8 rounded-full bg-gray-400 dark:bg-gray-700 relative flex items-center transition-colors duration-500">
                    <!-- Toggle circle -->
                    <span id="toggle-thumb"
                        class="absolute left-1 translate-x-1 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-md transform transition-transform duration-300 flex items-center justify-center">
                        <svg id="toggle-icon" class="w-4 h-4 text-gray-700 dark:text-white transition-all duration-300"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <!-- inserted by JS -->
                        </svg>
                    </span>

                </button>
            </div>

            {{-- កុំប៉ះពាល់ត្រង់នេះ --}}
            <div class="hidden">
                <input type="hidden" id="theme-toggle">
                <label for="theme-toggle" class="toggle-switch-label hidden" role="switch" aria-checked="false"
                    tabindex="0">

                </label>
            </div>
            {{-- កុំប៉ះពាល់ត្រង់នេះ --}}

            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-black dark:text-white ">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>

            




            @php
$id = Auth::user()->id;
$adminData = App\Models\User::find($id);
            @endphp


            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center space-x-2  focus:outline-none text-black dark:text-white ">
                    <img class="h-9 w-9 rounded-full border-2 border-white object-cover"
                        src="{{ !empty($adminData->photo) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}"
                        alt="User Profile">

                    <span class="font-medium ttext-xl hidden md:block text-black dark:text-white ">{{ $adminData->name }}</span>
                    <svg class="h-4 w-4 hidden md:block" fill="black" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="absolute right-0 mt-2 w-48 card-bg rounded-md shadow-lg py-1 z-50 origin-top-right">
                    <div class="px-4 py-2 text-sm font-semibold gap-2"> <span class="text-black text-xs dark:text-white">Welcome </span>
                        <span class="uppercase  text-black text-xs dark:text-white">{{ $adminData->name }}</span></div>

                    <hr class="border-gray-200 dark:border-gray-700 my-1">
                    <a href="{{ route('admin.admin_profile_view') }}"
                        class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-gray-200 dark:hover:bg-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>

                        <span>My Account</span>
                    </a>


                    <a href="{{ route('change.password') }}"
                        class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-gray-200 dark:hover:bg-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>

                        <span>Change Password</span>
                    </a>

                    <a href="{{ route('admin.logout') }}"
                        class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-gray-200 dark:hover:bg-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <span>Logout</span>
                    </a>

                </div>
            </div>



            <button id="menu-button" class="md:hidden focus:outline-none ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>




</header>
