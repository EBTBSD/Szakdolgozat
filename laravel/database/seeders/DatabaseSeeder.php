<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CoursesModel;
use App\Models\ModuleModel;
use App\Models\AssignmentModel;
use App\Models\QuestionModel;
use App\Models\AnswerModel;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user_o = User::create([
            'username' => 'T3ST',
            'firstname' => 'Imre',
            'lastname' => 'Teszt',
            'email' => 'imre@teszt.hu',
            'password' => Hash::make('TesztAdat3'),
        ]);

        $user_t = User::create([
            'username' => '7ES7',
            'firstname' => 'Teszt',
            'lastname' => 'Elek',
            'email' => 'teszt@elek.hu',
            'password' => Hash::make('TesztAdat3'),
        ]);
        $this->command->info('1/5: Felhasználók (75 db) létrehozása...');
        $users = User::factory(75)->create();
        $this->command->info('2/5: Kurzusok (75 db) létrehozása...');
        $courses = CoursesModel::factory(75)->create();
        foreach ($courses as $course) {
            $randomU = $users->random(rand(3, 5))->pluck('id');
            $course->users()->attach($randomU);
            if ($course->id <= 5) {
                $course->users()->attach($user_t->id);
            }
        }
        $this->command->info('3/5: Modulok (75 db) létrehozása...');
        $ModuleModels = ModuleModel::factory(75)->create();
        $this->command->info('4/5: Feladatok (75 db) létrehozása...');
        $assignments = AssignmentModel::factory(75)->create();
        $this->command->info('5/5: Kérdések (75 db) és Válaszok generálása...');
        $questions = QuestionModel::factory(75)->create();
        foreach ($questions as $QuestionModel) {
            if ($QuestionModel->question_type === 'multiple_choice') {
                AnswerModel::create([
                    'question_id' => $QuestionModel->id,
                    'answer_text' => fake()->word() . ' (Helyes)',
                    'is_correct' => 1
                ]);
                for ($i = 0; $i < 3; $i++) {
                    AnswerModel::create([
                        'question_id' => $QuestionModel->id,
                        'answer_text' => fake()->word(),
                        'is_correct' => 0
                    ]);
                }
            }
        }

        $this->command->info('🎉 KÉSZ! Az adatbázis sikeresen feltöltve táblánként 75 random adattal.');
        $this->command->warn('Teszteléshez használható:');
        $this->command->warn('User #1 -> username: T3ST | jelszó: TesztAdat3');
        $this->command->warn('Diák -> username: 7ES7 | jelszó: TesztAdat3');
    }
}