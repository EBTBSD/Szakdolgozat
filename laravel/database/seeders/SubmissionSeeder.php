<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\AssignmentModel;
use App\Models\User;

class SubmissionSeeder extends Seeder
{
    public function run()
    {
        $assignment = AssignmentModel::first();
        $user = User::first(); 

        if (!$assignment || !$user) {
            $this->command->error('Nincs feladat vagy felhasználó! A Seeder leáll.');
            return;
        }

        Submission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'text_answer' => 'Tanár úr, elkészültem a házival. A Laravel nagyon szuper!',
            'achieved_points' => 95,
            'teacher_feedback' => 'Szép munka Pisti, majdnem tökéletes!'
        ]);
    }
}