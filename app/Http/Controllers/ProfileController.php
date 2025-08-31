<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //     public function update(Request $request)
    // {
    //     $user = auth()->user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:6|confirmed',
    //     ]);

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
    //     ]);

    //     return back()->with('success', 'Profile updated successfully!');
    // }

}
