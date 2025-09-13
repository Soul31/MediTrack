<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\User;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the form inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to the unified dashboard route
            return redirect()->route('dashboard');
        }

        // If authentication fails, redirect back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect to the login page or home
    }

    public function register(Request $request)
    {
        // Validate the form inputs
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user in the database
        $user = User::create([
            'nom' => $validatedData['last_name'],
            'prenom' => $validatedData['first_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'patient',
        ]);

        $patient = Patient::create([
           'user_id' => $user->id
        ]);

        // Log in the user
        Auth::login($user);

        // Redirect to email verification notice
        return redirect()->route('verification.notice');
    }
public function changePassword(Request $request)
{
    // Validate the form inputs
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:8|confirmed', // Ensure password confirmation
    ]);

    // Check if the old password matches the current password
    if (!Hash::check($request->old_password, Auth::user()->password)) {
        return back()->withErrors(['old_password' => 'The old password is incorrect.']);
    }

    // Check if the new password is the same as the old password
    if (Hash::check($request->new_password, Auth::user()->password)) {
        return back()->withErrors(['new_password' => 'The new password cannot be the same as the old password.']);
    }

    // Update the user's password
    $user = Auth::user();
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Redirect with a success message
    return back()->with('success', 'Your password has been changed successfully.');
}
public function updateProfile(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'current_password' => 'required',
        // Add validation for phone/address if needed
    ]);

    // Verify current password
    if (!\Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
    }

    $user->nom = $request->nom;
    $user->prenom = $request->prenom;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}
}
