<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        ds($request->validated())->label('register: validated request data');

        $plainPassword = $request->validated('password');
        ds($plainPassword)->label('register: password before Hash::make()');

        $hashedPassword = Hash::make($plainPassword);
        ds($hashedPassword)->label('register: password after Hash::make()');

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $hashedPassword,
        ]);

        ds($user)->label('register: created user');

        Auth::login($user);

        return to_route('tasks.index')->with('success', 'Welcome, '.$user->name.'!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        ds($credentials)->label('login: credentials submitted');

        $attemptResult = Auth::attempt($credentials, $request->boolean('remember'));
        ds($attemptResult)->label('login: Auth::attempt() result');

        if (! $attemptResult) {
            return back()->withInput($request->only('email'))->with('error', 'Those credentials do not match our records.');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('tasks.index'))->with('success', 'Welcome back, '.Auth::user()->name.'!');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return to_route('login')->with('success', 'You have been logged out.');
    }
}