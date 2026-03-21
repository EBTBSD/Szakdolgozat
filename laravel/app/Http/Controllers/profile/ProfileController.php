<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'firstname' => 'sometimes|required|string|max:100',
            'lastname'  => 'sometimes|required|string|max:100',
            'email'     => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6',
        ]);

        if ($request->has('firstname')) $user->firstname = $validated['firstname'];
        if ($request->has('lastname')) $user->lastname = $validated['lastname'];
        if ($request->has('email')) $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return response()->json([
            'status' => 'Siker',
            'message' => 'Profil sikeresen frissítve!',
            'user' => $user
        ], 200);
    }
}