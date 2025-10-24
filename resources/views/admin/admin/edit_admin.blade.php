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
                        <a href="{{ route('all.admin') }}" >
                            {{ __(key: 'messages.edit_user') }}
                        </a>
                    </span>
                </h2>

                <form id="myForm" method="post" action="{{ route('admin.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $adminuser->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Name --}}
                            <div class="form-group">
                                <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ $adminuser->name }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Role --}}
                            <div class="form-group">
                                <label for="roles" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.roles') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="roles" id="roles"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option disabled>{{ __(key: 'messages.select_roles') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $adminuser->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.email') }}
                                </label>
                                <input type="email" id="email" name="email" value="{{ $adminuser->email }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.phone') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ $adminuser->phone }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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

    <script>
        $(document).ready(function () {
            $('#myForm').validate({
                rules: {
                    name: { required: true },
                    email: { required: true },
                    phone: { required: true },
                    roles: { required: true }
                },
                messages: {
                    name: { required: '{{ __('messages.user_name') }}', },
                    email: { required: '{{ __('messages.please_enter_email') }}' },
                    phone: { required: '{{ __('messages.please_enter_phone') }}' },
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