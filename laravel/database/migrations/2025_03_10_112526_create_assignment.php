<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->string('creator_username');
            $table->string('user_username');
            $table->string('assignment_name');
            $table->string('assignment_type');
            $table->integer('assignment_finnished');
            $table->integer('assignment_max_point');
            $table->integer('assignment_succed_point');
            $table->integer('assignment_grade');
            $table->date('assignment_deadline');
            $table->integer('assignment_accessible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment');
    }
};
