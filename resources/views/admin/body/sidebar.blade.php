<nav id="sidebar"
    class="static h-full nset-0  backdrop-blur-sm card-dynamic-bg w-56 p-4 md:block hidden border-none border-slate-200/70 dark:border-slate-800 z-40 overflow-y-auto overflow-x-hidden">

    <div id="sidebar-nav" class="space-y-1">
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
                {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                @if(request()->routeIs('dashboard'))
                    <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                @endif

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                </svg>
                <span class="px-2">{{ __('messages.dashboard') }}</span>
            </a>
            {{-- End Dashboard --}}

            {{-- Category Dropdown --}}
            @php
                $categoryMenu = Auth::user()->can('category.menu');
                $categoryCondition = Auth::user()->can('condition.all');
                $isCategoryActive = request()->routeIs('all.category') || request()->routeIs('all.condition');
            @endphp
            @if ($categoryMenu || $categoryCondition)
                <div class="relative" data-dropdown-trigger>
                    <a href="{{ route('all.category') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isCategoryActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isCategoryActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                        </svg>
                        <span class="px-2">{{ __('messages.category') }}</span>
                    </a>
                    <div data-dropdown-menu class="absolute top-0 left-full ml-2 w-48  card-dynamic-bg backdrop-blur-none shadow-xl rounded-lg p-2 z-50 border  hidden">
                        @if ($categoryMenu)
                            <a href="{{ route('all.category') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">
                                {{ __('messages.category') }}
                            </a>
                        @endif
                        @if ($categoryCondition)
                            <a href="{{ route('all.condition') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">
                                {{ __('messages.condition') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            {{-- End Category Dropdown --}}

            {{-- Product --}}
            @if (Auth::user()->can('product.menu'))
                @php
                    $isCustomerActive = request()->routeIs('all.product','detail.product','barcode.product','add.product','edit.product','import.product');
                @endphp
                <a href="{{ route('all.product') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ $isCustomerActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if($isCustomerActive)
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <span class="px-2">{{ __('messages.product') }}</span>
                </a>
            @endif
            {{-- End Product --}}

            {{-- Stock --}}
            @if (Auth::user()->can('stock.menu'))
                <a href="{{ route('all.stock') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ request()->routeIs('all.stock') ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if(request()->routeIs('all.stock'))
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                    </svg>
                    <span class="px-2">{{ __('messages.stock') }}</span>
                </a>
            @endif
            {{-- End Stock --}}

            {{-- Customer --}}
            @if (Auth::user()->can('customer.menu'))
                @php
                    $isCustomerActive = request()->routeIs('customer.all','add.customer','edit.customer','delete.customer');
                @endphp
                <a href="{{ route('customer.all') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ $isCustomerActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if($isCustomerActive)
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="px-2">{{ __('messages.customer') }}</span>
                </a>
            @endif
            {{-- End Customer --}}

            {{-- Supplier --}}
            @if (Auth::user()->can('supplier.menu'))
                @php
                    $isCustomerActive = request()->routeIs('all.supplier','add.supplier','edit.supplier');
                @endphp
                <a href="{{ route('all.supplier') }}"
                class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                {{ $isCustomerActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if($isCustomerActive)
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span class="px-2">{{ __('messages.supplier') }}</span>
                </a>
            @endif
            {{-- End Supplier --}}

            {{-- Purchase Dropdown --}}
            @php
                $purchaseMenu = Auth::user()->can('purchase.menu');
                $purchaseComplete = Auth::user()->can('purchase.complete');
                $purchasePendingDue = Auth::user()->can('purchase.pending.due');
                $isPurchaseActive = isRouteActive(['complete.purchase', 'purchase.pending.due','purchase.page']);
            @endphp
            @if ($purchaseMenu || $purchaseComplete || $purchasePendingDue)
                <div class="relative" data-dropdown-trigger>
                    <a href="{{ route('purchase.page') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isPurchaseActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isPurchaseActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        <span class="px-2">{{ __('messages.purchase') }}</span>
                    </a>
                    <div data-dropdown-menu class="absolute top-0 left-full ml-2 w-48 card-dynamic-bg backdrop-blur-none shadow-xl rounded-lg p-2 z-50 border  hidden">
                        @if ($purchaseComplete)
                            <a href="{{ route('complete.purchase') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.complete') }}</a>
                        @endif
                        @if ($purchasePendingDue)
                            <a href="{{ route('purchase.pending.due') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.pending_due') }}</a>
                        @endif
                    </div>
                </div>
            @endif
            {{-- End Purchase --}}

            {{-- Order Dropdown --}}
            @php
                $orderMenu = Auth::user()->can('order.menu');
                $orderPending = Auth::user()->can('order.pending');
                $orderComplete = Auth::user()->can('order.complete');
                // $orderPendingDue = Auth::user()->can('order.pending.due');
                $isOrderActive = isRouteActive(['pending.order', 'complete.order', 'pending.due']);
            @endphp
            @if ($orderMenu || $orderPending || $orderComplete  )
                <div class="relative" data-dropdown-trigger>
                    <a href="{{ route('pending.order') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isOrderActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isOrderActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                        <span class="px-2">{{ __('messages.order') }}</span>
                    </a>
                    <div data-dropdown-menu class="absolute top-0 left-full ml-2 w-48 card-dynamic-bg backdrop-blur-none shadow-xl rounded-lg p-2 z-50 border  hidden">
                        @if ($orderPending)
                            <a href="{{ route('pending.order') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.pending') }}</a>
                        @endif
                        @if ($orderComplete)
                            <a href="{{ route('complete.order') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.complete') }}</a>
                        @endif
                        {{-- @if ($orderPendingDue)
                            <a href="{{ route('pending.due') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.pending_due_sale') }}</a>
                        @endif --}}
                    </div>
                </div>
            @endif
            {{-- End Order --}}

            {{-- Permission Dropdown --}}
            @if (Auth::user()->can('role.menu'))
                @php
                    $isPermissionActive = isRouteActive(['all.permission', 'add.permission', 'edit.permission', 'all.roles', 'add.roles', 'edit.roles', 'add.roles.permission', 'all.roles.permission']);
                @endphp
                <div class="relative" data-dropdown-trigger>
                    <a href="{{ route('all.permission') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isPermissionActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isPermissionActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                        </svg>
                        <span class="px-2">{{ __('messages.permission') }}</span>
                    </a>
                    <div data-dropdown-menu class="absolute top-0 left-full ml-2 w-52 card-dynamic-bg backdrop-blur-none shadow-xl rounded-lg p-2 z-50 border  hidden">
                        <a href="{{ route('all.permission') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.all_permission') }}</a>
                        <a href="{{ route('all.roles') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.all_roles') }}</a>
                        <a href="{{ route('all.roles.permission') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.all_roles_in_permission') }}</a>
                    </div>
                </div>
            @endif
            {{-- End Permission --}}

            {{-- Report Dropdown --}}
            @php
                $reportMenu = Auth::user()->can('report.menu');
                $reportSale = Auth::user()->can('report.sale');
                $reportPurchase = Auth::user()->can('report.purchase');
                $reportStock = Auth::user()->can('report.stock');
                $reportExpense = Auth::user()->can('report.expense');
                $reportShift  = Auth::user()->can('report.shifts');
                $isReportActive = isRouteActive(['all.reports', 'report.purchases.view', 'all.report.stock', 'report.income_expense.view', 'report.shifts']);
            @endphp
            @if ($reportMenu || $reportSale || $reportPurchase || $reportStock || $reportExpense  || $reportShift)
                <div class="relative" data-dropdown-trigger>
                    <a href="{{ route('all.reports') }}"
                        class="relative nav-link flex items-center py-2.5 px-4 rounded-lg w-full transition-colors duration-200
                        {{ $isReportActive ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                        @if($isReportActive)
                            <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        <span class="px-2">{{ __('messages.report') }}</span>
                    </a>
                    <div data-dropdown-menu class="absolute top-0 left-full ml-2 w-48 card-dynamic-bg backdrop-blur-none shadow-xl rounded-lg p-2 z-50 border  hidden">
                        @if ($reportSale)
                            <a href="{{ route('all.reports') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.sale_report') }}</a>
                        @endif
                        @if ($reportPurchase)
                            <a href="{{ route('report.purchases.view') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.purchases_report') }}</a>
                        @endif
                        @if ($reportStock)
                            <a href="{{ route('all.report.stock') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.stock_report') }}</a>
                        @endif
                        @if ($reportExpense)
                            <a href="{{ route('report.income_expense.view') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md text-defalut hover:bg-primary">{{ __('messages.incom_outcome_report') }}</a>
                        @endif

                        @if ($reportShift)
                            <a href="{{ route('report.shifts') }}" class="block w-full text-left px-3 py-2 text-sm rounded-md ...">
                                {{ __('messages.shift_report') }}
                            </a>
                        @endif
                        
                    </div>
                </div>
            @endif
            {{-- End Report --}}

            {{-- User Menu --}}
            @if (Auth::user()->can('user.menu'))
                <a href="{{ route('all.admin') }}"
                    class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                    {{ request()->routeIs('all.admin') ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if(request()->routeIs('all.admin'))
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="px-2">{{ __('messages.user') }}</span>
                </a>
            @endif
            {{-- End User --}}

            {{-- Backup Menu --}}
            @if (Auth::user()->can('backup.menu'))
                <a href="{{ route('admin.backup') }}"
                    class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                    {{ request()->routeIs('admin.backup') ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if(request()->routeIs('admin.backup'))
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span class="px-2">{{ __('messages.backup') }}</span>
                </a>
            @endif
            {{-- End Backup --}}

            {{-- Setting Menu --}}
            @if (Auth::user()->can('setting.menu'))
                <a href="{{ route('admin.setting') }}"
                    class="relative nav-link flex items-center py-2.5 px-4 rounded-lg transition-colors duration-200
                    {{ request()->routeIs('admin.setting', 'admin.setting_infromationshop','db.import.form') ? 'text-primary' : ' text-default hover:bg-slate-200/60 dark:text-slate-300 dark:hover:bg-slate-700/60' }}">
                    @if(request()->routeIs('admin.setting', 'admin.setting_infromationshop','db.import.form'))
                        <span class="absolute inset-y-0 left-0 w-1 rounded-r-full bg-primary"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="px-2">{{ __('messages.setting') }}</span>
                </a>
            @endif
            {{-- End Setting --}}
            
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Sidebar Toggle Logic ---
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggleSidebar");
        
        // --- Dropdown Pop-out Logic ---
        const triggers = document.querySelectorAll('[data-dropdown-trigger]');
        let activeDropdown = null;
        let leaveTimeout;

        function hideActiveDropdown() {
            if (activeDropdown) {
                activeDropdown.remove();
                activeDropdown = null;
            }
        }
        
        if (sidebar && toggleBtn) {
            const pxTexts = sidebar.querySelectorAll(".px-2");
            const transitionClasses = ["transition-all", "duration-300", "ease-in-out"];
            const sidebarState = localStorage.getItem("sidebarState");

            function applySidebarState(state) {
                if (state === "collapsed") {
                    sidebar.classList.remove("w-56");
                    sidebar.classList.add("w-[80px]");
                    pxTexts.forEach(el => el.classList.add("hidden"));
                    hideActiveDropdown();
                } else {
                    sidebar.classList.remove("w-[80px]");
                    sidebar.classList.add("w-56");
                    pxTexts.forEach(el => el.classList.remove("hidden"));
                }
            }

            applySidebarState(sidebarState);
            setTimeout(() => sidebar.classList.add(...transitionClasses), 50);

            toggleBtn.addEventListener("click", () => {
                const isExpanded = sidebar.classList.contains("w-56");
                const newState = isExpanded ? "collapsed" : "expanded";
                localStorage.setItem("sidebarState", newState);
                applySidebarState(newState);
            });
        }

        function showDropdown(trigger) {
            hideActiveDropdown();
            const menuTemplate = trigger.querySelector('[data-dropdown-menu]');
            if (!menuTemplate) return;

            // if (sidebar && sidebar.classList.contains('w-[80px]')) {
            //     return;
            // }

            activeDropdown = menuTemplate.cloneNode(true);
            activeDropdown.classList.remove('hidden');
            activeDropdown.style.position = 'fixed';
            document.body.appendChild(activeDropdown);

            const rect = trigger.getBoundingClientRect();
            activeDropdown.style.top = `${rect.top}px`;
            activeDropdown.style.left = `${rect.right + 8}px`;
            activeDropdown.style.zIndex = '9999';

            const clearLeaveTimeout = () => clearTimeout(leaveTimeout);
            const scheduleHide = () => {
                leaveTimeout = setTimeout(hideActiveDropdown, 900);
            };

            trigger.addEventListener('mouseleave', scheduleHide);
            activeDropdown.addEventListener('mouseenter', clearLeaveTimeout);
            activeDropdown.addEventListener('mouseleave', scheduleHide);
        }

        triggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', () => {
                clearTimeout(leaveTimeout);
                showDropdown(trigger);
            });
        });
    });
    </script>
</nav>