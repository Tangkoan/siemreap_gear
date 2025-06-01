@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform ">
            <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                <svg class="h-6 w-6 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Change Password
            </h2>

            <form method="post" action="{{ route('update.password') }}">
                @csrf
                {{-- Old Password --}}
                <div class="mb-4 relative"> {{-- Added relative for positioning the icon --}}
                    <label for="current_password" class="block text-gray-400 text-sm font-medium mb-2">
                        Old Password
                    </label>
                    <input type="password" id="current_password" name="old_password" required
                           class="input-field-custom w-full pr-10 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('old_password') is-invalid @enderror">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="current_password">
                        <svg class="h-5 w-5 text-gray-500 hover:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </span>
                    @error('old_password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-4 relative">
                    <label for="new_password" class="block text-gray-400 text-sm font-medium mb-2">
                        New Password
                    </label>
                    <input type="password" id="new_password" name="new_password" required
                           class="input-field-custom w-full pr-10 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('new_password') is-invalid @enderror">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="new_password">
                        <svg class="h-5 w-5 text-gray-500 hover:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </span>
                    @error('new_password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6 relative">
                    <label for="new_password_confirmation" class="block text-gray-400 text-sm font-medium mb-2">
                        Confirm Password
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                           class="input-field-custom w-full pr-10 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    {{-- Eye icon for show/hide --}}
                    <span class="absolute inset-y-0 right-0 top-7 pr-3 flex items-center cursor-pointer toggle-password" data-target="new_password_confirmation">
                        <svg class="h-5 w-5 text-gray-500 hover:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </span>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                        Update
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
                icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'); // Eye open
            } else {
                icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7A10.05 10.05 0 0112 5c.424 0 .84.037 1.246.109m 3.167 3.167a3 3 0 11-4.243 4.243m4.243-4.243a3 3 0 00-4.243 4.243M3 3l3.59 3.59m0 0a9.953 9.953 0 01.442-.442L21 21"></path>'); // Eye closed
            }
        });
    });
</script>

@endsection