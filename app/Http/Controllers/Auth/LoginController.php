<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $credentials = $request->only('email', 'password');
       
        if (Auth::attempt($credentials)) {
            // dd($credentials);
            $request->session()->regenerate();

            $role = Auth::user()->role;
            
            return match ($role) {
                'superadmin' => redirect()->route('superadmin.dashboard.index'),
                'admin' => redirect()->route('admin.dashboard.index'),
                'accountant' => redirect()->route('accountant.dashboard.index'),
                'doctor' => redirect()->route('doctor.dashboard.index'),
                'patient' => redirect()->route('patient.dashboard.index'),
                'laboratorist' => redirect()->route('laboratorist.dashboard.index'),
                default => redirect()->route('home'),
            };
            session()->flash('success', 'Test message works!');
        }
        session()->flash('error', 'Invalid email or password');
        return view('auth.login');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

