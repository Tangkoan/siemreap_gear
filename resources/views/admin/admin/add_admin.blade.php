@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">
            <div
                class="lg:col-span-full bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl  text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <span class="px-2">
                        <a href="{{ route('all.admin') }}">
                            {{ __(key: 'messages.add_user') }}
                        </a>
                    </span>
                </h2>

                <form id="myForm" method="post" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">

                            {{-- Name --}}
                            <div class="form-group">
                                <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Role --}}
                            <div class="form-group">
                                <label for="roles" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.roles') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="roles" id="roles"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>{{ __(key: 'messages.select_roles') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.phone') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="phone" name="phone"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">

                            {{-- Password --}}
                            <div class="form-group relative mb-4">
                                <label for="current_password"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.password') }}
                                </label>
                                <input type="password" id="current_password" name="password"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') is-invalid @enderror">

                                {{-- Eye Icon --}}
                                <span class="absolute right-3 top-9 cursor-pointer toggle-password"
                                    data-target="current_password">
                                    <svg class="h-5 w-5 text-gray-500 hover:text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>

                                @error('password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.email') }}
                                </label>
                                <input type="email" id="email" name="email"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                                
                                {{-- ✅ THIS IS THE FIX ✅ --}}
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            {{ __(key: 'messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Image Preview & Validation Script (No changes needed here) --}}
    <script>
        $(document).ready(function () {
            $('#photo').on('change', function (event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const preview = $('#photo_preview');
                    const imageUrl = URL.createObjectURL(file);
                    preview.attr('src', imageUrl).removeClass('hidden');
                    preview.on('load', function () {
                        URL.revokeObjectURL(imageUrl);
                    });
                } else {
                    $('#photo_preview').addClass('hidden').attr('src', '#');
                }
            });

            // Password Toggle
            $('.toggle-password').on('click', function () {
                const targetId = $(this).data('target');
                const passwordField = $('#' + targetId);
                const icon = $(this).find('svg');

                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
            });

            // Validation
            $('#myForm').validate({
                rules: {
                    name: { required: true },
                    email: { required: true },
                    phone: { required: true },
                    password: { required: true },
                    roles: { required: true }
                },
                messages: {
                    name: { required: '{{ __('messages.user_name') }}', },
                    email: { required: '{{ __('messages.please_enter_email') }}' },
                    phone: { required: '{{ __('messages.please_enter_phone') }}' },
                    password: { required: '{{ __('messages.please_enter_password') }}' },
                    roles: { required: '{{ __('messages.please_enter_rolesl') }}' },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('text-red-500 text-sm');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('border-red-500');
                },
                unhighlight: function (element) {
                    $(element).removeClass('border-red-500');
                }
            });
        });
    </script>

@endsection