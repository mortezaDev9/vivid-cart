<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\User\Models\User;

class ResetPasswordController
{
    public function resetPasswordForm(string $token): View
    {
        return view('auth::reset-password', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $email     = $validated['email'];
        $tokenData = DB::table('password_reset_tokens')
            ->whereEmail($email)
            ->first();

        if (! $tokenData || ! Hash::check($validated['token'], $tokenData->token)) {
            return back()->withErrors(['email' => __('Invalid token or email')]);
        }

        $user = User::whereEmail($email)->first();
        $user->forceFill(['password' => Hash::make($validated['password'])])->setRememberToken(Str::random(64));
        $user->save();

        DB::table('password_reset_tokens')
            ->whereEmail($validated['email'])
            ->delete();

        event(new PasswordReset($user));

        toast('success', __('Your password has been reset successfully.'));

        return to_route('login.form');
    }
}
