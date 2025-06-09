<nav id="sidebar" class="sidebar-bg text-white w-64  p-4 space-y-2 md:block hidden transition-all duration-300 ease-in-out shadow-lg md:shadow-none">
    <div id="nav-links" class="space-y-2">
        <a href="{{ route('dashboard') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
            </svg>
                          
            <div class="px-2">Dashboard</div>
        </a>


        {{-- Brand --}}
        <a href="{{ route('all.brand') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linejoin="round"
                    d="M6.75 3.744h-.753v8.25h7.125a4.125 4.125 0 0 0 0-8.25H6.75Zm0 0v.38m0 16.122h6.747a4.5 4.5 0 0 0 0-9.001h-7.5v9h.753Zm0 0v-.37m0-15.751h6a3.75 3.75 0 1 1 0 7.5h-6m0-7.5v7.5m0 0v8.25m0-8.25h6.375a4.125 4.125 0 0 1 0 8.25H6.75m.747-15.38h4.875a3.375 3.375 0 0 1 0 6.75H7.497v-6.75Zm0 7.5h5.25a3.75 3.75 0 0 1 0 7.5h-5.25v-7.5Z" />
            </svg>
              
              <div class="px-2">Brand</div>
            
        </a>
        {{-- End Brand --}}

        {{-- Category --}}
        <a href="{{ route('all.category') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
            </svg>
              
              <div class="px-2">Category</div>
            
        </a>
        {{-- End Category --}}


        {{-- Unit --}}
        <a href="{{ route('all.unit') }}"
            class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        
            <div class="px-2">Unit</div>
        </a>
        {{-- End Unit --}}

        {{-- Product --}}
            <a href="{{ route('all.product') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
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
        <a href="{{ route('customer.all') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <div class="px-2">Customer</div>
        </a>
        {{-- End Employee --}}

         {{-- Supplier --}}
         <a href="{{ route('all.supplier') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            <div class="px-2">Supplier</div>
        </a>
        {{-- End Supplier --}}



        <!-- Expense Dropdown -->
        <div id="expenseDropdown" class="relative group">
            <!-- Main Button -->
            <a href="{{ route('add.expense') }}" class="nav-link flex items-center py-2 px-4 rounded-lg w-full transition-colors duration-200
                      {{ request()->routeIs('add.expense', 'today.expense', 'month.expense', 'year.expense')
                        ? 'bg-red-500 text-white'
                        : 'bg-white text-black hover:bg-red-500 hover:text-white' }}">
                        <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                        </svg>
                <div class="px-2">Expense</div>
            </a>
        
            <!-- Dropdown Below -->
            <div id="dropdownMenu" class="absolute left-0 w-full mt-1 opacity-0 invisible group-hover:visible group-hover:opacity-100 
                        transition-all duration-300 bg-white border border-gray-300 shadow-lg rounded-md z-10">
                <a href="{{ route('today.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Today</a>
                <a href="{{ route('month.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Month</a>
                <a href="{{ route('year.expense') }}"
                    class="block px-4 py-2 text-gray-700 hover:bg-red-500 hover:text-white transition">Year</a>
            </div>
        </div>




        








        {{-- <a href="" class="nav-link flex items-center py-2 px-4 rounded-lg hover:bg-indigo-600 transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.5 17a4.5 4.5 0 01-4.5-4.5V9.5a4.5 4.5 0 014.5-4.5h9a4.5 4.5 0 014.5 4.5v3A4.5 4.5 0 0118.5 17h-13zM12 11a1 1 0 100-2 1 1 0 000 2z"></path>
            </svg>
            <span>Categories</span>
        </a> --}}








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