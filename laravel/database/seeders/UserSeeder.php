<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('username');
        //     $table->string('lastname')->nullable();
        //     $table->string('firstname')->nullable();
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
        DB::table('users')->insert([
            [
                'id' => '1',
                'username' => 'TeIm',
                'lastname' => 'Test',
                'firstname' => 'Imre',
                'email' => 'te@im.re',
                'password' => Hash::make('TestImre'),
            ],


        ]);
    }
}
