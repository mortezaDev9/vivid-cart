<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Auth\Http\Requests\RegisterUserRequest;
use Modules\Cart\Models\Cart;
use Modules\User\Models\User;
use Modules\Wishlist\Models\Wishlist;

class RegisterUserController
{
    public function registerForm(): View
    {
        return view('auth::register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $user = DB::transaction(function () use ($validated) {
                $user = User::create([
                    'username' => $validated['username'],
                    'slug'     => Str::slug($validated['username']),
                    'email'    => $validated['email'],
                    'password' => $validated['password'],
                ]);

                Cart::create(['user_id' => $user->id]);
                Wishlist::create(['user_id' => $user->id]);

                return $user;
            });
        } catch (Exception $e) {
            Log::error('Transaction for registering user failed: ' . $e->getMessage());

            return back()->with('error', __('Registration failed, please try again.'));
        }

        Auth::login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
