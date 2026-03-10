<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersModel;
use App\Models\CoursesModel;
use App\Models\AssignmentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function main(Request $request){
        $user = $request->user();
        if($user){
            $courses = CoursesModel::where('course_users', $user->username)
                                   ->orWhere('creator_username', $user->username)
                                   ->get();
            $assigment = AssignmentModel::where('user_username', $user->username)->get();
            $grades = [];
            $ass_perc_arr = [];
            $ass_perc_suc = 0;
            $ass_perc_fai = 0;
            $ass_perc_out = 0;
            $ass_perc_nye = 0;
            $ass_perc_need = 0;
            $ass_perc = 0;
            foreach ($assigment as $item) {
                $grades[] = $item->assignment_grade;
                $ass_perc_arr[] = $item->assignment_finnished;
            }
            if(count($ass_perc_arr) > 0){
                for ($i=0; $i < count($ass_perc_arr); $i++) {
                    if($ass_perc_arr[$i] == 3){
                        $ass_perc_need++;
                    }
                    elseif($ass_perc_arr[$i] == 2){
                        $ass_perc_suc++;
                    }
                    elseif($ass_perc_arr[$i] == 1){
                        $ass_perc_fai++;
                    }
                    elseif($ass_perc_arr[$i] == 0){
                        $ass_perc_nye++;
                    }
                }
                $ass_perc = round(($ass_perc_suc / count($ass_perc_arr)) * 100);
            } else {
                $ass_perc = 0;
            }
            if(count($grades) != 0){
                $average = round(array_sum($grades) / count($grades), 2);
            } else {
                $average = 0;
            }
            return response()->json([
                'status' => 'Siker',
                'user' => $user,
                'courses' => $courses,
                'stats' => [
                    'average' => $average,
                    'ass_perc' => $ass_perc,
                    'ass_perc_suc' => $ass_perc_suc,
                    'ass_perc_fai' => $ass_perc_fai,
                    'ass_perc_out' => $ass_perc_out,
                    'ass_perc_nye' => $ass_perc_nye,
                    'ass_perc_need' => $ass_perc_need,
                ]
            ], 200);
        }
        return response()->json(['message' => 'Nincs bejelentkezve!'], 401);
    }
}