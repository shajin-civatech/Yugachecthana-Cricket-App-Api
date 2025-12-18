<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'google_id' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // New User? Register them.
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(16)), // Random password for Google users
                'google_id' => $request->google_id,
                'is_verified' => false
            ]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();

        // Send OTP via Email (Mock for now, or use Mail facade)
        // Mail::to($user->email)->send(new otpMail($otp));

        return response()->json([
            'message' => 'OTP sent to your email',
            'email' => $user->email,
            'require_otp' => true
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        // Verification Success
        $user->otp = null;
        $user->is_verified = true;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login Successful'
        ]);
    }
}
