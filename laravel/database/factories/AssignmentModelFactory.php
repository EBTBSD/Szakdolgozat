<?php

namespace Database\Factories;

namespace Database\Factories;
use App\Models\AssignmentModel;
use App\Models\ModuleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentModel>
 */
class AssignmentModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AssignmentModel::class;

    public function definition(): array
    {
        return [
            'module_id' => ModuleModel::inRandomOrder()->first()->id ?? ModuleModel::factory(),
            'assignment_name' => fake()->words(3, true),
            'assignment_type' => fake()->randomElement(['teszt', 'beadando']),
            'assignment_max_point' => fake()->randomElement([10, 20, 50, 100]),
            'assignment_deadline' => fake()->dateTimeBetween('now', '+1 month'),
            'assignment_accessible' => fake()->boolean(80) ? 1 : 0,
        ];
    }
}
