<header class="header-bg text-white p-2 shadow-s transition-colors duration-300">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center px-2">
            <b>
                <div class="text-black text-2xl">Siem Reap Gear</div>
            </b>
        </a>
        
        <div class="flex items-center space-x-4">
            <button class="color-primary text-white font-semibold py-2 px-4 rounded-full shadow-md transition-colors duration-200 hidden md:block">POS</button>

            {{-- កុំប៉ះពាល់ត្រង់នេះ --}}
            <div class="hidden">
                <input type="hidden" id="theme-toggle" >
                <label for="theme-toggle" class="toggle-switch-label hidden" role="switch" aria-checked="false" tabindex="0">
                    
                </label>
            </div>
            {{-- កុំប៉ះពាល់ត្រង់នេះ --}}



            @php
$id = Auth::user()->id;
$adminData = App\Models\User::find($id);
            @endphp


            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center space-x-2 text-white focus:outline-none">
                    <img class="h-9 w-9 rounded-full border-2 border-white object-cover" src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}" alt="User Profile">
                    
                    <span class="font-medium ttext-xl hidden md:block text-black">{{ $adminData->name }}</span>
                    <svg class="h-4 w-4 hidden md:block" fill="black" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200 transform" 
                     x-transition:enter-start="opacity-0 -translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150 transform" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="absolute right-0 mt-2 w-48 card-bg rounded-md shadow-lg py-1 z-10 origin-top-right">
                    <div class="px-4 py-2 text-sm font-semibold gap-2"> <span class="text-black text-xs">Dear</span> <span class="uppercase  text-black text-xs">{{ $adminData->name }}</span></div>

                    <hr class="border-gray-200 dark:border-gray-700 my-1">
                    <a href="{{ route('admin.admin_profile_view') }}" class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-black dark:hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                          
                        <span>My Account</span>
                    </a>

                  
                    <a href="{{ route('change.password') }}" class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-black dark:hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                          
                        <span>Change Password</span>
                    </a>

                    <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-4 py-2 text-xs hover:bg-black dark:hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                            class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                          
                        <span>Logout</span>
                    </a>
                    
                </div>
            </div>

            <button id="menu-button" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </div>
</header>