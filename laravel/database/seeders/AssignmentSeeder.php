<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AssignmentModel;
use App\Models\Module;

class AssignmentSeeder extends Seeder
{
    public function run()
    {
        AssignmentModel::create([
            'module_id' => 1,
            'assignment_name' => 'Első Beadandó: Laravel Alapok',
            'assignment_type' => 'Dolgozat',
            'assignment_max_point' => 100,
            'assignment_deadline' => '2026-12-31 23:59:00',
            'assignment_accessible' => 1
        ]);
    }
}
