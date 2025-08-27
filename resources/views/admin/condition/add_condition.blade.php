@extends('admin/admin_dashboard')
@section('admin')

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">
            <div class="lg:col-span-full bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 transition-colors duration-300 transform">
                <h2 class="text-xl  text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{-- ✅ Title --}}
                    <span>{{ __('messages.add_condition') }}</span>
                </h2>

                {{-- ✅ Form action points to condition.store route --}}
                <form method="post" action="{{ route('condition.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        <div class="space-y-4">
                            {{-- Condition Name --}}
                            <div>
                                <label for="condition_name" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{-- ✅ Label --}}
                                    {{ __('messages.condition_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="condition_name" name="condition_name" class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                            bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                            @error('condition_name') border-red-500 ring-red-500 @enderror" />
                                
                                {{-- ✅ Validation error --}}
                                <x-input-error :messages="$errors->get('condition_name')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md shadow-lg transition-colors duration-200">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection