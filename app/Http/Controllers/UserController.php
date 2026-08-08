<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::orderBy('username')->get();
        

        return view('users.index', compact('users'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        // Get employees that don't have an inventory account yet
        $existingUsers = User::pluck('idno');

        $employees = DB::connection('hris')
            ->table('employee_profile')
            ->whereNotIn('idno', $existingUsers)
            ->select(
                'idno',
                'lastname',
                'firstname',
                'middlename'
            )
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        return view('users.create', compact('employees'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idno' => 'required|unique:users,idno',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ]);

        User::create([
            'idno' => $request->idno,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
            $request->validate(
            [
                'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
                'role'      => 'required',
                'is_active' => 'required|boolean',
                'password'  => 'nullable|string|min:8|confirmed',
            ],
            [
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'username.unique' => 'This username is already in use.',
            ]
        );
        
        $user->username = $request->username;
        $user->role = $request->role;
        $user->is_active = $request->is_active;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

}