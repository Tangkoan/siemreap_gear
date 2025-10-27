<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Persion
use Spatie\Permission\Traits\HasRoles;
// End Persion

// Role Permission
use DB;
// End

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable;
    use  HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'appearance_settings', // ✅ បន្ថែម Column ថ្មីនេះ
        // 'background_type',  // ❌ លុប Column ចាស់នេះចោល
        // 'background_value', // ❌ លុប Column ចាស់នេះចោល
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            'appearance_settings' => 'array', // <-- បន្ថែមបន្ទាត់នេះ
        ];
    }


    // Start Role Permission
        public static function getpermissionGroups(){
            $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
            return $permission_groups;
        } // End Method 


        public static function getpermissionByGroupName($group_name){
            $permissions = DB::table('permissions')
                            ->select('name','id')
                            ->where('group_name',$group_name)
                            ->get();
            return $permissions;

        }// End Method 


        // public static function roleHasPermissions($role, $permissions){

        //     $hasPermission = true;
        //     foreach($permissions as $permission){
        //         if (!$role->hasPermissionTo($permission->name)) {
        //             $hasPermission = false;
        //             return $hasPermission;
        //         }
        //         return $hasPermission;
        //     }
    
        // }// End Method 
        public static function roleHasPermissions($role, $permissions){
            foreach ($permissions as $permission) {
                if (!$role->hasPermissionTo($permission)) {
                    return false;
                }
            }
            return true;
        }
        



        
    // End
}
