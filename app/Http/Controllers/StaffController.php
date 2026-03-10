<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('adminFolder.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('adminFolder.staff.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'username'      => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email'         => 'required|email|max:255|unique:users',
            'phoneNumber'   => 'nullable|string|max:11|regex:/^09[0-9]{9}$/',
            'role'          => 'required|in:staff,manager',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password'      => 'required|string|min:8|confirmed',
        ], [
            'name.regex'          => 'The name field may only contain letters and spaces.',
            'username.regex'      => 'The username may only contain letters, numbers, and underscores.',
            'phoneNumber.regex'   => 'The phone number must be a valid 11-digit Philippine number starting with 09.',
            'password.min'        => 'The password must be at least 8 characters.',
            'profile_image.image' => 'The profile photo must be an image file.',
            'profile_image.mimes' => 'Accepted formats: jpeg, png, jpg, gif, webp.',
            'profile_image.max'   => 'Profile photo must not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')
                            ->store('profile_images', 'public');
        }

        User::create([
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'phoneNumber'       => $request->phoneNumber,
            'role'              => $request->role,
            'profile_image'     => $imagePath,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function edit($id)
    {
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);
        
        return view('adminFolder.staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'username'      => [
                'required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($staff->userID, 'userID'),
            ],
            'email'         => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($staff->userID, 'userID'),
            ],
            'phoneNumber'   => 'nullable|string|max:11|regex:/^09[0-9]{9}$/',
            'role'          => 'required|in:staff,manager',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password'      => 'nullable|string|min:8|confirmed',
        ], [
            'name.regex'          => 'The name field may only contain letters and spaces.',
            'username.regex'      => 'The username may only contain letters, numbers, and underscores.',
            'phoneNumber.regex'   => 'The phone number must be a valid 11-digit Philippine number starting with 09.',
            'password.min'        => 'The password must be at least 8 characters.',
            'profile_image.image' => 'The profile photo must be an image file.',
            'profile_image.mimes' => 'Accepted formats: jpeg, png, jpg, gif, webp.',
            'profile_image.max'   => 'Profile photo must not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'role'        => $request->role,
        ];

        // Handle remove image
        if ($request->input('remove_image') === '1') {
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $updateData['profile_image'] = null;
        }

        // Handle new image upload (overrides remove if both somehow sent)
        if ($request->hasFile('profile_image')) {
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $updateData['profile_image'] = $request->file('profile_image')
                                               ->store('profile_images', 'public');
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $staff->update($updateData);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        if ($id == auth()->guard()->user()->userID) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $staff = User::whereIn('role', ['staff', 'manager'])
                    ->findOrFail($id);

        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}