<nav id="sidebar"
    class="h-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg w-56 p-4 md:block hidden border-r border-slate-200 dark:border-slate-800">

    <div id="sidebar-nav" class="space-y-1"> {{-- <-- បន្ថែម id នៅទីនេះ --}}
    <div id="nav-links" class="space-y-1">

        {{-- Helper function for complex route checks --}}
        @php
            function isRouteActive($routes) {
                foreach ((array) $routes as $route) {
                    if (request()->routeIs($route)) return true;
                }
                return false;
            }
        @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
            {{ request()->routeIs('dashboard') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
            @if(request()->routeIs('dashboard'))
                <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
            </svg>
            <span class="px-2">Dashboard</span>
        </a>
        {{-- End Dashboard --}}

        {{-- Category --}}
        @if (Auth::user()->can('category.menu'))
            <a href="{{ route('all.category') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.category') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('all.category'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                </svg>
                <span class="px-2">Category</span>
            </a>
        @endif
        {{-- End Category --}}

        {{-- Product --}}
        @if (Auth::user()->can('product.menu'))
            <a href="{{ route('all.product') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.product') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('all.product'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                <span class="px-2">Product</span>
            </a>
        @endif
        {{-- End Product --}}

        {{-- Customer --}}
        @if (Auth::user()->can('customer.menu'))
             <a href="{{ route('customer.all') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('customer.all') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('customer.all'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="px-2">Customer</span>
            </a>
        @endif
        {{-- End Customer --}}

        {{-- Supplier --}}
        @if (Auth::user()->can('supplier.menu'))
            <a href="{{ route('all.supplier') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.supplier') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                 @if(request()->routeIs('all.supplier'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                <span class="px-2">Supplier</span>
            </a>
        @endif
        {{-- End Supplier --}}

        {{-- Stock --}}
        @if (Auth::user()->can('stock.menu'))
            <a href="{{ route('all.stock') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.stock') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('all.stock'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                <span class="px-2">Stock</span>
            </a>
        @endif
        {{-- End Stock --}}

        {{-- Purchase Dropdown --}}
        @php
            $purchaseMenu = Auth::user()->can('purchase.menu');
            $purchasePending = Auth::user()->can('purchase.pending');
            $purchaseComplete = Auth::user()->can('purchase.complete');
            $purchasePendingDue = Auth::user()->can('purchase.pending.due');
            $isPurchaseActive = isRouteActive(['pending.purchase', 'complete.purchase', 'purchase.pending.due']);
        @endphp
        @if ($purchaseMenu || $purchasePending || $purchaseComplete || $purchasePendingDue)
            <div id="purchaseDropdown" class="relative group">
                @if ($purchaseMenu)
                    <a href="{{ route('pending.purchase') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isPurchaseActive ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isPurchaseActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <span class="px-2">Purchase</span>
                    </a>
                @endif
                <div class="absolute top-0 left-full ml-2 w-48 opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all duration-300 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-xl rounded-lg p-2 z-10 border border-slate-200 dark:border-slate-700">
                    @if ($purchasePending)
                        <a href="{{ route('pending.purchase') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Pending</a>
                    @endif
                    @if ($purchaseComplete)
                        <a href="{{ route('complete.purchase') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Complete</a>
                    @endif
                    @if ($purchasePendingDue)
                        <a href="{{ route('purchase.pending.due') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Pending Due</a>
                    @endif
                </div>
            </div>
        @endif
        {{-- End Purchase --}}

        {{-- Expense Dropdown --}}
        @php
            $canAdd = Auth::user()->can('expense.menu');
            $canToday = Auth::user()->can('expense.today');
            $canMonth = Auth::user()->can('expense.month');
            $canYear = Auth::user()->can('expense.year');
            $isExpenseActive = isRouteActive(['add.expense', 'today.expense', 'month.expense', 'year.expense']);
        @endphp
        @if ($canAdd || $canToday || $canMonth || $canYear)
            <div id="expenseDropdown" class="relative group">
                @if ($canAdd)
                    <a href="{{ route('add.expense') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isExpenseActive ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isExpenseActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                        @endif
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                        </svg>
                        <span class="px-2">Expense</span>
                    </a>
                @endif
                <div class="absolute top-0 left-full ml-2 w-48 opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all duration-300 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-xl rounded-lg p-2 z-10 border border-slate-200 dark:border-slate-700">
                    @if ($canToday)
                        <a href="{{ route('today.expense') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Today</a>
                    @endif
                    @if ($canMonth)
                        <a href="{{ route('month.expense') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Month</a>
                    @endif
                    @if ($canYear)
                        <a href="{{ route('year.expense') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Year</a>
                    @endif
                </div>
            </div>
        @endif
        {{-- End Expense --}}

        {{-- Order Dropdown --}}
        @php
            $orderMenu = Auth::user()->can('order.menu');
            $orderPending = Auth::user()->can('order.pending');
            $orderComplete = Auth::user()->can('order.complete');
            $orderPendingDue = Auth::user()->can('order.pending.due');
            $isOrderActive = isRouteActive(['pending.order', 'complete.order', 'pending.due']);
        @endphp
        @if ($orderMenu || $orderPending || $orderComplete || $orderPendingDue)
            <div id="orderDropdown" class="relative group">
                @if ($orderMenu)
                    <a href="{{ route('pending.order') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isOrderActive ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isOrderActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                        <span class="px-2">Order</span>
                    </a>
                @endif
                <div class="absolute top-0 left-full ml-2 w-48 opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all duration-300 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-xl rounded-lg p-2 z-10 border border-slate-200 dark:border-slate-700">
                    @if ($orderPending)
                        <a href="{{ route('pending.order') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Pending</a>
                    @endif
                    @if ($orderComplete)
                        <a href="{{ route('complete.order') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Complete</a>
                    @endif
                    @if ($orderPendingDue)
                        <a href="{{ route('pending.due') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Pending Due</a>
                    @endif
                </div>
            </div>
        @endif
        {{-- End Order --}}

        {{-- Permission Dropdown --}}
        @if (Auth::user()->can('role.menu'))
            @php
                $isPermissionActive = isRouteActive(['all.permission', 'add.permission', 'edit.permission', 'all.roles', 'add.roles', 'edit.roles', 'add.roles.permission', 'all.roles.permission']);
            @endphp
            <div id="permissionDropdown" class="relative group">
                <a href="{{ route('all.permission') }}"
                    class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                    {{ $isPermissionActive ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if($isPermissionActive)
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                    </svg>
                    <span class="px-2">Permission</span>
                </a>
                <div class="absolute top-0 left-full ml-2 w-52 opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all duration-300 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-xl rounded-lg p-2 z-10 border border-slate-200 dark:border-slate-700">
                    <a href="{{ route('all.permission') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">All Permission</a>
                    <a href="{{ route('all.roles') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">All Roles</a>
                    <a href="{{ route('add.roles.permission') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Roles in Permission</a>
                    <a href="{{ route('all.roles.permission') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">All Roles in Permission</a>
                </div>
            </div>
        @endif
        {{-- End Permission --}}

        {{-- User Menu --}}
        @if (Auth::user()->can('user.menu'))
            <a href="{{ route('all.admin') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.admin') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('all.admin'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span class="px-2">User</span>
            </a>
        @endif
        {{-- End User --}}

        {{-- Backup Menu --}}
        @if (Auth::user()->can('backup.menu'))
            <a href="{{ route('admin.backup') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('admin.backup') ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('admin.backup'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
                <span class="px-2">Backup</span>
            </a>
        @endif
        {{-- End Backup --}}

        {{-- Report Dropdown --}}
        @php
            $reportMenu = Auth::user()->can('report.menu');
            $reportSale = Auth::user()->can('report.sale');
            $reportPurchase = Auth::user()->can('report.purchase');
            $reportStock = Auth::user()->can('report.stock');
            $reportExpense = Auth::user()->can('report.expense');
            $isReportActive = isRouteActive(['all.reports', 'report.purchases.view', 'all.report.stock', 'report.income_expense.view']);
        @endphp
        @if ($reportMenu || $reportSale || $reportPurchase || $reportStock || $reportExpense)
            <div id="reportDropdown" class="relative group">
                @if ($reportMenu)
                    <a href="{{ route('all.reports') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isReportActive ? 'bg-red-500/10 text-red-600 font-semibold dark:text-red-400' : 'text-slate-600 hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isReportActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-red-500"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        <span class="px-2">Report</span>
                    </a>
                @endif
                <div class="absolute top-0 left-full ml-2 w-48 opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all duration-300 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-xl rounded-lg p-2 z-10 border border-slate-200 dark:border-slate-700">
                    @if ($reportSale)
                        <a href="{{ route('all.reports') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Sale</a>
                    @endif
                    @if ($reportPurchase)
                        <a href="{{ route('report.purchases.view') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Purchase</a>
                    @endif
                    @if ($reportStock)
                        <a href="{{ route('all.report.stock') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Stock</a>
                    @endif
                    @if ($reportExpense)
                        <a href="{{ route('report.income_expense.view') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Income & Outcome</a>
                    @endif
                </div>
            </div>
        @endif
        {{-- End Report --}}
        

    </div>
</div>

    {{-- The original scripts are restored here for correct functionality --}}
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggleSidebar");
        const pxTexts = sidebar.querySelectorAll(".px-2");

        // រក្សាទុក class transition ទុកក្នុងអថេរ
        const transitionClasses = ["transition-all", "duration-300", "ease-in-out"];

        const sidebarState = localStorage.getItem("sidebarState");

        function applySidebarState(state) {
            if (state === "collapsed") {
                sidebar.classList.remove("w-56");
                sidebar.classList.add("w-[80px]");
                pxTexts.forEach(el => el.classList.add("hidden"));
            } else { // 'expanded' state
                sidebar.classList.remove("w-[80px]");
                sidebar.classList.add("w-56");
                pxTexts.forEach(el => el.classList.remove("hidden"));
            }
        }

        // ពិនិត្យមើល toggleBtn មុននឹងដំណើរការកូដ
        if (toggleBtn) {
            // ដំណើរការฟังก์ชันដើម្បីកំណត់ទំហំភ្លាមៗដោយគ្មាន animation
            applySidebarState(sidebarState);

            // បន្ថែម class transition ត្រឡប់មកវិញបន្ទាប់ពីពន្យារពេលបន្តិច
            // ដើម្បីឱ្យ browser មិនចាប់យកការផ្លាស់ប្តូរទំហំដំបូង
            setTimeout(() => {
                sidebar.classList.add(...transitionClasses);
            }, 50); // 50ms គឺគ្រប់គ្រាន់ហើយ

            toggleBtn.addEventListener("click", () => {
                const isExpanded = sidebar.classList.contains("w-56");
                const newState = isExpanded ? "collapsed" : "expanded";
                localStorage.setItem("sidebarState", newState);
                applySidebarState(newState);
            });
        }
    });
</script>
</nav>