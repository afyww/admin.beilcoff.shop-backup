<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function vlogin()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $qrToken = Str::random(32);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->level = 'cashier';
        $user->qr_token = $qrToken;
        $user->save();  // Save the additional attributes

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect('/users')->with('toast_success', 'Registration successful!')
            ->with('access_token', $token);
    }

    public function registerchair(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $email = Str::random(8) . '@gmail.com';

        $password = Str::random(10);

        $qrToken = Str::random(32);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->level = 'user';
        $user->qr_token = $qrToken;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect('/chair')->with('toast_success', 'Registration successful!')->with('access_token', $token);
    }

    public function login(Request $request)
    {
        if ($request->has('qrToken')) {
            $user = User::where('qr_token', $request->qrToken)->first();
    
            if (! $user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }
    
        // Standard email/password login
        if (! Auth::attempt($request->only('email', 'password'))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            return redirect()->route('login')->withErrors(['email' => 'Unauthorized']);
        }
    
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }
    
        Auth::login($user);
        return redirect()->route('dashboard');
    }
    

    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            if ($user = Auth::user()) {
                $user->tokens()->delete(); // Revoke all tokens
            }

            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        }

        // For non-JSON requests (web guard)
        if ($user = Auth::guard('web')->user()) {
            $user->tokens()->delete(); // Revoke all tokens
        }

        Auth::guard('web')->logout();
        return redirect()->route('login')->with('status', 'Logged out successfully');
    }
}
