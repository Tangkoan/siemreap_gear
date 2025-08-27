<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class RoleController extends Controller
{
    //
    public function AllPermission()
    {
        $permissions = Permission::all();
        return view('admin.permission.all_permission', compact('permissions'));
    } // End Method 

    public function AddPermission()
    {

        return view('admin.permission.add_permission');
    } // End Method 


    public function StorePermission(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:permissions,name',
                'group_name' => 'required',
            ],
            [
                'name.required' => __('messages.please_enter_permission_name'),
                'name.unique' => __('messages.permission_name_already_exists'),
                'group_name.required' => __('messages.please_select_group_name'),
            ]
        );



        $role = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => __('messages.permission_added_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
    }

    public function EditPermission($id)
    {

        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit_permission', compact('permission'));
    } // End Method 


    public function UpdatePermission(Request $request)
    {
        $per_id = $request->id;

        $request->validate(
            [
                'name' => 'required|unique:permissions,name,' . $per_id,
                'group_name' => 'required',
            ],
            [
                'name.required' => __('messages.please_enter_permission_name'),
                'name.unique' => __('messages.permission_name_already_exists'),
                'group_name.required' => __('messages.please_select_group_name'),
            ]
        );

        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = [
            'message' => __('messages.permission_updated_successfully'),
            'alert-type' => 'success'
        ];

        return redirect()->route('all.permission')->with($notification);
    }

    public function DeletePermission($id)
    {
        
        $permission = Permission::with('roles')->findOrFail($id);

        // ត្រួតពិនិត្យថាតើ Permission នេះមានជាប់ទាក់ទងនឹង Role ណាមួយទេ
        if ($permission->roles->count() > 0) {
            // ប្រសិនបើមាន Role កំពុងប្រើ 
            $notification = array(
                'message' => __('messages.permission_in_use_error'), // "មិនអាចលុបបានទេ! Permission នេះកំពុងត្រូវបានប្រើប្រាស់។"
                'alert-type' => 'warning' // ប្តូរទៅជា 'error' ឬ 'warning'
            );

            return redirect()->back()->with($notification);
        }

        // ប្រសិនបើគ្មាន Role  nàoប្រើទេ (count ស្មើ 0) -> ទើបអនុញ្ញាតឱ្យលុប
        $permission->delete();

        $notification = array(
            'message' => __('messages.permission_deleted_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

    public function searchPermission(Request $request)
    {
        $query = Permission::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('group_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        if ($isAll) {
            $permissions = $query->get();
        } else {
            $permissions = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($permissions as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                
                <td class="p-4 py-5">' . $item->name . '</td>
                <td class="p-4 py-5">' . $item->group_name . '</td>
                
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    
                <button class="icon-edit dark:hover:text-blue-900  hover:text-blue-900  transition-colors duration-200  focus:outline-none">
                                <a href="' . route('edit.permission', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                </a>
                    </button>

                    
                            
                <button class="icon-delete  transition-colors duration-200 dark:hover:text-red-900  hover:text-red-900 focus:outline-none">
                                <a href="' . route('delete.permission', parameters: $item->id) . '" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                </a>
                    </button>
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $permissions->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }


    ///  ========================================== Start Setup Role ========================================== 
    public function AllRoles()
    {

        $roles = Role::all();
        return view('admin.role.all_roles', compact('roles'));
    } // End Method 


    public function AddRoles()
    {

        return view('admin.role.add_roles');
    } // End Method 


    public function EditRoles($id)
    {

        $roles = Role::findOrFail($id);
        return view('admin.role.edit_roles', compact('roles'));
    } // End Method 

    public function UpdateRoles(Request $request)
    {

        $role_id = $request->id;

        $request->validate(
            [
                'name' => 'required|unique:roles,name,' . $role_id,
            ],
            [
                'name.required' => __('messages.please_enter_permission_name'),
                'name.unique' => __('messages.roles_name_already_exists'),
            ]
        );

        Role::findOrFail($role_id)->update([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => __('messages.role_updated_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
    } // End Method 

    public function DeleteRoles($id)
    {
        // 1. ស្វែងរក Role នោះ
        $role = Role::findOrFail($id);

        // 2. ពិនិត្យមើលថាតើ Role នេះមាន User ប្រើប្រាស់ដែរឬទេ
        // យើងប្រើ ->users()->count() ដើម្បីរាប់ចំនួន User ដែលមាន Role នេះ
        if ($role->users()->count() > 0) {

            // ប្រសិនបើមាន User ប្រើប្រាស់ សូមកុំលុប និងបង្ហាញសារ Error
            $notification = array(
                'message' => __('messages.cannot_delete_this_roles_have_user_use_this_role'),
                'alert-type' => 'error' // ប្រើ 'error' ឬ 'warning'
            );
            return redirect()->back()->with($notification);
        }
        // 3. ប្រសិនបើគ្មាន User ប្រើប្រាស់ទេ ទើបអនុញ្ញាតឲ្យលុប
        $role->delete();

        $notification = array(
            'message' => __('messages.role_deleted_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 

    public function StoreRoles(Request $request)
    {

        

        $request->validate(
            [
                'name' => 'required|unique:roles,name',
            ],
            [
                'name.required' => __('messages.please_enter_permission_name'),
                'name.unique' => __('messages.roles_name_already_exists'),
            ]
        );

        $role = Role::create([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => __('messages.role_added_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
    } // End Method 

    public function searchRoles(Request $request)
    {
        $query = Role::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        if ($isAll) {
            $roles = $query->get();
        } else {
            $roles = $query->paginate((int)$perPage);
        }

        $table = '';
        foreach ($roles as $key => $item) {
            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
                <td class="p-4 py-5">' . ($key + 1) . '</td>
                
                
                <td class="p-4 py-5">' . $item->name . '</td>
                
                <td class="p-4 py-5">' . date('d/m/Y', strtotime($item->created_at)) . '</td>
                
                
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    
                <button class="icon-edit dark:hover:text-blue-900  hover:text-blue-900  transition-colors duration-200  focus:outline-none">
                                <a href="' . route('edit.roles', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                </a>
                    </button>

                    
                            
                <button class="icon-delete  transition-colors duration-200 dark:hover:text-red-900  hover:text-red-900 focus:outline-none">
                                <a href="' . route('delete.roles', parameters: $item->id) . '" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                </a>
                    </button>
                    
                    
                    
                    
                    </div>

                    
                </td>
            </tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $roles->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }

    /// End

// ========================================== Add Roles Permission All Method ========================================== 
    
    public function AddRolesPermission()
    {

        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.role.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    } // End Method 


    public function StoreRolesPermission(Request $request)
    {
        // ពិនិត្យមើលថាតើ Role ID នេះមាន role_has_permissions ហើយឬនៅ
        $exists = DB::table('role_has_permissions')->where('role_id', $request->role_id)->exists();

        if ($exists) {
            $notification = array(
                'message' => __('messages.permission_roler_already'),
                'alert-type' => 'error'
            );

            

            return redirect()->back()->with($notification);
        }

        // បើមិនទាន់មានទេ ទើបអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យ
        $data = array();
        $permissions = $request->permission;

        foreach ($permissions as $key => $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);
        }

        $notification = array(
            'message' => __('messages.role_permission_added_successfully'),
            
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles.permission')->with($notification);

    } // End Method 

    public function AllRolesPermission()
    {

        $roles = Role::all();
        return view('admin.role.all_roles_permission', compact('roles'));
    } // End Method


    public function searchRolesPermission(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10;
        $isAll = $perPage === 'all';

        $roles = $isAll ? $query->get() : $query->paginate((int) $perPage);

        $table = '';
        foreach ($roles as $key => $item) {
            // $permissionTags = '';
            // foreach ($item->permissions as $perm) {
            //     $permissionTags .= '<span class="inline-block icon-add text-white text-l px-2 py-1 rounded-md mr-1 mb-1 text-center align-middle ">' . $perm->name . '</span>';
            // }
            $permissionTags = '';
            $permissionCount = 0; // 1. Initialize a counter for permissions
            $groupedPermissions = [];

            // Loop through each permission for the current role
            foreach ($item->permissions as $index => $perm) {
                // Append the permission tag span
                $permissionTags .= '<span class="inline-block  bg-red-600 text-white    text-xs  px-2.5 py-1 rounded-md mr-3 mb-1">' . $perm->name . '</span>';
                $permissionCount++; // 2. Increment the counter

                // 3. Check if the counter is a multiple of 8 and it's not the last permission
                if ($permissionCount % 8 === 0 && $index < count($item->permissions) - 1) {
                    $permissionTags .= '<br class="mb-1">'; // 4. Add a line break with a small bottom margin
                }
            }

            $table .= '<tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">'
                . '<td class="dark:text-white p-4 py-5  text-sm text-slate-800">' . ($key + 1) . '</td>'
                . '<td class="dark:text-white  p-4 py-5 text-sm text-black">' . $item->name . '</td>'
                . '<td  class=" text-sm">' . $permissionTags . '</td>'
                . '<td class="dark:text-white  px-4 py-4 text-sm whitespace-nowrap">'
                . '<div class="flex items-center gap-x-6">'

                . '<td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center gap-x-6">
                    
                <button class="icon-edit  transition-colors duration-200 dark:hover:text-blue-900  hover:text-blue-900 focus:outline-none">
                                <a href="' . route('admin.edit.roles', $item->id) . '" >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                </a>
                    </button>

                    
                            
                <button class="icon-delete  transition-colors duration-200 dark:hover:text-red-900  hover:text-red-900 focus:outline-none">
                                <a href="' . route('admin.delete.roles', parameters: $item->id) . '" id="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                </a>
                    </button>
                    
                    
                    
                    
                    </div>

                    
                </td>'

                . '</div>'
                . '</td>'
                . '</tr>';
        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500">Showing all results</div>' : $roles->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination
        ]);
    }

    public function AdminEditRoles($id)
    {

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.role.edit_roles_permission', compact('role', 'permissions', 'permission_groups'));
    } // End Method 

    // ដើរ ពេលEdit ទោះមិនបញ្ចូល Role ក៏ Success
    public function RolePermissionUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissionIds = $request->permission ?? [];

        // ផ្លាស់ប្តូរពី ID ទៅជា Permission Object
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        $role->syncPermissions($permissions);

        $notification = [
            'message' => __('messages.role_permission_updated_successfully'),
            'alert-type' => 'success'
        ];

        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function AdminDeleteRoles($id)
    {
        // ស្វែងរក Role នោះ
        $role = Role::findOrFail($id);

        // ពិនិត្យមើលថាតើ Role នេះមាន User ប្រើឬអត់
        // $role->users->count() គឺដំណើរការដោយសារ Relationship ដែល Spatie បានបង្កើត
        if ($role->users->count() > 0) {
            
            // បើមាន User កំពុងប្រើ Roleមួយហ្នឹង, បង្កើតសារ Error ហើយត្រឡប់ទៅវិញ
            $notification = array(
                'message' => __('messages.cannot_delete_this_role'). $role->users->count() . ' user(s).',
                'alert-type' => 'error' // ប្រភេទ Alert គឺ error
            );

            return redirect()->back()->with($notification);
        }

        // បើគ្មាន User ប្រើRoleហ្នឹងទេ, ទើបអនុញ្ញាតឱ្យលុប
        $role->delete();

        $notification = array(
            'message' => __('messages.role_deleted_successfully'),
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

}
