@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- អ្នកត្រូវតែមាន jquery.validate.min.js ដើម្បីឲ្យ validation ដំណើរការ --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 ">
            <div class="bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 transition-all duration-300">
                <h2 class="dark:text-white text-2xl  text-gray-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <span>{{ __('messages.add_roles_permission') }}</span>
                </h2>

                <form id="myForm" method="post" action="{{ route('role.permission.store') }}">
                    @csrf

                    <div class="mb-6">
                        <div class="form-group">
                            <label for="role_id" class="block dark:text-white text-gray-700 text-sm font-medium mb-2">
                                {{ __('messages.roles') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="role_id" id="role_id" class="dark:text-white dark:bg-gray-900 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="" disabled selected>{{ __('messages.select_roles') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-6 flex items-center gap-3">
                        {{-- កែតម្រូវ: id និង for ត្រូវតែដូចគ្នា --}}
                        <input type="checkbox" id="checkAll" 
                            class="h-5 w-5 rounded bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-gray-800 dark:text-blue-500">
                        <label for="checkAll" class="text-gray-700 text-sm dark:text-white">{{ __('messages.all_permission') }}</label>
                    </div>

                    <hr class="border-gray-300 mb-6">

                    {{-- 
                        !!! ការព្រមានសំខាន់ !!!
                        កូដខាងក្រោមនេះនៅតែមានបញ្ហា N+1 Query។ 
                        សូមកែវានៅក្នុង Controller របស់អ្នកដើម្បីឲ្យកម្មវិធីដំណើរការលឿន។
                    --}}
                    @foreach ($permission_groups as $group)
                        @php
                            $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                        @endphp
                        <fieldset class="mb-6 border border-gray-200 p-4 rounded-md">
                            <legend class="text-sm text-black  mb-4 dark:text-white">
                                {{-- កែតម្រូវ: ត្រូវបញ្ជាក់ឈ្មោះไฟล์ 'messages.' --}}
                                {{ __('messages.' . $group->group_name) }}
                            </legend>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    {{-- កែតម្រូវ: ដកដកឃ្លាចេញពី for --}}
                                    <label for="perm_{{ $permission->id }}" class="dark:text-white flex items-center gap-3 text-gray-700 text-sm">
                                        <input type="checkbox" name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
    class="h-5 w-5 rounded bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-gray-800 dark:text-blue-500">
                                        {{-- កែតម្រូវ: ត្រូវបញ្ជាក់ឈ្មោះไฟล์ 'messages.' --}}
                                        {{ __('messages.' . $permission->name) }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach

                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition-all duration-200">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // កែតម្រូវ: ប្រើ id ថ្មី 'checkAll'
        $('#checkAll').on('click', function() {
            $('input[type=checkbox]').prop('checked', this.checked);
        });

        $(document).ready(function () {
            $('#myForm').validate({
                rules: {
                    role_id: { required: true },
                },
                messages: {
                    role_id: { required: '{{ __('messages.please_select_role_name') }}' },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('text-red-500 text-xs mt-1'); // ប្រើ class របស់ Tailwind
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('border-red-500'); // ប្រើ class របស់ Tailwind
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('border-red-500'); // ប្រើ class របស់ Tailwind
                },
            });
        });
    </script>
@endsection