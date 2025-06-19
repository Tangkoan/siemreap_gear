<nav id="sidebar"
    class="sidebar-bg text-white w-64  p-4 space-y-2 md:block hidden transition-all duration-300 ease-in-out shadow-lg md:shadow-none">
    <div id="nav-links" class="space-y-2">
        <a href="{{ route('dashboard') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
            </svg>

            <div class="px-2">Dashboard</div>
        </a>

        {{-- Category --}}
        <a href="{{ route('all.category') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
            </svg>

            <div class="px-2">Category</div>

        </a>
        {{-- End Category --}}


        

        {{-- Product --}}
        <a href="{{ route('all.product') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <div class="px-2">Product</div>

        </a>
        {{-- End Product --}}

        {{-- Employee --}}
        {{-- <a href="{{ route('employee.all') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <span class="px-2">Employee</span>
                    </a> --}}
        {{-- End Employee --}}

        {{-- Employee --}}
        <a href="{{ route('customer.all') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <div class="px-2">Customer</div>
        </a>
        {{-- End Employee --}}

        {{-- Supplier --}}
        <a href="{{ route('all.supplier') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            <div class="px-2">Supplier</div>
        </a>
        {{-- End Supplier --}}

        {{-- Stock --}}
        <a href="{{ route('all.stock') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
            </svg>
              
            <div class="px-2">Stock</div>
        </a>
        {{-- End Stock --}}

        {{-- Purchase --}}
        <a href="{{ route('all.purchase') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
        
        
            <div class="px-2">Purchase</div>
        
        </a>
        {{-- End Purchase --}}



        <!-- Expense Dropdown -->
        <div id="expenseDropdown" class="relative group">
            <!-- Main Button -->
            <a href="{{ route('add.expense') }}"
                class="nav-link flex items-center py-2 px-4 rounded-lg w-full transition-colors duration-200
                      {{ request()->routeIs('add.expense', 'today.expense', 'month.expense', 'year.expense')
    ? 'bg-red-500 text-white'
    : 'bg-white text-black hover:bg-red-500 hover:text-white' }}">
                <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                </svg>
                <div class="px-2">Expense</div>
            </a>

            <!-- Dropdown Below -->
            <div id="dropdownMenu"
                class="absolute top-0 left-full ml-2 opacity-0 invisible group-hover:visible group-hover:opacity-100 
                        transition-all duration-300 bg-white border border-gray-300 shadow-lg rounded-md z-10">
                <a href="{{ route('today.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Today</a>
                <a href="{{ route('month.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Month</a>
                <a href="{{ route('year.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Year</a>
            </div>
        </div>



        <!-- Oder Dropdown -->
        <div id="orderDropdown" class="relative group">
            <!-- Main Button -->
            <a href="{{ route('pending.order') }}"
                class="nav-link flex items-center py-2 px-4 rounded-lg w-full transition-colors duration-200
                                            {{ request()->routeIs('pending.order', 'complete.order')
    ? 'bg-red-500 text-white'
    : 'bg-white text-black hover:bg-red-500 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                </svg>
                  
                <div class="px-2">Order</div>
            </a>

            <!-- Dropdown Below -->
            <div id="dropdownMenu"
                class="absolute top-0 left-full ml-2 opacity-0 invisible group-hover:visible group-hover:opacity-100 
                                transition-all duration-300 bg-white border border-gray-300 shadow-lg rounded-md z-10">
                <a href="{{ route('pending.order') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Pending</a>
                
                <a href="{{ route('complete.order') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Complete</a>
            </div>
        </div>

        







    


    <!-- Permission Dropdown -->
    <div id="permissionDropdown" class="relative group ">
        <!-- Main Button -->
        <a href="{{ route('all.permission') }}"
            class="nav-link flex items-center py-2 px-4 rounded-lg transition-colors duration-200
            {{ request()->routeIs('all.permission', 'add.permission', 'all.roles', 'add.roles', 'edit.roles', 'edit.permission', 'add.roles.permission') ? 'bg-red-500 text-white' : 'bg-white text-black hover:bg-red-500 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
            </svg>
            <div class="px-2">Permission</div>
        </a>
    
        <!-- Dropdown on same row (right side) -->
        <div id="dropdownMenu" class="absolute top-0 left-full ml-2 opacity-0 invisible group-hover:visible group-hover:opacity-100 
                   transition-all duration-300 bg-white border border-gray-300 shadow-lg rounded-md z-10 min-w-max">
            <a href="{{ route('all.permission') }}"
                class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">All Permission</a>
            <a href="{{ route('all.roles') }}"
                class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">All Roles</a>

            <a href="{{ route('add.roles.permission') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Roles in Permission</a>


            <a href="{{ route('all.roles.permission') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">All Roles in Permission</a>
        </div>
    </div>



    <!-- User Dropdown -->
    <div id="userDropdown" class="relative group ">
        <!-- Main Button -->
        <a href="{{ route('all.admin') }}"
            class="nav-link flex items-center py-2 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.admin', ) ? 'bg-red-500 text-white' : 'bg-white text-black hover:bg-red-500 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                  
            <div class="px-2">User</div>
        </a>
    
        <!-- Dropdown on same row (right side) -->
        {{-- <div id="dropdownMenu" class="absolute top-0 left-full ml-2 opacity-0 invisible group-hover:visible group-hover:opacity-100 
                       transition-all duration-300 bg-white border border-gray-300 shadow-lg rounded-md z-10 min-w-max">
            <a href="{{ route('all.admin') }}"
                class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">All User</a>
            
        </div> --}}
    </div>












        <script>
            const dropdown = document.getElementById('expenseDropdown');
            const menu = document.getElementById('dropdownMenu');
            let hideTimeout;

            dropdown.addEventListener('mouseenter', () => {
                clearTimeout(hideTimeout);
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.add('opacity-100');
                    menu.classList.remove('opacity-0');
                }, 10); // short delay to ensure transition
            });

            dropdown.addEventListener('mouseleave', () => {
                menu.classList.remove('opacity-100');
                menu.classList.add('opacity-0');
                hideTimeout = setTimeout(() => {
                    menu.classList.add('hidden');
                }, 3000); // Delay 3 seconds before hiding
            });
        </script>

    </div>
</nav>
