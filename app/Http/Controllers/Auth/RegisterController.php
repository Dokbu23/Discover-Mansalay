<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:tourist,resort_owner,enterprise_owner'],
        ]);

        // Auto-approve tourists, others need admin approval
        $isApproved = $request->role === 'tourist';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'is_active' => true,
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
        ]);

        if ($isApproved) {
            Auth::login($user);
            return redirect('/dashboard')->with('success', 'Welcome to Discover Mansalay!');
        }

        return redirect('/login')->with('success', 'Registration successful! Please wait for admin approval before you can login.');
    }
}
