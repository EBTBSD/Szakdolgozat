<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $course = \App\Models\CoursesModel::first();
    if (!$course) {
        $this->command->error('Nincs kurzus az adatbázisban! A ModuleSeeder leáll.');
        return;
    }

    Module::create([
        'course_id' => $course->id,
        'module_title' => '1. Hét: Bevezetés a Laravelbe',
        'order_index' => 1
    ]);

    Module::create([
        'course_id' => $course->id,
        'module_title' => '2. Hét: Adatbázisok és Migrációk',
        'order_index' => 2
    ]);
}
}
