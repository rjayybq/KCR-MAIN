<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 10 users per page
       $users = User::orderBy('id', 'desc')->paginate(10);

         return view('cashier.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('cashier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users', // validate username
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,cashier', // only admin & cashier allowed
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username, // save username
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Account created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('cashier.edit', compact('user'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id, // unique except this user
            'email' => 'required|string|email|unique:users,email,' . $user->id, // unique except this user
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,cashier',
            'password' => 'nullable|string|min:6|confirmed', // optional password update
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'status' => $request->status,
            'role' => $request->role,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Account updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User account removed successfully.');
    }

    public function profile()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    
            
}
