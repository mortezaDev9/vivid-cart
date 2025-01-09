<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Auth\Http\Requests\LoginUserRequest;

class AuthenticateUserController
{
    public function loginForm(): View
    {
        return view('auth::login');
    }

    public function login(LoginUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $remember  = $request->filled('remember');

        if (Auth::attempt($validated, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => __('The provided credentials do not match our records')
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
