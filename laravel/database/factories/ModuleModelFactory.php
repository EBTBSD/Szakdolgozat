<?php

namespace Database\Factories;

use App\Models\ModuleModel;
use App\Models\CoursesModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ModuleModel::class;

    public function definition(): array
    {
        return [
            'course_id' => CoursesModel::inRandomOrder()->first()->id ?? CoursesModel::factory(),
            'module_title' => fake()->sentence(3),
            'order_index' => fake()->numberBetween(1, 10),
        ];
    }
}
