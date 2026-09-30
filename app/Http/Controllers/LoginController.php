<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials =[
            $field =>$request->email,
            'password' =>$request->password
        ];

        if(Auth::guard('web')->attempt($credentials))
        {
            $request->session()->regenerate();
            
            // Cek apakah ada intended URL (dari OAuth atau route tertentu)
            // Jika tidak ada, default ke dashboard
            $intendedUrl = session()->pull('url.intended', route('dashboard'));
            
            return redirect($intendedUrl)
                ->withSuccess('You have successfully logged in!');
        }else {
            return back()->withErrors([
                'email' => 'Username not found in crendential',
            ])->onlyInput('email', 'password');
        }

    } 
    
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }    

}