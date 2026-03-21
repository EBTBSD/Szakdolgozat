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
        if(Auth::attempt($request->validated()))
        {
            $user = Auth::user();
            $courses = CoursesModel::all();
            $assigment = AssignmentModel::all();
            $average = DB::table('assignment')->avg('assignment_grade');
            
            $grades = [];
            $ass_perc_arr = [];
            $ass_perc_suc = 0;
            $ass_perc_fai = 0;
            $ass_perc_out = 0;
            $ass_perc_nye = 0;
            $ass_perc = 0;

            foreach ($assigment as $item) {
                if($item->user_username == $user->username){
                    $grades[] = $item->assignment_grade;
                    $ass_perc_arr[] = $item->assignment_finnished;
                }
            }
            if(count($ass_perc_arr) > 0){
                for ($i=0; $i < count($ass_perc_arr); $i++) {
                    if($ass_perc_arr[$i] == 2){ $ass_perc_suc++; }
                    elseif($ass_perc_arr[$i] == 1){ $ass_perc_fai++; }
                    elseif($ass_perc_arr[$i] == 0){ $ass_perc_nye++; }
                }
                $ass_perc = ($ass_perc_suc / count($ass_perc_arr))*100;
            } else {
                $ass_perc = 0;
            }

            if(count($grades) != 0){
                $average = array_sum($grades) / count($grades);
            } else {
                $average = 0;
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'Siker',
                'token' => $token,
                'user' => $user,
                'stats' => [
                    'courses' => $courses,
                    'assigment' => $assigment,
                    'average' => $average,
                    'ass_perc' => $ass_perc,
                    'ass_perc_suc' => $ass_perc_suc,
                    'ass_perc_fai' => $ass_perc_fai,
                    'ass_perc_out' => $ass_perc_out,
                    'ass_perc_nye' => $ass_perc_nye,
                ]
            ], 200);
        }
        else
        {
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
            $username = strtoupper(\Illuminate\Support\Str::random(5));
            $exists = \App\Models\User::where('username', $username)->exists();
        } while ($exists);
        $user = \App\Models\User::create([
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
}