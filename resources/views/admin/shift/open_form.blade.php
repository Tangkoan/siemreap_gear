@extends('admin/admin_dashboard')
@section('admin')

<div class="w-full p-4 pt-6 mx-auto md:p-6">
    <div class="container-fluid">

        {{-- =================================== --}}
        {{-- START: Page Title & Breadcrumb --}}
        {{-- =================================== --}}
        <div class="flex flex-col items-start justify-between mb-6 sm:flex-row sm:items-center">
            <div>
                {{-- ✅ បានកែប្រែ --}}
                <h4 class="text-2xl font-semibold text-default">{{ __('messages.open_new_shift') }}</h4>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{-- ✅ បានកែប្រែ --}}
                    {{ __('messages.start_daily_sales_session') }}
                </p>
            </div>
            <div class="mt-2 text-sm sm:mt-0">
                <ol class="flex items-center space-x-1 text-gray-500 dark:text-gray-400">
                    {{-- ✅ បានកែប្រែ --}}
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">{{ __('messages.dashboard') }}</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    {{-- ✅ បានកែប្រែ (ប្រើ Key ដែលមានស្រាប់ពី Header) --}}
                    <li class="font-medium text-default">{{ __('messages.open_shift') }}</li>
                </ol>
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END: Page Title & Breadcrumb --}}
        {{-- =================================== --}}


        {{-- =================================== --}}
        {{-- START: Main Form Card --}}
        {{-- =================================== --}}
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="overflow-hidden shadow-lg card-dynamic-bg rounded-xl">
                    
                    <div class="p-6 md:p-8">

                        <div class="text-center">
                            {{-- ✅ បានកែប្រែ --}}
                            <h4 class="mb-2 text-2xl font-semibold text-default">{{ __('messages.start_your_shift') }}</h4>
                            <p class="mb-6 text-gray-600 dark:text-gray-400">
                                {{-- ✅ បានកែប្រែ --}}
                                {{ __('messages.enter_starting_cash_prompt') }}
                            </p>
                        </div>
                        
                        {{-- បង្ហាញ Error ពី Middleware (Styled with Tailwind) --}}
                        @if (session('error'))
                            <div class="relative px-4 py-3 mb-6 text-red-700 bg-red-100 border border-red-400 rounded-lg dark:bg-red-900/30 dark:border-red-600 dark:text-red-300" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('shift.open') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="starting_cash" class="block mb-1 text-sm font-medium text-default">
                                    {{-- ✅ បានកែប្រែ --}}
                                    {{ __('messages.starting_cash_usd') }}
                                </label>
                                
                                <input class="block w-full px-3 py-2 border rounded-lg shadow-sm bg-inherit text-default card-dynamic-bg border-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary
                                        @error('starting_cash') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       type="number" 
                                       name="starting_cash" 
                                       id="starting_cash" 
                                       value="{{ old('starting_cash', 0) }}" 
                                       step="0.01" 
                                       required 
                                       autofocus>
                                
                                @error('starting_cash')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="pt-2">
                                <button class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition duration-150 ease-in-out bg-primary border border-transparent rounded-lg shadow-sm  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" type="submit">
                                    {{-- ✅ បានកែប្រែ --}}
                                    {{ __('messages.start_shift_and_go_to_pos') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END: Main Form Card --}}
        {{-- =================================== --}}

    </div> </div>
@endsection