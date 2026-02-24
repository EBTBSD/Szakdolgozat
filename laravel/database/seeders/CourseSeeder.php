<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::create('courses', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('creator_username');
        //     $table->string('course_name');
        //     $table->string('course_type');
        //     $table->string('course_img_path');
        //     $table->text('course_users');
        //     $table->timestamps();
        // });
        $faker = Faker::create();

        for ($i=0; $i < 10; $i++) {
            DB::table('courses')->insert([
                [
                    'id' => $i+2,
                    'creator_username' => $faker->randomElement(['TeIm', 'Alma', 'Korte', 'TeEl']),
                    'course_name' => $faker->text(15),
                    'course_type' => 'WebDevelopers',
                    'course_img_path' => 'images/course_images/'. rand(1,8) .'.jpg',
                    'course_users' => 'a,b,c,d,e,f,g'
                ],
            ]);
        }

    }
}
