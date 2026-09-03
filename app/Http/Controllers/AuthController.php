<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class AuthController extends Controller
{
    public function signUp(){
        return view('auth.signUp');
    }

    public function signUpPost(Request $request){

        $request->validate([
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|size:11|regex:/^09[0-9]{9}$/|unique:users',
            'birth_date' => 'required|regex:/^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/',
            'national_code' => 'required|size:10|regex:/^[0-9]{10}$/|unique:users',
            'password' => 'required|min:8|confirmed' 
        ]);

        try{
            $birthDate = Jalalian::fromFormat('Y/m/d', $request->birth_date)->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'تاریخ تولد وارد شده معتبر نیست. لطفاً از فرمت YYYY/MM/DD استفاده کنید.');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'birth_date' => $birthDate,
            'national_code' => $request->national_code,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return redirect()->back()->with('error', 'ثبت نام با مشکل مواجه شد. لطفاً دوباره تلاش کنید.');
        }

        return redirect()->route('home')->with('success', 'ثبت نام موفقیت‌آمیز بود. لطفاً وارد شوید.');
    }
}
