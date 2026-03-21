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
            // 1. A kurzusok lekérése
            $courses = CoursesModel::where('course_users', 'LIKE', '%' . $user->username . '%')
                                   ->orWhere('creator_username', $user->username)
                                   ->get();
            
            // 2. A diák eddigi beadásai
            $submissions = \App\Models\SubmissionModel::where('user_id', $user->id)->get();
            
            // --- STATISZTIKA SZÁMÍTÁSA ---
            $grades = [];
            $ass_perc_suc = 0; $ass_perc_fai = 0;
            $ass_perc_out = 0; $ass_perc_nye = 0; $ass_perc_need = 0;

            foreach ($submissions as $sub) {
                $grades[] = $sub->grade;
                if ($sub->grade >= 2) {
                    $ass_perc_suc++;
                } else {
                    $ass_perc_fai++;
                }
            }

            $total_subs = count($submissions);
            $ass_perc = ($total_subs > 0) ? round(($ass_perc_suc / $total_subs) * 100) : 0;
            $average = (count($grades) > 0) ? round(array_sum($grades) / count($grades), 2) : 0;


            // --- ÚJ: FELADATOK (TEENDŐK) LEKÉRÉSE ---
            $courseIds = $courses->pluck('id'); // Kigyűjtjük a kurzusok ID-jait
            
            // Lekérjük az összes elérhető feladatot, ami ezekhez a kurzusokhoz tartozik
            $assignments = \App\Models\AssignmentModel::whereIn('course_id', $courseIds)
                                ->where('assignment_accessible', 1)
                                ->orderBy('assignment_deadline', 'asc') // Legközelebbi határidő legelöl
                                ->get();

            // Kicsit "kicsinosítjuk" a feladatokat az Angular számára
            foreach ($assignments as $ass) {
                // Hozzárakjuk a kurzus nevét
                $course = $courses->firstWhere('id', $ass->course_id);
                $ass->course_name = $course ? $course->course_name : 'Ismeretlen kurzus';
                
                $sub = $submissions->firstWhere('assignment_id', $ass->id);
                $ass->is_completed = $sub ? true : false;
                
                $deadline = \Carbon\Carbon::parse($ass->assignment_deadline)->endOfDay();
                $ass->is_expired = \Carbon\Carbon::now()->greaterThan($deadline);
            }

            return response()->json([
                'status' => 'Siker',
                'user' => $user,
                'courses' => $courses,
                'assignments' => $assignments,
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