<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoursesModel;
use App\Models\AssignmentModel;
use App\Http\Requests\newCourseRequest;
use Illuminate\Support\Str;

class CoursesController extends Controller
{
    public function newCourse(newCourseRequest $request)
    {
        $adatok = $request->validated();
        $adatok['creator_username'] = $request->user()->username;
        $adatok['invite_code'] = strtoupper(\Illuminate\Support\Str::random(6));
        $course = \App\Models\CoursesModel::create($adatok);
        if (!empty($adatok['course_users'])) {
            $users = explode(',', $adatok['course_users']);
            foreach ($users as $user) {
                $user = trim($user); 
                if (!empty($user)) {
                    \App\Models\AssignmentModel::create([
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

    public function joinCourseWithCode(Request $request)
    {
        $request->validate(['invite_code' => 'required|string']);
        $user = $request->user();
        $course = \App\Models\CoursesModel::where('invite_code', $request->invite_code)->first();
        if (!$course) {
            return response()->json(['message' => 'Érvénytelen meghívó kód!'], 404);
        }
        $usersArray = $course->course_users ? explode(',', $course->course_users) : [];

        if (in_array($user->username, $usersArray)) {
            return response()->json(['message' => 'Már csatlakoztál ehhez a kurzushoz!'], 400);
        }
        $usersArray[] = $user->username;
        $course->course_users = implode(',', array_filter($usersArray));
        $course->save();

        return response()->json(['message' => 'Sikeresen csatlakoztál a kurzushoz!'], 200);
    }

    public function getCourseDetails(Request $request, $id)
    {
        $user = $request->user();
        $course = \App\Models\CoursesModel::find($id);
        if (!$course) {
            return response()->json(['message' => 'A kurzus nem található!'], 404);
        }
        
        $assignments = \App\Models\AssignmentModel::where('course_id', $id)->get();

        $is_teacher = ($course->creator_username === $user->username);

        return response()->json([
            'status' => 'Siker',
            'course' => $course,
            'assignments' => $assignments,
            'is_teacher' => $is_teacher
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
            'user_username' => $request->user()->username, 
            'creator_username' => $request->user()->username
        ]);

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

    public function getSubmissions($id)
    {
        $submissions = \App\Models\SubmissionModel::with('user')->where('assignment_id', $id)->get();
        return response()->json(['submissions' => $submissions], 200);
    }

    public function getTestForStudent(Request $request, $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Nincs bejelentkezve!'], 401);
            }

            $submission = \App\Models\SubmissionModel::where('assignment_id', $id)->where('user_id', $user->id)->first();
            $assignment = \App\Models\AssignmentModel::where('id', $id)->where('assignment_accessible', 1)->first();

            if (!$assignment) {
                return response()->json(['message' => 'Ez a feladat nem található vagy nem tölthető ki!'], 404);
            }

            $deadline = \Carbon\Carbon::parse($assignment->assignment_deadline)->endOfDay();
            $isExpired = \Carbon\Carbon::now()->greaterThan($deadline);

            if ($submission) {
                return response()->json([
                    'is_completed' => true,
                    'is_expired' => $isExpired,
                    'assignment' => $assignment,
                    'submission' => $submission
                ], 200);
            }

            if ($isExpired) {
                return response()->json([
                    'is_completed' => false,
                    'is_expired' => true,
                    'assignment' => $assignment
                ], 200);
            }

            $questions = \App\Models\QuestionModel::with(['answers' => function($query) {
                $query->select('id', 'question_id', 'answer_text'); 
            }])->where('assignment_id', $id)->get();

            return response()->json([
                'is_completed' => false,
                'is_expired' => false,
                'assignment' => $assignment,
                'questions' => $questions
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Rendszerhiba: ' . $e->getMessage()], 500);
        }
    }

    public function submitTest(Request $request, $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Hiba: Nem vagy bejelentkezve! Ellenőrizd, hogy a route az api.php-ban az auth:sanctum csoporton belül van-e!'], 401);
            }
            $existing = \App\Models\SubmissionModel::where('assignment_id', $id)
                            ->where('user_id', $user->id)
                            ->first();

            if ($existing) {
                return response()->json(['message' => 'Ezt a tesztet már beküldted!'], 400);
            }

            $studentAnswers = $request->input('answers', []);
            $achievedPoints = 0;

            $questions = \App\Models\QuestionModel::where('assignment_id', $id)->get();

            foreach ($questions as $question) {
                $correctAnswer = \App\Models\AnswerModel::where('question_id', $question->id)
                                    ->where('is_correct', 1)
                                    ->first();

                if ($correctAnswer !== null) {
                    if (isset($studentAnswers[$question->id]) && (int)$studentAnswers[$question->id] === (int)$correctAnswer->id) {
                        $achievedPoints += (int)$question->question_points;
                    }
                }
            }

            $assignment = \App\Models\AssignmentModel::find($id);
            if (!$assignment) {
                return response()->json(['message' => 'Hiba: Ez a feladat nem található!'], 404);
            }
            $deadline = \Carbon\Carbon::parse($assignment->assignment_deadline)->endOfDay();
            if (\Carbon\Carbon::now()->greaterThan($deadline)) {
                return response()->json(['message' => 'Sajnos időközben lejárt a feladat beküldési határideje!'], 400);
            }

            $maxPoints = $assignment->assignment_max_point;
            
            $grade = 1;
            if ($maxPoints > 0) {
                $percentage = ($achievedPoints / $maxPoints) * 100;
                if ($percentage >= 90) { $grade = 5; }
                elseif ($percentage >= 80) { $grade = 4; }
                elseif ($percentage >= 65) { $grade = 3; }
                elseif ($percentage >= 50) { $grade = 2; }
                else { $grade = 1; }
            }

            $submission = \App\Models\SubmissionModel::create([
                'assignment_id' => $id,
                'user_id' => $user->id,
                'achieved_points' => $achievedPoints,
                'grade' => $grade,
                'status' => 1
            ]);

            return response()->json([
                'message' => 'Teszt sikeresen beküldve és kijavítva!',
                'achieved_points' => $achievedPoints,
                'grade' => $grade
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Rendszerhiba: ' . $e->getMessage() . ' (Sor: ' . $e->getLine() . ')'
            ], 500);
        }
    }
}