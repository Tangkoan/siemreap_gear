@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 ">
            <div class="dark:bg-gray-900 bg-white rounded-lg shadow-md p-6 transition-all duration-300">
                <h2 class="dark:text-white text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <a href="{{ route('all.roles') }}">Add Roles Permission</a>
                </h2>

                <form id="myForm" method="post" action="{{ route('role.permission.store') }}">
                    @csrf

                    {{-- Group Name --}}
                    <div class="mb-6">
                        <label for="group_name" class="block dark:text-white text-gray-700 text-sm font-medium mb-2">
                            Group Name <span class="text-red-500">*</span>
                        </label>
                        <select name="role_id" id="role_id" required
                            class="dark:text-white dark:bg-gray-800 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="" disabled selected>Select Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- All Permissions Toggle --}}
                    <div class="mb-6 flex items-center gap-3">
                        <input type="checkbox" value="" id="customckeck1"
                            class="h-5 w-5 rounded border-gray-400 text-blue-600 focus:ring-blue-500">
                        <label for="all_permission" class="text-gray-700 text-sm dark:text-white">All Permissions</label>
                    </div>

                    <hr class="border-gray-300 mb-6">

                    {{-- Permission Groups --}}
                    @foreach ($permission_groups as $group)
                        @php
                            $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                        @endphp

                        <fieldset class="mb-6 border border-gray-200 p-4 rounded-md">
                            <legend class="text-sm text-black font-semibold mb-4 dark:text-white">{{ $group->group_name }}
                            </legend>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($permissions as $permission)
                                    <label for=" perm_{{ $permission->id }}"
                                        class="dark:text-white flex items-center gap-3 text-gray-700 text-sm">
                                        <input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                            id="perm_{{ $permission->id }}"
                                            class="dark:text-white h-5 w-5 rounded border-gray-400 text-blue-600 focus:ring-blue-500">
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



    <script type="text/javascript">
        // Check/uncheck all permission checkboxes when 'All Permission' is clicked
        $('#customckeck1').on('click', function() {
            const isChecked = $(this).is(':checked');
            $('input[type=checkbox][name="permission[]"]').prop('checked', isChecked);
        });
    </script>
@endsection
