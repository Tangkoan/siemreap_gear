<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

// use App\Http\Controllers\Auth;
// Import សម្រាប់ AdminDestroy()
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
// End Import សម្រាប់ AdminDestroy()

// Import សម្រាប់ Model User
use App\Models\User;
// End Import 

// Import សម្រាប់ Hsah Password
use Illuminate\Support\Facades\Hash;
// Import សម្រាប់ Hsah Password


class AdminController extends Controller
{
    //
    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'info'
        );


        return redirect('/')->with($notification);

        // return redirect('/');
    }

    // Start
    public function AdminLogoutPage(){

        $notification = array(
            'message' => 'Login Successfully',
            'alert-type' => 'info'
        );

        // return redirect()->back()->with($notification);

        return view('admin.admin_logout')->with($notification);
    }// End Method
    
    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.profile.admin_profile_view', compact('adminData'));

        // compact('adminData') គឺបានន័យថាវាទាញទិន្នន័យចេញ ពី user_id = 1 គឺទាញទិន្នន័យមកមួយ Record គឺលក្ខណះជា array associative ចំណុចហ្នឹងពិសេស Reserch បន្ថែម
    }// End Method

    public function AdminProfileStore(Request $request){
        
        $id = Auth::user()->id;
        $data = User::find($id);
        $data -> name = $request->name;
        $data -> email = $request->email;
        $data -> phone = $request->phone;

        if($request->file('photo')){
            $file = $request->file('photo'); // file ត្រង់នេះ គឺជាtypeនៃ admin_profile_view.blade
            @unlink(public_path('upload/admin_image/'.$data->photo));  // សម្រាប់កន្លែងនេះគឺគេ Replace រូបដោយមិនអោយរូបផ្សេងៗលោតមានច្រើន
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'),$filename);
            $data['photo'] = $filename;
        }
        
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method


    public function ChangePassword(Request $request){
        return view('admin.profile.admin_change_password');
    }// End Method

    public function UpdatePassword(Request $request){
        // Validation
        $request->validate([

            'old_password' => 'required', // Use 'required' ផ្ទាល់
            'new_password' => 'required|confirmed', // Use 'required|confirmed'
        ]);

        /// Match The Old Password
        if(!Hash::check($request->old_password, auth::user()->password)){
            
            $notification = array(
            'message' => 'Old Password Donest Match!!!',
            'alert-type' => 'error'
            );
            
            return back()->with($notification);
        }

        //// Update The New Password
        User::whereId(auth()->Auth::id())->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Password Change Success',
            'alert-type' => 'success'
        );

        return back()->with($notification);

    }// End Method
}


