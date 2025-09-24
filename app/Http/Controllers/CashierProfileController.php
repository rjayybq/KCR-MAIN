<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CashierProfileController extends Controller
{
    public function __construct()
    {
        // Protect all routes: only authenticated users with cashier role
        $this->middleware(['auth', 'role:cashier']);
    }

    /**
     * Show cashier profile
     */
    public function profile()
    {
        return view('cashier.profile.show', ['user' => auth()->user()]);
    }

    /**
     * Update cashier profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'profile_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            // Delete old picture if exists
            if ($user->profile_pic && Storage::disk('public')->exists($user->profile_pic)) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }

        // Update profile fields
        $user->name = $request->name;
        $user->username = $request->username ?? $user->username; // optional
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('cashier.profile')
            ->with('success', 'Profile updated successfully!');
    }
}
