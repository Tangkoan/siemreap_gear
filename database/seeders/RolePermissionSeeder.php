<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Import Model User
use Illuminate\Support\Facades\Hash; // !! ត្រូវតែ Import Hash Facade

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. បង្កើត Permissions ទាំងអស់
        $permissions = [
            'customer.all', 'customer.add', 'customer.edit', 'customer.delete', 'customer.menu',
            'pos.menu',
            'supplier.menu', 'supplier.all', 'supplier.edit', 'supplier.add', 'supplier.delete',
            'category.menu', 'category.all', 'category.edit', 'category.delete', 'category.add',
            'product.menu', 'product.all', 'product.add', 'product.edit', 'product.delete', 'product.import', 'product.export', 'product.barcode', 'product.details',
            'order.menu', 'order.pending', 'order.complete', 'order.pending.pre.order', 'order.pending.due',
            'purchase.menu', 'purchase.complete', 'purchase.pending.due',
            'role.menu', 'permission.menu',
            'user.menu', 'user.all', 'user.add', 'user.delete', 'user.edit',
            'condition.all', 'condition.add', 'condition.edit', 'condition.delete',
            'setting.menu',
            'backup.menu',
            'report.menu', 'report.sale', 'report.purchase', 'report.stock', 'report.expense'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. បង្កើត Roles និងផ្តល់ Permissions ឱ្យ
        // Role: Supper Admin 
        $superAdminRole = Role::create(['name' => 'Supper Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

         // 3. បង្កើត Users និងផ្តល់ Role ឱ្យ
        // បង្កើត User: Supper Admin
        $superAdminUser = User::create([
            'name' => 'vc',
            'email' => 'kuytangkoan@gmail.com', // កែពី .o ទៅ .com
            'password' => Hash::make('Tangkoan@1100') //  សំខាន់! ប្រើ Hash::make
        ]);
        $superAdminUser->assignRole($superAdminRole);

        // 3. ផ្តល់ Role User ដែលមានស្រាប់
        // User: vc (ID=1) ជា Supper Admin
        $userVc = User::find(1);
        if ($userVc) {
            $userVc->assignRole($superAdminRole);
        }

    }
}