@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">
            <div class="bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 transition-all duration-300">
                <h2 class="text-2xl  text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <a href="{{ route('all.roles.permission') }}">
                        Edit Roles Permission
                    </a>
                </h2>

                <form id="myForm" method="post" action="{{ route('role.permission.update', $role->id) }}">
                    @csrf

                    {{-- Role Name --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                            Role Name:
                            <span class="text-red-500 text-xl dark:text-red-400">{{ $role->name }}</span>
                        </label>
                    </div>

                    {{-- All Permissions Toggle --}}
                    <div class="mb-6 flex items-center gap-3">
                        <input type="checkbox" id="checkAllPermissions"
                            class="h-5 w-5 rounded bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-gray-800 dark:text-blue-500">
                        <label for="checkAllPermissions" class="text-gray-700 dark:text-gray-200 text-sm">All
                            Permissions</label>
                    </div>

                    <hr class="border-gray-300 dark:border-gray-700 mb-6">

                    {{-- Permission Groups --}}
                    @foreach ($permission_groups as $group)
                        @php
                            $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                        @endphp

                        <fieldset class="mb-6 border border-gray-200 dark:border-gray-700 p-4 rounded-md">
                            <legend class="text-sm text-gray-800 dark:text-gray-100  mb-4">
                                {{ $group->group_name }}
                            </legend>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach ($permissions as $permission)
                                    <label for="perm_{{ $permission->id }}"
                                        class="flex items-center gap-2 text-gray-700 dark:text-gray-200 text-sm">
                                        <input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                            id="perm_{{ $permission->id }}" {{ App\Models\User::roleHasPermissions($role, $permission) ? 'checked' : '' }}
                                            class="h-5 w-5 rounded bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-gray-800 dark:text-blue-500">
                                        {{ $permission->name }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach

                    <div class="text-right">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition-all duration-200">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#checkAllPermissions').on('click', function () {
            const isChecked = $(this).is(':checked');
            $('input[name="permission[]"]').prop('checked', isChecked);
        });
    </script>

@endsection