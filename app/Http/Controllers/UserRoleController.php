<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index(){
        $users = User::with('roles')->orderBy('first_name')->get();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function update(Request $request, User $user){

        $data = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $creatorRole = Role::where('name', 'creator')->first();

        if($user->hasRole('creator')){
            abort(403, 'نقش creator قابل تغییر نمی‌باشد');
        }

        if($creatorRole && in_array($creatorRole->id, $data['role_ids'])){
            abort(403, 'نقش creator قابل تخصیص نمی‌باشد');
        }

        $user->roles()->sync($data['role_ids']);
        return back()->with('success', 'نقش کاربر با موفقیت بروزرسانی شد');
    }
}
