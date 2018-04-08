<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $settings = json_decode($user->settings);
        return view('users.edit', compact('user', 'settings'));
    }

    /**
     * @param  Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'pagination' => 'required',
        ]);
        $user->update([
            'email' => $request->email,
            'settings' => json_encode(['pagination' => $request->pagination]),
        ]);
        return back()->with(['ok' => __('Le profil a bien été mis à jour')]);
    }
}
