<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-post',
            'update-own-post',
            'delete-own-post',
            'update-any-post',
            'delete-any-post',
            'create-category',
            'update-category',
            'delete-category',
            'manage-users',
            'assign-role',
        ];

        foreach($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'دسترسی کامل سییستم',
        ]);

        $creator = Role::firstOrCreate([
            'name' => 'creator',
            'description' => 'مدیریت دسته بندی‌ها',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
            'description' => 'کاربر عادی سیستم',
        ]); 

        $admin->permissions()->sync(Permission::all());

        $creator->permissions()->sync(Permission::WhereIn('name', [
            'create-category',
            'update-category',
            'delete-category',
        ])->get());

        $user->permissions()->sync(Permission::WhereIn('name', [
            'create-post',
            'update-own-post',
            'delete-own-post',
        ])->get());
        
        

    }
}
