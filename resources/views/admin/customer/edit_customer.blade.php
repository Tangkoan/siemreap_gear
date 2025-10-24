@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6  dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1">

            <div
                class="lg:col-span-full rounded-lg shadow-xl p-6 bg-gray-100 dark:bg-gray-900 transition-all duration-300 transform">
                <h2 class="text-xl  text-gray-800 dark:text-gray-100 mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <div class="px-2">{{ __('messages.edit_customer') }}</div>
                </h2>

                <div>
                    <form method="post" action="{{ route('customer.update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                {{-- Customer Name --}}
                                <div>
                                    <label for="name"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                        {{ __('messages.customer_name') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ $customer->name }}"
                                        class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                           @error('name') border-red-500 ring-red-500 @enderror" />

                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                {{-- Customer Address --}}
                                <div>
                                    <label for="address"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                        {{ __('messages.customer_address') }}
                                    </label>
                                    <input type="text" id="address" name="address" value="{{ $customer->address }}"
                                        class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>

                            {{-- Column 2 --}}
                            <div class="space-y-4">
                                {{-- Notes --}}
                                <div>
                                    <label for="notes"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                        {{ __('messages.notes') }}
                                    </label>
                                    <input type="text" id="notes" name="notes" value="{{ $customer->notes }}"
                                        class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                {{-- Customer Phone --}}
                                <div>
                                    <label for="phone"
                                        class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                        {{ __('messages.customer_phone') }}
                                    </label>
                                    <input type="number" id="phone" name="phone" value="{{ $customer->phone }}"
                                        class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                {{ __('messages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#image').on('change', function (event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file));
                    preview.removeClass('hidden');
                    preview.on('load', function () {
                        URL.revokeObjectURL(preview.attr('src')); // free memory
                    })
                } else {
                    $('#image_preview').addClass('hidden').attr('src', '#');
                }
            });
        });
    </script>

@endsection