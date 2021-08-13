<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function showFormLogin()
    {
        if(Auth::check()){
            return redirect("/admin");
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = [
            "username" => $request->request->get("username"),
            "password" => $request->request->get("password")
        ];
        if (Auth::attempt($credentials)){
            return redirect("/admin");
        }
        return redirect()->back()->withInput()->with('failed', 'Periksa Kembali Username dan Password Anda');
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/");
    }
}
