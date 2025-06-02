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
                    <img class="h-9 w-9 rounded-full border-2 border-white object-cover" src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/'.$adminData->photo) : url('upload/no_image.jpg') }}" alt="User Profile">
                    
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
                    <div class="px-4 py-2 text-sm font-semibold "> <span class="text-blue-600">Dear</span> <span class="uppercase  text-purple-600">{{ $adminData->name }}</span></div>

                    <hr class="border-gray-200 dark:border-gray-700 my-1">
                    <a href="{{ route('admin.admin_profile_view') }}" class="flex items-center px-4 py-2 text-default hover:bg-gray-700 dark:hover:bg-gray-200">
                        <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        <span>My Account</span>
                    </a>

                    <hr class="border-gray-200 dark:border-gray-700 my-1">
                    <a href="{{ route('change.password') }}" class="flex items-center gap-2 px-4 py-2 text-default hover:bg-gray-700 dark:hover:bg-gray-200">
                        <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h5a1 1 0 001-1V4a1 1 0 00-1-1H3zm13 0a1 1 0 00-1 1v12a1 1 0 001 1h5a1 1 0 001-1V4a1 1 0 00-1-1h-5z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Change Password</span>
                    </a>

                    <hr class="border-gray-200 dark:border-gray-700 my-1">
                    <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-4 py-2 text-default hover:bg-gray-700 dark:hover:bg-gray-200">
                        <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h5a1 1 0 001-1V4a1 1 0 00-1-1H3zm13 0a1 1 0 00-1 1v12a1 1 0 001 1h5a1 1 0 001-1V4a1 1 0 00-1-1h-5z" clip-rule="evenodd"></path>
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