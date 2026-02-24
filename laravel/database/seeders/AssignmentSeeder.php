<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentSeeder extends Seeder
{
    // Schema::create('assignment', function (Blueprint $table) {
    //     $table->id();
    //     $table->intager('course_id');
    //     $table->string('creator_username');
    //     $table->string('user_username');
    //     $table->string('assignment_name');
    //     $table->string('assignment_type');
    //     $table->string('assignment_finnished');
    //     $table->text('assignment_max_point');
    //     $table->text('assignment_succed_point');
    //     $table->text('assignment_deadline');
    //     $table->text('assignment_accessible');
    //     $table->text('assignment_accessible_time');
    //     $table->timestamps();
    // });
    public function run(): void
    {
        $faker = Faker::create();

        for ($i=0; $i < 10; $i++) {
            DB::table('assignment')->insert([
                [
                    'id' => $i+2,
                    'course_id' => $faker->randomDigit,
                    'creator_username' => $faker->randomElement(['TeIm', 'Alma', 'Korte', 'TeEl']),
                    'user_username' => $faker->randomElement(['TeIm', 'Alma', 'Korte', 'TeEl']),
                    'assignment_name' => $faker->word,
                    'assignment_type' => $faker->word,
                    'assignment_finnished' => $faker->numberBetween(0, 2),
                    'assignment_max_point' => '100',
                    'assignment_succed_point' => $faker->numberBetween(0, 100),
                    'assignment_grade' => $faker->numberBetween(1,5),
                    'assignment_deadline' => $faker->dateTimeBetween('+1 day', '+1 week'),
                    'assignment_accessible' => $faker->numberBetween(0, 2)
                ],
            ]);
        }
    }
}
