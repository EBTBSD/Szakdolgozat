<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoursesModel;
use App\Models\AssignmentModel;
use App\Http\Requests\newCourseRequest;

class CoursesController extends Controller
{
    public function newCourse(newCourseRequest $request){
        
        $adatok = $request->validated();
        
        $adatok['creator_username'] = $request->user()->username;

        $course = CoursesModel::create($adatok);
        if (!empty($adatok['course_users'])) {
            $users = explode(',', $adatok['course_users']);
            foreach ($users as $user) {
                $user = trim($user); 
                if (!empty($user)) {
                    AssignmentModel::create([
                        'course_id' => $course->id,
                        'user_username' => $user
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'Siker',
            'message' => 'A kurzus sikeresen létrejött!'
        ], 201);
    }
}