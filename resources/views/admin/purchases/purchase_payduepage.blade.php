@extends('admin/admin_dashboard')
@section('admin')

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            {{-- The main card container --}}
            {{-- I've replaced 'card-bg' with standard Tailwind classes for background colors in both light and dark modes. --}}
            <div class="lg:col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 transition-all duration-300">
                
                {{-- Header section --}}
                {{-- Replaced 'text-default' with classes that adjust text color for light/dark modes. --}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                    <span>{{ __('messages.purchase_pay_due_amount') }}</span>
                </h2>

                <div>
                    <form method="post" action="{{ route('purchase.update.due') }}">
                        @csrf

                        {{-- Hidden fields do not need styling, so classes have been removed. --}}
                        <input type="hidden" name="id" value="{{ $purchasepaydue->id }}">
                        <input type="hidden" name="pay" value="{{ $purchasepaydue->pay }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                
                                {{-- Pay Now Field --}}
                                <div>
                                    {{-- Label text color adjusted for better visibility in both modes. --}}
                                    <label for="due" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                        {{ __('messages.pay_now') }}
                                    </label>

                                    {{-- Input field now has distinct styles for light and dark modes. --}}
                                    <input type="number" min="0" step="0.01" value="{{ $purchasepaydue->due }}" id="due" name="due"
                                        required 
                                        class="w-full py-2.5 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                               border  bg-white text-gray-900 
                                                dark:text-gray-200
                                               @error('due') border-red-500 dark:border-red-500 @enderror">

                                    <x-input-error :messages="$errors->get('due')" class="mt-2" />
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            {{-- Replaced 'button-blue' with standard Tailwind classes for a consistent look. --}}
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
