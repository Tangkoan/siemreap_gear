<header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b  border-slate-200 dark:border-slate-800 dark:border-0 transition-colors duration-300">
    
    <div class="container mx-auto flex flex-wrap justify-between items-center gap-y-2 p-3">

        {{-- ✅ START: PHP Block to fetch Shop Info --}}
        {{-- ✅ ចាប់ផ្តើម៖ ប្លុក PHP សម្រាប់ទាញយក --}}
        @php
            // ទាញយក Record ពី Table 'informationshops'
            $shopInfo = \App\Models\InformationShop::first();
        @endphp
        {{-- ✅ END: PHP Block to fetch Shop Info --}}

        
        {{-- Left Side: Logo & Sidebar Toggle --}}
        <div class="flex items-center space-x-2">
            
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <span class="px-2">
                    {{-- ✅ កែប្រែទីនេះ: Dynamic Logo --}}
                    {{-- កូដនេះនឹងពិនិត្យមើលថា $shopInfo មាន logo ឬអត់ --}}
                    <img class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0" 
                         src="{{ ($shopInfo && $shopInfo->logo) ? asset('upload/shop_info/' . $shopInfo->logo) : asset('upload/no_image.jpg') }}" 
                         alt="Shop Logo">
                </span>
                <span class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    {{-- ✅ កែប្រែទីនេះ: Dynamic Shop Name --}}
                    {{-- បើ $shopInfo មាន name_en, បើ Default --}}
                    {{ $shopInfo->name_en ?? 'Siem Reap Gear' }}
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

            @php
                // Get the active rate from the Database
                // ទាញយកអត្រាប្ដូរប្រាក់ដែលកំពុង Active ពី Database
                $activeRate = \App\Models\ExchangeRate::where('is_active', true)->latest()->first();
            @endphp

            <button id="exchange-rate-btn" class="hidden sm:block bg-teal-500 hover:bg-teal-600 text-white   py-2 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                1$ = <span id="current-rate-display">{{ $activeRate->rate_khr ?? '?' }}</span>៛
            </button>

            {{-- ✅ NEW: Open/Close Shift Button --}}
            {{-- ✅ ថ្មី៖ ប៊ូតុងបើក/បិទវេន --}}
            <button id="shift-toggle-btn" class="hidden sm:block text-white py-2 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                <span id="shift-btn-text">Open Shift</span>
            </button>
            
            @can('pos.menu')
                <a href="{{ route('pos') }}" id="pos-link-btn" class="hidden sm:block bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('messages.pos') }}
                </a>
            @else
                <button class="hidden sm:block bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400  py-2 px-4 rounded-lg shadow-md cursor-not-allowed" disabled title="You don't have permission to access POS">
                    {{ __('messages.pos') }}
                </button>
            @endcan
            
            {{-- @can('pos.menu')
                <a href="{{ route('pos') }}" class="hidden sm:block bg-red-600 hover:bg-red-700 text-white  py-2 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                    {{ __('messages.pos') }}
                </a>
            @else
                <button class="hidden sm:block bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400   py-2 px-4 rounded-lg shadow-md cursor-not-allowed" disabled title="You don't have permission to access POS">
                    {{ __('messages.pos') }}
                </button>
            @endcan --}}

            {{-- Theme Toggle --}}
            <button id="theme-toggle" class="w-16 h-8 rounded-full bg-slate-200 dark:bg-slate-700 relative flex items-center transition-colors duration-500">
                <span id="toggle-thumb" class="absolute left-1 w-6 h-6 bg-white dark:bg-slate-800 rounded-full shadow-md transform transition-transform duration-300 flex items-center justify-center">
                    <svg id="toggle-icon" class="w-4 h-4 text-slate-700 dark:text-white transition-all duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"></svg>
                </span>
            </button>

            {{-- Language Switcher --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center justify-center p-2 rounded-full text-slate-600 dark:text-slate-300 transition-colors focus:outline-none">
                    @if (app()->getLocale() == 'km')
                        <img src="https://flagcdn.com/w20/kh.png" srcset="https://flagcdn.com/w40/kh.png 2x" width="30" alt="Khmer" class="dark:ring-1 dark:ring-white rounded-sm">
                    @else
                        <img src="https://flagcdn.com/w20/us.png" srcset="https://flagcdn.com/w40/us.png 2x" width="30" alt="English" class="dark:ring-1 dark:ring-white rounded-sm">
                    @endif
                    <span class="ml-2 text-xs  ">{{ strtoupper(app()->getLocale()) }}</span>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-40 rounded-xl bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl py-2 z-50 origin-top-right ring-1 ring-black ring-opacity-5">
                    <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                        <img src="https://flagcdn.com/w20/us.png" srcset="https://flagcdn.com/w40/us.png 2x" width="20" alt="English" class="dark:ring-1 dark:ring-white rounded-sm">
                        <span>English</span>
                    </a>
                    <a href="{{ route('language.switch', 'km') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors">
                        <img src="https://flagcdn.com/w20/kh.png" srcset="https://flagcdn.com/w40/kh.png 2x" width="20" alt="Khmer" class="dark:ring-1 dark:ring-white rounded-sm">
                        <span>ភាសាខ្មែរ</span>
                    </a>
                </div>
            </div>

            {{-- Stock Alert Notification --}}
            <div class="relative">
                <button id="messageBtn" type="button" class="relative p-2 rounded-full text-slate-600 bg-slate-200/60 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/60 dark:bg-slate-700/60 transition-colors focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <span id="notificationCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/3 -translate-y-1/3">0</span>
                </button>
                <div id="messageDropdown" class="hidden absolute right-0 z-50 mt-2 w-72 origin-top-right rounded-lg bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl ring-1 ring-black ring-opacity-5">
                    <div id="notificationContent" class="py-1 max-h-[500px] overflow-y-auto">
                        {{-- Notifications loaded by JS --}}
                    </div>
                </div>
            </div>

            {{-- User Profile --}}
            @php
                $id = Auth::user()->id;
                $adminData = App\Models\User::find($id);
            @endphp
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="group flex items-center space-x-2 focus:outline-none">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-transparent group-hover:border-blue-500 transition-colors" src="{{ !empty($adminData->photo) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}" alt="User Profile">
                    <div class="font-medium text-left hidden lg:block">
                        <div class="text-sm text-slate-800 dark:text-slate-200 transition-colors">{{ $adminData->name }}</div>
                        {{-- កែត្រង់នេះ --}}
                        <div class="text-xs text-slate-500 dark:text-slate-400 transition-colors">{{ $adminData->roles->first()?->name }}</div>
                    </div>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-56 rounded-xl bg-white/90 dark:bg-slate-800/90 backdrop-blur-md shadow-2xl py-2 z-50 origin-top-right ring-1 ring-black ring-opacity-5">
                    {{-- Dropdown content --}}
                    <div class="px-4 py-2">
                        <p class="text-sm text-slate-700 dark:text-slate-200">{{ __('messages.signed_in_as') }}</p>
                        <p class="text-sm   text-slate-900 dark:text-white truncate">{{ $adminData->name }}</p>
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

            {{-- Mobile Menu Button (Hamburger) --}}
            <button id="menu-button" class="md:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>



            

        </div>
    </div>
</header>

{{-- ✅ START: Exchange Rate Modal with Auto-Fetch Functionality --}}
{{-- ✅ START: Modal សម្រាប់អត្រាប្តូរប្រាក់ ជាមួយនឹងមុខងារចាប់យកស្វ័យប្រវត្តិ --}}
<div id="exchange-rate-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative top-10 sm:top-20 mx-auto w-full max-w-sm transform rounded-xl bg-white p-6 shadow-2xl transition-all duration-300 dark:bg-slate-800 border dark:border-slate-700">
        <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Set Today's Exchange Rate</h3>
            <button id="cancel-exchange-rate-x" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="mt-4 text-left space-y-4">
            {{-- Auto Fetch Button --}}
            {{-- ប៊ូតុងសម្រាប់ចាប់យកអត្រាស្វ័យប្រវត្តិ --}}
            <button id="fetch-auto-rate-btn" type="button" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 disabled:opacity-75">
                <svg id="fetch-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="fetch-btn-text">Fetch Rate from MEF</span>
                
            </button>

            {{-- Separator --}}
            {{-- ខ្សែបន្ទាត់ --}}
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-slate-300 dark:border-slate-600"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white dark:bg-slate-800 px-2 text-sm text-slate-500 dark:text-slate-400">Or enter manually</span>
                </div>
            </div>

            {{-- Manual Input Form --}}
            {{-- Form សម្រាប់បញ្ចូលដោយដៃ --}}
            <form id="exchangeRateForm" class="space-y-4">
                @csrf
                <div>
                    <label for="exchange_rate_input" class="block text-sm font-medium text-slate-700 dark:text-gray-200">1 USD = KHR</label>
                    <input type="number" name="rate" id="exchange_rate_input" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-slate-700" placeholder="e.g., 4150" required>
                    <div id="rate_error" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="pt-4 flex justify-end gap-x-3">
                    <button id="cancel-exchange-rate" type="button" class="px-4 py-2 bg-slate-100 text-slate-800 rounded-md hover:bg-slate-200 focus:outline-none dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">Cancel</button>
                    <button id="save-exchange-rate-btn" type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none disabled:opacity-75">Save Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- ✅ END: Exchange Rate Modal --}}

            {{-- ✅ START: Open Shift Modal (UPDATED) --}}
            {{-- ✅ START: Modal សម្រាប់បើកវេន (បានកែប្រែ) --}}
            @php
                // ទាញយកអត្រាប្តូរប្រាក់ដែលកំពុង Active សម្រាប់បង្ហាញ
                $activeRate = \App\Models\ExchangeRate::where('is_active', true)->latest()->first();
                $currentRateKhr = $activeRate->rate_khr ?? 4000; // 4000 ជា Default បើអត់មាន Rate
            @endphp

            <div id="open-shift-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
                <div class="relative top-10 sm:top-20 mx-auto w-full max-w-sm transform rounded-xl bg-white p-6 shadow-2xl transition-all duration-300 dark:bg-slate-800 border dark:border-slate-700">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Open Shift</h3>
                        <button id="cancel-open-shift-x" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="mt-4 text-left space-y-4">
                        <form id="openShiftForm" class="space-y-4">
                            @csrf
                            
                            {{-- USD Input --}}
                            <div>
                                <label for="opening_cash_usd_input" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Starting Cash (USD)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="opening_cash_usd" id="opening_cash_usd_input" class="block w-full pr-10 pl-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-slate-700" value="0" required step="0.01">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                </div>
                                <div id="usd_error" class="text-red-500 text-sm mt-1"></div>
                            </div>

                            {{-- KHR Input --}}
                            <div>
                                <label for="opening_cash_khr_input" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Starting Cash (KHR)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="opening_cash_khr" id="opening_cash_khr_input" class="block w-full pr-10 pl-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-slate-700" value="0" required step="1">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">៛</span>
                                    </div>
                                </div>
                                <div id="khr_error" class="text-red-500 text-sm mt-1"></div>
                            </div>

                            {{-- Exchange Rate Display/Hidden Input --}}
                            <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-md">
                                <label class="block text-sm font-medium text-slate-700 dark:text-gray-200">Current Exchange Rate</label>
                                <p id="current_rate_display_modal" class="text-xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $currentRateKhr }}</p>
                                <input type="hidden" name="exchange_rate" id="exchange_rate_hidden_input" value="{{ $currentRateKhr }}">
                                <p class="text-xs text-slate-500 dark:text-slate-400">1 USD = KHR</p>
                            </div>
                            
                            <div id="shift_error" class="text-red-500 text-sm mt-1"></div> {{-- General Error --}}

                            <div class="pt-4 flex justify-end gap-x-3">
                                <button id="cancel-open-shift" type="button" class="px-4 py-2 bg-slate-100 text-slate-800 rounded-md hover:bg-slate-200 focus:outline-none dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">Cancel</button>
                                <button id="save-open-shift-btn" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none disabled:opacity-75">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ✅ END: Open Shift Modal --}}



            {{-- ✅ START: Close Shift Modal (UPDATED) --}}
            <div id="close-shift-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300">
                <div class="relative top-10 sm:top-20 mx-auto w-full max-w-sm transform rounded-xl bg-white p-6 shadow-2xl transition-all duration-300 dark:bg-slate-800 border dark:border-slate-700">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Close Shift Now</h3>
                        <button id="cancel-close-shift-x" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="mt-4 text-left space-y-4">
                        <form id="closeShiftForm" class="space-y-4">
                            @csrf
                            
                            {{-- USD Input --}}
                            <div>
                                <label for="closing_cash_usd_input" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Ending Cash Count (USD)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="closing_cash_usd" id="closing_cash_usd_input" class="block w-full pr-10 pl-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm dark:bg-slate-700" placeholder="0" required step="0.01">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                </div>
                                <div id="closing_usd_error" class="text-red-500 text-sm mt-1"></div>
                            </div>

                            {{-- KHR Input --}}
                            <div>
                                <label for="closing_cash_khr_input" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Ending Cash Count (KHR)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="closing_cash_khr" id="closing_cash_khr_input" class="block w-full pr-10 pl-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm dark:bg-slate-700" placeholder="0" required step="1">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">៛</span>
                                    </div>
                                </div>
                                <div id="closing_khr_error" class="text-red-500 text-sm mt-1"></div>
                            </div>

                            {{-- Field សម្រាប់កំណត់ចំណាំ --}}
                            <div>
                                <label for="notes_input_close" class="block text-sm font-medium text-slate-700 dark:text-gray-200">Notes (Optional)</label>
                                <textarea name="notes" id="notes_input_close" rows="3" class="mt-1 block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-slate-700" placeholder="Any issues or comments during the shift..."></textarea>
                            </div>

                            <div class="pt-4 flex justify-end gap-x-3">
                                <button id="cancel-close-shift" type="button" class="px-4 py-2 bg-slate-100 text-slate-800 rounded-md hover:bg-slate-200 focus:outline-none dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">Cancel</button>
                                <button id="save-close-shift-btn" type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none disabled:opacity-75">Confirm Close Shift</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ✅ END: Close Shift Modal --}}

            



<script>
document.addEventListener("DOMContentLoaded", () => {
    
    // --- PART 1: Theme Toggle Logic ---
    const toggleBtn = document.getElementById("theme-toggle");
    if (toggleBtn) {
        const thumb = document.getElementById("toggle-thumb");
        const icon = document.getElementById("toggle-icon");

        const setSunIcon = () => {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="text-yellow-400" fill="currentColor" d="M12 4V2m0 20v-2m8-8h2M2 12h2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414M17.95 17.95l-1.414-1.414M6.05 6.05L4.636 7.464M12 8a4 4 0 100 8 4 4 0 000-8z" />`;
            icon.classList.add('text-yellow-400');
        };

        const setMoonIcon = () => {
            icon.classList.remove('text-yellow-400');
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 009.79 9.79z" />`;
        };

        const updateUI = (isDark) => {
            thumb.classList.toggle("translate-x-8", isDark);
            thumb.classList.toggle("translate-x-1", !isDark);
            icon.classList.add("scale-75", "opacity-0");
            setTimeout(() => {
                icon.innerHTML = '';
                isDark ? setMoonIcon() : setSunIcon();
                icon.classList.remove("scale-75", "opacity-0");
                icon.classList.add("scale-100", "opacity-100");
            }, 200);
        };

        const isDarkInit = localStorage.getItem("theme") === "dark";
        updateUI(isDarkInit);

        toggleBtn.addEventListener("click", () => {
            const isDark = document.documentElement.classList.toggle("dark");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            updateUI(isDark);
        });
    }

    // --- PART 2: Stock Alert Notification Logic ---
    const messageBtn = document.getElementById('messageBtn');
    if (messageBtn) {
        const dropdown = document.getElementById('messageDropdown');
        const notificationCountSpan = document.getElementById('notificationCount');
        const notificationContentDiv = document.getElementById('notificationContent');

        const fetchStockAlerts = async () => {
            try {
                const response = await fetch("{{ route('stock.alerts') }}");
                const data = await response.json();
                if (data.status === 'success') {
                    notificationCountSpan.textContent = data.count;
                    notificationContentDiv.innerHTML = ''; 
                    if (data.count > 0) {
                        data.products.forEach(product => {
                            const alertItem = document.createElement('div');
                            alertItem.className = 'flex items-start px-4 py-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                            alertItem.innerHTML = `
                                <div class="flex-shrink-0 mt-1 icon-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100 mb-1">{{ __('messages.product_name') }}: <span class="text-indigo-600 dark:text-indigo-400">${product.product_name || 'N/A'}</span></p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">{{ __('messages.in_stock') }}: <strong>${product.product_store}</strong> ({{ __('messages.alert_threshold') }}: ${product.stock_alert})</p>
                                </div>`;
                            notificationContentDiv.appendChild(alertItem);
                        });
                    } else {
                        notificationContentDiv.innerHTML = `<p class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 text-center italic">{{ __('messages.no_stock_alert') }}</p>`;
                    }
                }
            } catch (error) {
                console.error('Error fetching stock alerts:', error);
                notificationContentDiv.innerHTML = '<p class="px-4 py-2 text-sm text-red-500 text-center">Failed to load notifications.</p>';
            }
        };

        fetchStockAlerts();
        messageBtn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
            fetchStockAlerts();
        });

        document.addEventListener('click', (e) => {
            if (!messageBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // --- PART 3: Exchange Rate Modal Logic (Updated) ---
    // --- ផ្នែកទី៣៖ Logic សម្រាប់ Modal អត្រាប្តូរប្រាក់ (បានកែប្រែ) ---
    const exchangeModal = document.getElementById('exchange-rate-modal');
    if (exchangeModal) {
        const openBtn = document.getElementById('exchange-rate-btn');
        const cancelBtnX = document.getElementById('cancel-exchange-rate-x');
        const cancelBtn = document.getElementById('cancel-exchange-rate');
        const manualForm = document.getElementById('exchangeRateForm');
        
        // Get elements for auto-fetch
        // យក Element សម្រាប់ auto-fetch
        const fetchAutoBtn = document.getElementById('fetch-auto-rate-btn');
        const fetchSpinner = document.getElementById('fetch-spinner');
        const fetchBtnText = document.getElementById('fetch-btn-text');

        // Function to open the modal
        // Function សម្រាប់បើក Modal
        const openModal = () => exchangeModal.classList.remove('hidden');

        // Function to close the modal and reset form
        // Function សម្រាប់បិទ Modal និង reset form
        const closeModal = () => {
            exchangeModal.classList.add('hidden');
            manualForm.reset();
            const rateError = document.getElementById('rate_error');
            if (rateError) rateError.textContent = '';
        };
        
        // Function to update UI after a successful save (auto or manual)
        // Function សម្រាប់ update UI បន្ទាប់ពីការរក្សាទុកជោគជ័យ (auto ឬ manual)
        const updateDisplayAndClose = (data) => {
            toastr.success(data.message);
            // Update rate on the main button
            // Update អត្រានៅលើប៊ូតុងធំ
            document.getElementById('current-rate-display').textContent = data.new_rate;
            
            // Update rate on the POS page's hidden input if it exists
            // Update អត្រានៅលើ Input ដែលលាក់ក្នុងទំព័រ POS បើមាន
            const posRateInput = document.getElementById('exchange_rate_khr');
            if (posRateInput) {
                posRateInput.value = data.new_rate;
                // Dispatch event for other scripts to listen to
                // បញ្ជូន Event ឲ្យ Script ផ្សេងៗអាចត្រងស្តាប់បាន
                document.dispatchEvent(new CustomEvent('rateUpdated'));
            }
            closeModal();
        };
        
        // Event listeners to open/close modal
        // Event Listeners សម្រាប់បើក/បិទ Modal
        if (openBtn) openBtn.addEventListener('click', openModal);
        cancelBtnX.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => {
            if (e.target === exchangeModal) closeModal();
        });

        // ✅ NEW: Event Listener for the Auto-Fetch button
        // ✅ ថ្មី៖ Event Listener សម្រាប់ប៊ូតុងចាប់យកស្វ័យប្រវត្តិ
        fetchAutoBtn.addEventListener('click', function() {
            // Show loading state
            // បង្ហាញពីស្ថានភាពកំពុងដំណើរការ
            fetchBtnText.textContent = 'Fetching...';
            fetchSpinner.classList.remove('hidden');
            this.disabled = true;

            fetch("{{ route('exchange-rate.auto-fetch') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('form#exchangeRateForm input[name="_token"]').value
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, data })))
            .then(({ status, data }) => {
                if (status >= 400) {
                    // Handle API or server errors
                    // ដោះស្រាយបញ្ហាពី API ឬ Server
                    toastr.error(data.message || 'Failed to fetch the rate.');
                    throw new Error(data.message);
                }
                // On success, update UI and close modal
                // ពេលជោគជ័យ គឺ Update UI ហើយបិទ Modal
                updateDisplayAndClose(data);
            })
            .catch(error => {
                console.error('Auto Fetch Error:', error);
            })
            .finally(() => {
                // Reset button state
                // កំណត់សភាពប៊ូតុងឡើងវិញ
                fetchBtnText.textContent = 'Fetch Rate from MEF';
                fetchSpinner.classList.add('hidden');
                this.disabled = false;
            });
        });

        // Event listener for the manual input form
        // Event listener សម្រាប់ Form បញ្ចូលដោយដៃ
        manualForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const saveBtn = document.getElementById('save-exchange-rate-btn');
            saveBtn.disabled = true;
            saveBtn.innerText = 'Saving...';

            fetch("{{ route('exchange-rate.store') }}", {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                    // CSRF is already in FormData
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, data })))
            .then(({ status, data }) => {
                if (status >= 400) {
                    if (data.errors && data.errors.rate) {
                        document.getElementById('rate_error').textContent = data.errors.rate[0];
                    } else {
                        toastr.error(data.message || 'An error occurred.');
                    }
                    throw new Error('Validation or server failed');
                }
                // On success, update UI and close modal
                // ពេលជោគជ័យ គឺ Update UI ហើយបិទ Modal
                updateDisplayAndClose(data);
            })
            .catch(error => {
                console.error('Manual Save Error:', error);
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerText = 'Save Rate';
            });
        });
    }


    // --- PART 4: Shift Management Logic (FINAL UPDATED) ---

        const openShiftModal = document.getElementById('open-shift-modal');
        const closeShiftModal = document.getElementById('close-shift-modal');
        const shiftToggleBtn = document.getElementById('shift-toggle-btn');
        const shiftBtnText = document.getElementById('shift-btn-text');
        const openShiftForm = document.getElementById('openShiftForm');
        const closeShiftForm = document.getElementById('closeShiftForm');
        const posLinkBtn = document.getElementById('pos-link-btn');

        // Exchange Rate elements
        const rateDisplayModal = document.getElementById('current_rate_display_modal');
        const rateHiddenInput = document.getElementById('exchange_rate_hidden_input');

        if (shiftToggleBtn && openShiftModal && closeShiftModal) {
            // Open Modal Close buttons
            const cancelOpenBtnX = document.getElementById('cancel-open-shift-x');
            const cancelOpenBtn = document.getElementById('cancel-open-shift');
            const saveOpenBtn = document.getElementById('save-open-shift-btn');

            // Close Modal Close buttons
            const cancelCloseBtnX = document.getElementById('cancel-close-shift-x');
            const cancelCloseBtn = document.getElementById('cancel-close-shift');
            const saveCloseBtn = document.getElementById('save-close-shift-btn');

            let isShiftOpen = false;

            // Function to update the button's appearance and state
            const updateShiftButtonUI = (isOpen) => {
                isShiftOpen = isOpen;
                shiftToggleBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'bg-orange-600', 'hover:bg-orange-700');
                
                if (isOpen) {
                    shiftToggleBtn.classList.add('bg-orange-600', 'hover:bg-orange-700');
                    shiftBtnText.textContent = 'Close Shift';
                    if (posLinkBtn) {
                        posLinkBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        posLinkBtn.href = "{{ route('pos') }}"; 
                    }
                } else {
                    shiftToggleBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    shiftBtnText.textContent = 'Open Shift';
                    if (posLinkBtn) {
                        posLinkBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        posLinkBtn.removeAttribute('href'); 
                    }
                }
            };

            // Function to check the current shift status from the server
            const checkShiftStatus = async () => {
                try {
                    const response = await fetch("{{ route('shift.check') }}");
                    const data = await response.json();
                    if (data.status === 'success') {
                        updateShiftButtonUI(data.is_open);
                    }
                } catch (error) {
                    console.error('Error checking shift status:', error);
                    updateShiftButtonUI(false); 
                }
            };
            
            checkShiftStatus(); 

            // ✅ NEW: Function to sync Exchange Rate in Open Shift Modal
            const syncExchangeRate = () => {
                const currentRate = document.getElementById('current-rate-display').textContent.trim();
                if (rateDisplayModal && rateHiddenInput && currentRate !== '?') {
                    rateDisplayModal.textContent = currentRate;
                    rateHiddenInput.value = currentRate;
                }
            };
            
            // Listen for rate update event (from Exchange Rate Modal logic)
            document.addEventListener('rateUpdated', syncExchangeRate);
            syncExchangeRate(); // Sync on load too

            // Event Listener for the main toggle button
            shiftToggleBtn.addEventListener('click', () => {
                if (isShiftOpen) {
                    closeShiftModal.classList.remove('hidden');
                } else {
                    syncExchangeRate(); // Sync rate before opening modal
                    openShiftModal.classList.remove('hidden');
                }
            });

            // Close Modal functions for Open Shift Modal
            const closeOpenModal = () => {
                openShiftModal.classList.add('hidden');
                openShiftForm.reset();
                document.getElementById('usd_error').textContent = '';
                document.getElementById('khr_error').textContent = '';
                document.getElementById('shift_error').textContent = '';
            };

            cancelOpenBtnX.addEventListener('click', closeOpenModal);
            cancelOpenBtn.addEventListener('click', closeOpenModal);

            // Close Modal functions for Close Shift Modal
            const closeCloseModal = () => {
                closeShiftModal.classList.add('hidden');
                closeShiftForm.reset();
                document.getElementById('closing_usd_error').textContent = '';
                document.getElementById('closing_khr_error').textContent = '';
            };

            cancelCloseBtnX.addEventListener('click', closeCloseModal);
            cancelCloseBtn.addEventListener('click', closeCloseModal);

            // Close Modals on outside click
            window.addEventListener('click', (e) => {
                if (e.target === openShiftModal) closeOpenModal();
                if (e.target === closeShiftModal) closeCloseModal();
            });

            // --------------------------------------------------
            // 1. Handle Open Shift Form Submission
            // --------------------------------------------------
            openShiftForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveOpenBtn.disabled = true;
                saveOpenBtn.innerText = 'Submitting...';
                
                document.getElementById('usd_error').textContent = '';
                document.getElementById('khr_error').textContent = '';
                document.getElementById('shift_error').textContent = '';

                fetch("{{ route('shift.open') }}", {
                    method: 'POST',
                    body: new FormData(this),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json().then(data => ({ status: response.status, data })))
                .then(({ status, data }) => {
                    if (status >= 400) {
                        // Handle Validation Errors
                        if (data.errors) {
                            if (data.errors.opening_cash_usd) document.getElementById('usd_error').textContent = data.errors.opening_cash_usd[0];
                            if (data.errors.opening_cash_khr) document.getElementById('khr_error').textContent = data.errors.opening_cash_khr[0];
                        } else {
                            toastr.error(data.message || 'Failed to open shift.');
                            document.getElementById('shift_error').textContent = data.message || 'Check inputs and try again.';
                        }
                        throw new Error('Shift opening failed.');
                    }
                    
                    toastr.success(data.message);
                    updateShiftButtonUI(true); 
                    closeOpenModal();
                })
                .catch(error => {
                    console.error('Open Shift Error:', error);
                })
                .finally(() => {
                    saveOpenBtn.disabled = false;
                    saveOpenBtn.innerText = 'Submit';
                });
            });

            // --------------------------------------------------
            // 2. Handle Close Shift Form Submission 
            // --------------------------------------------------
            closeShiftForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveCloseBtn.disabled = true;
                saveCloseBtn.innerText = 'Closing...';
                
                document.getElementById('closing_usd_error').textContent = '';
                document.getElementById('closing_khr_error').textContent = '';

                fetch("{{ route('shift.close') }}", {
                    method: 'POST',
                    body: new FormData(this),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json().then(data => ({ status: response.status, data })))
                .then(({ status, data }) => {
                    if (status >= 400) {
                        // Handle Validation Errors
                        if (data.errors) {
                            if (data.errors.closing_cash_usd) document.getElementById('closing_usd_error').textContent = data.errors.closing_cash_usd[0];
                            if (data.errors.closing_cash_khr) document.getElementById('closing_khr_error').textContent = data.errors.closing_cash_khr[0];
                        } else {
                            toastr.error(data.message || 'Failed to close shift.');
                        }
                        throw new Error('Shift closing failed.');
                    }
                    
                    toastr.success(data.message);
                    updateShiftButtonUI(false); 
                    closeCloseModal();
                })
                .catch(error => {
                    console.error('Close Shift Error:', error);
                })
                .finally(() => {
                    saveCloseBtn.disabled = false;
                    saveCloseBtn.innerText = 'Confirm Close Shift';
                });
            });
        }
});


</script>