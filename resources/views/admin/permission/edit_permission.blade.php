@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">
            <div
                class="lg:col-span-full bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                    </svg>
                    <span class="px-2">
                        <a href="{{ route('all.permission') }}"
                            class="">
                            
                            {{ __('messages.edit_permission') }}
                        </a>
                    </span>
                </h2>

                <form id="myForm" method="post" action="{{ route('permission.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $permission->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.permission_name') }}
                                </label>
                                <input value="{{ $permission->name }}" type="text" id="name" name="name" 
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') is-invalid @enderror">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="group_name"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.group_name') }}
                                </label>
                                <select name="group_name" id="example-select" 
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="" disabled>{{ __(key: 'messages.select_group_name') }}</option>
                                    @foreach([
                                            'pos',
                                            'customer',
                                            'supplier',
                                            'category',
                                            'product',
                                            
                                            'orders',
                                            'purchases',
                                            
                                            'roles',
                                            'report',
                                            'user',
                                            'permission',
                                            'backup'
                                        ] as $group)
                                            <option value="{{ $group }}" {{ $permission->group_name === $group ? 'selected' : '' }}>
                                                {{ ucfirst($group) }}
                                            </option>
                                    @endforeach
                                </select>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $('#myForm').validate({
                rules: {
                    name: { required: true },
                    group_name: { required: true }
                },
                 name: {
                        required: '{{ __('messages.please_enter_permission_name') }}',
                    },
                    group_name: {
                        required: '{{ __('messages.please_select_group_name') }}',
                    },

                // កន្លែងនេះត្រឹមត្រូវ
                messages: {
                    name: {
                        required: '{{ __('messages.please_enter_permission_name') }}',
                    },
                    group_name: {
                        required: '{{ __('messages.please_select_group_name') }}',
                    },
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
