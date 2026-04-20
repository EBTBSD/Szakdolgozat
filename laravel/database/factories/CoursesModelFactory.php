<?php

namespace Database\Factories;

use App\Models\CoursesModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoursesModel>
 */
class CoursesModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CoursesModel::class;

    public function definition(): array
    {
        return [
            'creator_id' => User::inRandomOrder()->first()->id ?? User::factory(), 
            'course_name' => fake()->catchPhrase(),
            'course_type' => fake()->randomElement(['Informatika', 'Matek', 'Nyelv', 'Történelem']),
            'course_img_path' => '/images/course_images/default.jpg',
            'invite_code' => strtoupper(Str::random(6))
        ];
    }
}
