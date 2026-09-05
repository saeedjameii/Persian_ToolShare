<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $jalaliDate = Jalalian::fromFormat('Y/m/d', $request->birth_date);

            if ($jalaliDate->format('Y/m/d') !== $request->birth_date) {
                throw new \InvalidArgumentException('Invalid Jalali date after normalization.');
            }

            $birthDate = $jalaliDate->toCarbon()->format('Y-m-d');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors(['birth_date' => 'تاریخ تولد وارد شده معتبر نیست. لطفاً از فرمت YYYY/MM/DD استفاده کنید (مثلاً 1405/06/12).']);
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

        Auth::login($user);

        if (!$user) {
            return redirect()->back()->with('error', 'ثبت نام با مشکل مواجه شد. لطفاً دوباره تلاش کنید.');
        }

        return redirect()->route('home')->with('success', 'ثبت نام موفقیت‌آمیز بود. لطفاً وارد شوید.');
    }

    public function login(){
        return view('auth.login');
    }

    public function loginPost(Request $request){
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return redirect()->back()->withInput()->withErrors(['email' => 'ایمیل یا رمز عبور اشتباه است.']);
        }

        return redirect()->route('home')->withCookie(cookie('token', $token, 60))->with('success', 'ورود موفقیت‌آمیز بود.');
    }
}
