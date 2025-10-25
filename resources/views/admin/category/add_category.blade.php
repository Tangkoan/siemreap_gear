@extends('admin/admin_dashboard')
@section('admin')

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div
                class="lg:col-span-full bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-xl p-6 transition-colors duration-300 transform">
                <h2 class="text-xl  text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-2.25-1.313M21 7.5v2.25m0-2.25-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3 2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75 2.25-1.313M12 21.75V19.5m0 2.25-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                    </svg>
                    <span>{{ __('messages.add_category') }}</span>
                </h2>

                <form method="post" action="{{ route('category.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Category Name --}}
                            <div>
                                <label for="category_name"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.category_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="category_name" name="category_name"  class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                           @error('category_name') border-red-500 ring-red-500 @enderror" />

                                <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md shadow-lg transition-colors duration-200">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection