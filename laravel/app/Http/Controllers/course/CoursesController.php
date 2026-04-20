<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoursesModel;
use App\Models\AssignmentModel;
use App\Http\Models\ModuleModel;
use App\Http\Requests\newCourseRequest;
use App\Http\Requests\AssignmentRequest;
use App\Http\Requests\MaterialRequest;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\AnswerRequest;
use App\Http\Requests\JoinCourseRequest;

use Illuminate\Support\Str;

class CoursesController extends Controller
{
    public function newCourse(newCourseRequest $request)
    {
        $adatok = $request->validated();
        $adatok['creator_id'] = $request->user()->id;
        $adatok['invite_code'] = strtoupper(Str::random(6));
        $course = \App\Models\CoursesModel::create([
            'creator_id' => $adatok['creator_id'],
            'course_name' => $adatok['course_name'],
            'course_type' => $adatok['course_type'],
            'course_img_path' => $adatok['course_img_path'] ?? '/images/default.jpg',
            'invite_code' => $adatok['invite_code']
        ]);

        return response()->json([
            'status' => 'Siker',
            'message' => 'A kurzus sikeresen létrejött!',
            'course' => $course
        ], 201);
    }
    public function joinCourseWithCode(JoinCourseRequest $request)
    {
        $user = $request->user();
        $course = \App\Models\CoursesModel::where('invite_code', $request->invite_code)->first();        if (!$course) {
            return response()->json(['message' => 'Hibás kód!'], 404);
        }
        $usersArray = $course->course_users ? explode(',', $course->course_users) : [];

        if (!$course) {
            return response()->json(['message' => 'Hibás kód vagy a kurzus nem létezik!'], 404);
        }
        if ($course->userss()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Már csatlakoztál ehhez a kurzushoz!'], 400);
        }
        $course->userss()->attach($user->id);
        return response()->json(['message' => 'Sikeresen csatlakoztál a kurzushoz!'], 200);
    }
    public function getCourseDetails(Request $request, $id) 
    {
        $course = \App\Models\CoursesModel::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Nincs ilyen kurzus'], 404);
        }

        $modules = \App\Models\ModuleModel::with(['assignments', 'materials'])
                    ->where('course_id', $id)
                    ->orderBy('order_index')
                    ->get();

        $user = $request->user();
        $is_teacher = ($course->creator_id === $user->id);

        return response()->json([
            'status' => 'Siker',
            'course' => $course,
            'modules' => $modules,
            'is_teacher' => $is_teacher,
            'debug' => [
                'eltarolt_keszito_id' => $course->creator_id,
                'jelenlegi_felhasznalo_id' => $user->id
            ]
        ], 200);
    }
    public function newAssignment(AssignmentRequest $request, $moduleId)
    {
        $module = \App\Models\ModuleModel::find($moduleId);
        if (!$module) return response()->json(['message' => 'A modul nem található!'], 404);

        \App\Models\AssignmentModel::create(array_merge(
            $request->validated(), 
            ['module_id' => $moduleId]
        ));

        return response()->json(['status' => 'Siker', 'message' => 'A feladat sikeresen kiírva!'], 201);
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

    public function updateAssignment(AssignmentRequest $request, $id)
    {
        $assignment = \App\Models\AssignmentModel::find($id);
        if (!$assignment) return response()->json(['message' => 'A feladat nem található!'], 404);

        $assignment->update($request->validated());

        return response()->json(['message' => 'Feladat sikeresen frissítve!', 'assignment' => $assignment], 200);
    }

    public function getQuestions($id)
    {
        $questions = \App\Models\QuestionModel::with('answers')->where('assignment_id', $id)->get();
        return response()->json(['questions' => $questions], 200);
    }

    public function addQuestion(QuestionRequest $request, $id)
    {
        $question = \App\Models\QuestionModel::create(array_merge(
            $request->validated(),
            ['assignment_id' => $id]
        ));
        return response()->json(['message' => 'Kérdés hozzáadva!', 'question' => $question], 201);
    }

    public function addAnswer(AnswerRequest $request, $id)
    {
        $answer = \App\Models\AnswerModel::create(array_merge(
            $request->validated(),
            ['question_id' => $id]
        ));
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
                return response()->json(['message' => 'Hiba: Nem vagy bejelentkezve!'], 401);
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
            $autoFeedback = "A rendszer automatikusan kiértékelte. Százalék: " . round($percentage ?? 0) . "%. Ajánlott jegy: " . $grade;

            $submission = \App\Models\SubmissionModel::create([
                'assignment_id' => $id,
                'user_id' => $user->id,
                'achieved_points' => $achievedPoints,
                'teacher_feedback' => $autoFeedback
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

    public function uploadMaterial(MaterialRequest $request, $moduleId)
    {
        $module = \App\Models\ModuleModel::with('course')->find($moduleId);
        if (!$module) return response()->json(['message' => 'Modul nem található'], 404);

        if ($module->course->creator_username !== $request->user()->username) {
            return response()->json(['message' => 'Nincs jogosultságod!'], 403);
        }

        $file = $request->file('file');
        \App\Models\MaterialModel::create([
            'module_id' => $moduleId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $file->store('materials', 'public')
        ]);

        return response()->json(['message' => 'Fájl sikeresen feltöltve!'], 201);
    }
    public function newModule(Request $request, $courseId)
    {
        $request->validate([
            'module_title' => 'required|string|max:255'
        ]);
        $orderIndex = \App\Models\ModuleModel::where('course_id', $courseId)->count() + 1;

        \App\Models\ModuleModel::create([
            'course_id' => $courseId,
            'module_title' => $request->module_title,
            'order_index' => $orderIndex
        ]);

        return response()->json(['message' => 'Új hét (modul) sikeresen létrehozva!'], 201);
    }
}