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

    public function getCourseDetails(Request $request, $id)
    {
        $user = $request->user();
        $course = \App\Models\CoursesModel::find($id);
        if (!$course) {
            return response()->json(['message' => 'A kurzus nem található!'], 404);
        }
        $assignments = \App\Models\AssignmentModel::where('course_id', $id)
            ->where('user_username', $user->username)
            ->get();
        return response()->json([
            'status' => 'Siker',
            'course' => $course,
            'assignments' => $assignments
        ], 200);
    }

    public function newAssignment(Request $request, $id)
    {
        $request->validate([
            'assignment_name' => 'required|string',
            'assignment_type' => 'required|string',
            'assignment_max_point' => 'required|integer',
            'assignment_deadline' => 'required|date',
            'assignment_accessible' => 'required'
        ]);
        $course = \App\Models\CoursesModel::find($id);
        if (!$course) {
            return response()->json(['message' => 'A kurzus nem található!'], 404);
        }
        $users = [];
        if (!empty($course->course_users)) {
            $users = explode(',', $course->course_users);
        }
        $users[] = $request->user()->username;
        $users = array_unique($users);
        foreach ($users as $user) {
            $user = trim($user);
            if (!empty($user)) {
                \App\Models\AssignmentModel::create([
                    'course_id' => $id,
                    'assignment_name' => $request->assignment_name,
                    'assignment_type' => $request->assignment_type,
                    'assignment_max_point' => $request->assignment_max_point,
                    'assignment_succed_point' => 0,
                    'assignment_grade' => 0,
                    'assignment_finnished' => 0,
                    'assignment_deadline' => $request->assignment_deadline,
                    'assignment_accessible' => $request->assignment_accessible,
                    'user_username' => $user,
                    'creator_username' => $request->user()->username
                ]);
            }
        }
        return response()->json([
            'status' => 'Siker',
            'message' => 'A feladat sikeresen kiírva!'
        ], 201);
    }

    public function getAssignmentDetails(Request $request, $id)
    {
        $assignment = \App\Models\AssignmentModel::find($id);
        if (!$assignment) {
            return response()->json(['message' => 'A feladat nem található!'], 404);
        }
        return response()->json([
            'status' => 'Siker',
            'assignment' => $assignment
        ], 200);
    }

    public function deleteAssignment($id)
    {
        $assignment = \App\Models\AssignmentModel::find($id);
        if (!$assignment) {
            return response()->json(['message' => 'A feladat nem található!'], 404);
        }
        $assignment->delete();
        return response()->json(['message' => 'A feladat sikeresen törölve!'], 200);
    }

    public function updateAssignment(Request $request, $id)
    {
        $assignment = \App\Models\AssignmentModel::find($id);
        if (!$assignment) {
            return response()->json(['message' => 'A feladat nem található!'], 404);
        }
        $request->validate([
            'assignment_name' => 'required|string',
            'assignment_type' => 'required|string',
            'assignment_max_point' => 'required|integer',
            'assignment_deadline' => 'required|date',
            'assignment_accessible' => 'required'
        ]);
        $assignment->update([
            'assignment_name' => $request->assignment_name,
            'assignment_type' => $request->assignment_type,
            'assignment_max_point' => $request->assignment_max_point,
            'assignment_deadline' => $request->assignment_deadline,
            'assignment_accessible' => $request->assignment_accessible,
        ]);
        return response()->json([
            'message' => 'Feladat sikeresen frissítve!', 
            'assignment' => $assignment
        ], 200);
    }

    public function getQuestions($id)
    {
        $questions = \App\Models\QuestionModel::with('answers')->where('assignment_id', $id)->get();
        return response()->json(['questions' => $questions], 200);
    }

    public function addQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|string',
            'question_points' => 'required|integer|min:1'
        ]);
        $question = \App\Models\QuestionModel::create([
            'assignment_id' => $id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'question_points' => $request->question_points
        ]);
        return response()->json(['message' => 'Kérdés hozzáadva!', 'question' => $question], 201);
    }
    public function addAnswer(Request $request, $id)
    {
        $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'required|boolean'
        ]);
        $answer = \App\Models\AnswerModel::create([
            'question_id' => $id,
            'answer_text' => $request->answer_text,
            'is_correct' => $request->is_correct
        ]);
        return response()->json(['message' => 'Válasz hozzáadva!', 'answer' => $answer], 201);
    }
    public function deleteQuestion($id)
    {
        $question = \App\Models\QuestionModel::find($id);
        if (!$question) {
            return response()->json(['message' => 'A kérdés nem található!'], 404);
        }
        $question->delete();
        return response()->json(['message' => 'Kérdés sikeresen törölve!'], 200);
    }
    public function deleteAnswer($id)
    {
        $answer = \App\Models\AnswerModel::find($id);
        if (!$answer) {
            return response()->json(['message' => 'A válasz nem található!'], 404);
        }
        $answer->delete();
        return response()->json(['message' => 'Válasz sikeresen törölve!'], 200);
    }
}