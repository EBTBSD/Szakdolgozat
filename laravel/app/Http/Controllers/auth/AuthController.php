<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UsersModel;
use App\Models\CoursesModel;
use App\Models\AssignmentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login_store(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'Siker',
                'token' => $token,
                'user' => $user,
                'message' => 'Sikeres bejelentkezés!'
            ], 200);
        } 
        else {
            return response()->json([
                'status' => 'Hiba',
                'message' => 'Hibás felhasználónév vagy jelszó!'
            ], 401);
        }
    }

    public function register_store(RegisterRequest $request)
    {
        $adatok = $request->validated();
        do {
            $username = strtoupper(\Illuminate\Support\Str::random(4));
            $exists = \App\Models\User::where('username', $username)->exists();
        } while ($exists);
        $user = User::create([
            'firstname' => $adatok['firstname'],
            'lastname'  => $adatok['lastname'],
            'username' => $username,
            'password' => bcrypt($adatok['password']),
            'email' => $adatok['email'],
        ]);

        return response()->json([
            'status' => 'Siker',
            'message' => 'Sikeres regisztráció!',
            'username' => $username
        ], 201);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'Siker',
                'message' => 'Sikeres kijelentkezés!'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hiba történt a kijelentkezés során.'
            ], 500);
        }
    }
}