<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $confirmedAt = (int) $request->session()->get('auth.password_confirmed_at', 0);
        $twoFactorPasswordConfirmed = now()->timestamp - $confirmedAt <= config('auth.password_timeout');

        return view('profile.edit', [
            'user' => $user,
            'twoFactorPasswordConfirmed' => $twoFactorPasswordConfirmed,
        ]);
    }

    public function update(Request $request, UpdatesUserProfileInformation $profileInformation): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $profileInformation->update($user, $request->only(['name', 'email']));

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        Validator::make($request->only('password'), [
            'password' => ['required', 'current_password:web'],
        ])->validateWithBag('userDeletion');

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
