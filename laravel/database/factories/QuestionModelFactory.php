<?php

namespace Database\Factories;
use App\Models\QuestionModel;
use App\Models\AssignmentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = QuestionModel::class;

    public function definition(): array
    {
        return [
            'assignment_id' => AssignmentModel::inRandomOrder()->first()->id ?? AssignmentModel::factory(),
            'question_text' => fake()->sentence() . '?',
            'question_type' => fake()->randomElement(['multiple_choice', 'essay']),
            'question_points' => fake()->numberBetween(1, 10),
        ];
    }
}
