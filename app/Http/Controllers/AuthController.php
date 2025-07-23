<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {

    public function __construct(private MailService $mailService) {
    }
    
    public function register(Request $request) {
        try{
            $registerUserData = $request->validate([
                'name' => 'required|string',
                'password' => 'required|min:8',
                'email' => 'required|string|email:rfc,dns|unique:users'
            ]);
        }   catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $registerUserData['name'],
            'password' => $registerUserData['password'],
            'email' => $registerUserData['email']
        ]);

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        $this->mailService->sendWelcome($user);

        return response()->json([
            'token' => $token,
        ]);
    }

    public function login(Request $request) {
        try{
            $loginUserData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|min:8'
            ]);
        }   catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) { // Return error if user doesn't exist or password doesn't match.
            return response()->json([
                'errors' => [
                    'password' => ['Invalid credentials.']
                ]
            ], 401);
        }

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }
}
