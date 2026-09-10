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
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->roles()->sync([$request->role_id]);
        return back()->with('success', 'نقش کاربر با موفقیت بروزرسانی شد');
    }
}
