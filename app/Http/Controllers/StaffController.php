<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index()
    {
        // Fetch only staff and manager roles, exclude guest
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('adminFolder.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('adminFolder.staff.create');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|max:255|unique:users',
            'phoneNumber' => 'nullable|string|max:11|regex:/^09[0-9]{9}$/',
            'role' => 'required|in:staff,manager',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.regex' => 'The name field may only contain letters and spaces.',
            'username.regex' => 'The username may only contain letters, numbers, and underscores.',
            'phoneNumber.regex' => 'The phone number must be a valid 11-digit Philippine number starting with 09.',
            'password.min' => 'The password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Automatically verify email when created by admin
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit($id)
    {
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);
        
        return view('adminFolder.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, $id)
    {
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($staff->userID, 'userID'),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($staff->userID, 'userID'),
            ],
            'phoneNumber' => 'nullable|string|max:11|regex:/^09[0-9]{9}$/',
            'role' => 'required|in:staff,manager',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.regex' => 'The name field may only contain letters and spaces.',
            'username.regex' => 'The username may only contain letters, numbers, and underscores.',
            'phoneNumber.regex' => 'The phone number must be a valid 11-digit Philippine number starting with 09.',
            'password.min' => 'The password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $staff->update($updateData);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy($id)
    {
        // Prevent deleting yourself
        if ($id === auth()->guard()->user()->userID) { 
            return redirect()->route('admin.staff.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);
        
        // Permanent delete
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}