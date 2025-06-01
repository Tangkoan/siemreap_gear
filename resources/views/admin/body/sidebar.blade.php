<nav id="sidebar" class="sidebar-bg text-white w-64  p-4 space-y-2 md:block hidden transition-all duration-300 ease-in-out shadow-lg md:shadow-none">
    <div id="nav-links" class="space-y-2">
        <a href="{{ route('dashboard') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
            <span>Dashboard</span>
        </a>



        {{-- Product --}}
        <a href="{{ route('product.all') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm2 4a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1h-1zM8 12a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1h-1zM8 18a1 1 0 00-1 1v.5a1 1 0 001 1h4a1 1 0 001-1V19a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>
            <span>Products</span>
        </a>
        {{-- End Product --}}

        {{-- Employee --}}
        <a href="{{ route('employee.all') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm2 4a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1h-1zM8 12a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1h-1zM8 18a1 1 0 00-1 1v.5a1 1 0 001 1h4a1 1 0 001-1V19a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>
            <span class="text-nav">Employee</span>
        </a>
        {{-- End Employee --}}

        {{-- Employee --}}
    <a href="{{ route('customer.all') }}" class="nav-link bg-white text-black flex items-center py-2 px-4 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm2 4a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1V7a1 1 0 00-1-1h-1zM8 12a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1H8zm5 0a1 1 0 00-1 1v2a1 1 0 001 1h1a1 1 0 001-1v-2a1 1 0 00-1-1h-1zM8 18a1 1 0 00-1 1v.5a1 1 0 001 1h4a1 1 0 001-1V19a1 1 0 00-1-1H8z" clip-rule="evenodd"></path></svg>
            <span>Customer</span>
        </a>
        {{-- End Employee --}}


        








        {{-- <a href="" class="nav-link flex items-center py-2 px-4 rounded-lg hover:bg-indigo-600 transition-colors duration-200">
            <svg class="icon mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.5 17a4.5 4.5 0 01-4.5-4.5V9.5a4.5 4.5 0 014.5-4.5h9a4.5 4.5 0 014.5 4.5v3A4.5 4.5 0 0118.5 17h-13zM12 11a1 1 0 100-2 1 1 0 000 2z"></path>
            </svg>
            <span>Categories</span>
        </a> --}}
    </div>
</nav>