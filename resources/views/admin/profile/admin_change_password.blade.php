@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-4 sm:p-6">
    <div class="grid grid-cols-1">

        {{-- Main Card Container --}}
        <div class="lg:col-span-full bg-white dark:bg-gray-900 rounded-lg shadow-md p-6 transition-colors duration-300">
            <h2 class="text-xl  text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <span>{{ __('messages.change_password') }}</span>
            </h2>

            <form method="post" action="{{ route('update.password') }}">
                @csrf

                {{-- Old Password --}}
                <div class="mb-4 relative">
                    <label for="current_password" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        {{ __('messages.old_password') }}
                    </label>
                    <input type="password" id="current_password" name="old_password"
                           class="w-full pr-10 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('old_password') border-red-500 @enderror">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="current_password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                    </span>
                    @error('old_password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-4 relative">
                    <label for="new_password" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        {{ __('messages.new_password') }}
                    </label>
                    <input type="password" id="new_password" name="new_password"
                           class="w-full pr-10 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('new_password') border-red-500 @enderror">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="new_password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                    </span>
                    @error('new_password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6 relative">
                    <label for="new_password_confirmation" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                        {{ __('messages.confirm_password') }}
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                           class="w-full pr-10 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="new_password_confirmation">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                    </span>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('messages.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.toggle-password').on('click', function() {
            const targetId = $(this).data('target');
            const passwordField = $('#' + targetId);
            const icon = $(this).find('svg');

            // Toggle the type attribute
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);

            // Toggle the eye icon
            if (type === 'password') {
                // Eye open icon SVG
                icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>');
            } else {
                // Eye closed (slashed) icon SVG
                icon.html('<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"</path>');
            }
        });
    });
</script>

@endsection
