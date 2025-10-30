<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Http\Controllers\Auth;
// Import សម្រាប់ AdminDestroy()
use Illuminate\Http\Request;
// End Import សម្រាប់ AdminDestroy()

// Import សម្រាប់ Model User
use Illuminate\Support\Facades\Auth;
// End Import

// Import សម្រាប់ Hsah Password
use Illuminate\Support\Facades\Hash;
// Import សម្រាប់ Hsah Password

// Role For User
use Spatie\Permission\Models\Role;

// use end path

// In your AdminController.php
// For logging errorsឮ

class AdminController extends Controller
{
    //
    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = [
            'message' => __('messages.logout_successfully'),
            'alert-type' => 'info',
        ];

        return redirect('/')->with($notification);

        // return redirect('/');
    }

    // Start
    public function AdminLogoutPage()
    {

        $notification = [
            'message' => __('messages.login_successfully'),
            'alert-type' => 'info',
        ];

        // return redirect()->back()->with($notification);

        return view('admin.admin_logout')->with($notification);
    } // End Method

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);

        return view('admin.profile.admin_profile_view', compact('adminData'));

        // compact('adminData') គឺបានន័យថាវាទាញទិន្នន័យចេញ ពី user_id = 1 គឺទាញទិន្នន័យមកមួយ Record គឺលក្ខណះជា array associative ចំណុចហ្នឹងពិសេស Reserch បន្ថែម
    } // End Method

    public function AdminProfileStore(Request $request)
    {

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo'); // file ត្រង់នេះ គឺជាtypeនៃ admin_profile_view.blade
            @unlink(public_path('upload/admin_image/'.$data->photo));  // សម្រាប់កន្លែងនេះគឺគេ Replace រូបដោយមិនអោយរូបផ្សេងៗលោតមានច្រើន
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = [
            'message' => __('messages.profile_updated_successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    } // End Method

    public function ChangePassword(Request $request)
    {
        return view('admin.profile.admin_change_password');
    } // End Method

    public function UpdatePassword(Request $request)
    {
        // Validation
        $request->validate([

            'old_password' => 'required', // Use 'required' ផ្ទាល់
            'new_password' => 'required|confirmed', // Use 'required|confirmed'
        ]);

        // / Match The Old Password
        if (! Hash::check($request->old_password, auth::user()->password)) {

            $notification = [
                'message' => __('messages.old_password_doest_match'),
                'alert-type' => 'error',
            ];

            return back()->with($notification);
        }

        // // Update The New Password
        $request->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        $notification = [
            'message' => __('messages.password_change_success'),
            'alert-type' => 'success',
        ];

        return back()->with($notification);
    } // End Method

    // Setup Admin
    public function AllAdmin()
    {

        $alladminuser = User::latest()->get();

        return view('admin.admin.all_admin', compact('alladminuser'));
    } // End Method

    public function searchAdmin(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->search.'%');
            });
        }

        // 👉 កំណត់អោយចេញតាម created_at ថ្មីជាងគេ
        $query->orderBy('created_at', 'desc');

        $perPage = $request->perPage ?? 10; // ✅ Default = 10
        $isAll = $perPage === 'all';

        if ($isAll) {
            $users = $query->get();
        } else {
            $users = $query->paginate((int) $perPage);
        }

        $table = '';
        foreach ($users as $key => $item) {

            $table .= '
            <tr class="hover:bg-slate-50 border-b border-slate-200 dark:hover:bg-gray-700">
        <td class="p-2">'.($key + 1).'</td>
        
        <td class="p-2">
            <img src="'.(! empty($item->photo) ? asset('upload/admin_image/'.$item->photo) : asset('upload/no_image.jpg')).'" 
                 alt="Admin photo" 
                 class="rounded-md" 
                 style="width: 46px; height: 46px; object-fit: cover; object-position: center;" />
        </td>
    
        <td class="p-2">'.$item->name.'</td>
        <td class="p-2">'.($item->email ?? 'null').'</td>
        <td class="p-2">'.($item->phone ?? 'null').'</td> 
        
       <td class="p-2 text-sm text-primary font-semibold ">'.implode(', ', $item->getRoleNames()->toArray()).'</td>


        
        <td class="px-4 py-4 text-sm whitespace-nowrap">
            <div class="flex items-center gap-x-6 ">
            
                <button class="text-primary  transition-colors duration-200 dark:hover:text-primary-900  hover:text-primary-900 focus:outline-none">
                    <a href="'.route('edit.admin', $item->id).'" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                </button>
    
                <button type="button" class="icon-delete text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-red-500  hover:text-red-500 focus:outline-none">
                                    <a href="'.route('delete.admin', parameters: $item->id).'" id="delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    </a>
                        </button>
                        
                        
                        
                        
                        </div>
    
                        
                    </td>
                </tr>';

        }

        $pagination = $isAll ? '<div class="text-sm text-slate-500"></div>' : $users->links('pagination::tailwind')->toHtml();

        return response()->json([
            'table' => $table,
            'pagination' => $pagination,
        ]);
    } // End Method

    public function AddAdmin()
    {

        $roles = Role::all();

        return view('admin.admin.add_admin', compact('roles'));
    }// End Method

    public function StoreAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'roles' => 'required|string', // Ensure a role name is submitted
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        // ✅ ដំណោះស្រាយ៖ ប្រើឈ្មោះ Role ដោយផ្ទាល់ពី Request
        // មិនចាំបាច់ស្វែងរក Role ជាមុនទេ ព្រោះ Form បានបញ្ជូនឈ្មោះមកស្រាប់
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('all.admin')->with([
            'message' => __('messages.new_user_created_successfully'),
            'alert-type' => 'success',
        ]);
    }

    public function EditAdmin($id)
    {

        $roles = Role::all();
        $adminuser = User::findOrFail($id);

        return view('admin.admin.edit_admin', compact('roles', 'adminuser'));

    }// End Method

    public function UpdateAdmin(Request $request)
    {
        $admin_id = $request->id;

        $user = User::findOrFail($admin_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        // Remove existing roles
        $user->roles()->detach();

        // Assign new role if exists
        if ($request->roles) {
            $role = Role::findById($request->roles); // ដាក់ role ID នៅទីនេះ
            $user->assignRole($role->name); // បម្លែងទៅជា name មុន
        }

        $notification = [
            'message' => __('messages.user_updated_successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->route('all.admin')->with($notification);
    }

    public function DeleteAdmin($id)
    {

        $user = User::findOrFail($id);
        if (! is_null($user)) {
            $user->delete();
        }

        $notification = [
            'message' => __('messages.user_delete_successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);

    }// End Method

    // ============================= Admin Dashboard =============================

    public function dashboard()
    {
        $currentMonth = Carbon::now()->format('F Y');
        $thisMonthSales = Order::whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year)
            ->sum('total');

        $lastMonthSales = Order::whereMonth('order_date', Carbon::now()->subMonth()->month)
            ->whereYear('order_date', Carbon::now()->subMonth()->year)
            ->sum('total');

        // Calculate percentage growth
        $growth = 0;
        if ($lastMonthSales > 0) {
            $growth = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        }

        return view('admin.dashboard', compact('currentMonth', 'thisMonthSales', 'lastMonthSales', 'growth'));
    }
}
