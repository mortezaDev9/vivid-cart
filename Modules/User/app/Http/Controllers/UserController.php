<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\User\Http\Requests\UpdatePasswordRequest;
use Modules\User\Http\Requests\UpdateProfileRequest;

class UserController
{
    public function index(): View
    {
        return view('user::index');
    }

    public function editProfile(): View
    {
        return view('user::edit-profile');
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        Gate::authorize('update', auth()->user());

        $validated = $request->validated();
        $user      = auth()->user();

        $changes = array_filter($validated, function ($value, $key) use ($user) {
            return $user->$key !== $value;
        }, ARRAY_FILTER_USE_BOTH);

        if (! empty($changes)) {
            $user->update($changes);

            toast('success', __('Your profile has been updated.'));
        } else {
            toast('info', __('Nothing updated'));
        }

        return to_route('account.index');
    }

    public function changePassword(): View
    {
        return view('user::change-password');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        Gate::authorize('update', auth()->user());

        $validated = $request->validated();
        $user      = auth()->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The current password is incorrect.'),
            ]);
        }

        if (Hash::check($validated['new_password'], $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => __('The new password cannot be the same as the current password.'),
            ]);
        }

        $user->forceFill(['password' => Hash::make($validated['new_password'])]);
        $user->save();

        toast('success', __('Your password has been updated successfully.'));

        return to_route('account.index');
    }
}
