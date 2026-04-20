<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\CoursesModel;
use App\Models\Module;
use Laravel\Sanctum\Sanctum;

class ModuleCreationTest extends TestCase
{
    use RefreshDatabase; 
    public function test_teacher_can_create_a_new_module()
    {
        $this->withoutExceptionHandling();

        $user = clone User::create([
            'name' => 'Tanár István',
            'username' => 'TanarPisti',
            'email' => 'tanar@iskola.hu',
            'password' => bcrypt('titkos123')
        ]);
        
        $course = CoursesModel::create([
            'creator_username' => 'TanarPisti',
            'course_name' => 'Programozás Alapjai',
            'course_type' => 'Online',
            'invite_code' => 'TEST01',
            'course_img_path' => 'default.jpg'
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/courses/{$course->id}/modules/new", [
            'module_title' => '1. Hét: A tesztelés művészete'
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Új hét (modul) sikeresen létrehozva!']);

        $this->assertDatabaseHas('modules', [
            'course_id' => $course->id,
            'module_title' => '1. Hét: A tesztelés művészete',
            'order_index' => 1
        ]);
    }
    public function test_module_creation_requires_a_title()
    {
        $user = User::create(['name' => 'Teszt', 'username' => 'TanarPisti', 'email' => 'a@b.hu', 'password' => '123']);
        $course = CoursesModel::create([
            'creator_username' => 'TanarPisti', 
            'course_name' => 'Kurzus', 
            'course_type' => 'Online', 
            'invite_code' => 'ABC',
            'course_img_path' => 'default.jpg' 
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/courses/{$course->id}/modules/new", [
            'module_title' => '' 
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['module_title']);
    }
    public function test_teacher_can_upload_material_to_module()
    {
        $this->withoutExceptionHandling();
        \Illuminate\Support\Facades\Storage::fake('public'); 
        $user = User::create(['name' => 'Teszt', 'username' => 'TanarPisti', 'email' => 'b@b.hu', 'password' => '123']);
        $course = CoursesModel::create([
            'creator_username' => 'TanarPisti', 
            'course_name' => 'Kurzus', 
            'course_type' => 'Online', 
            'invite_code' => 'ABC',
            'course_img_path' => 'default.jpg'
        ]);
        
        $module = Module::create([
            'course_id' => $course->id,
            'module_title' => '1. Hét',
            'order_index' => 1
        ]);

   
        $file = \Illuminate\Http\UploadedFile::fake()->create('tananyag.pdf', 100, 'application/pdf');

        $response = $this->postJson("/api/modules/{$module->id}/materials", [
            'file' => $file
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('materials', [
            'module_id' => $module->id,
            'file_name' => 'tananyag.pdf'
        ]);
    }
}