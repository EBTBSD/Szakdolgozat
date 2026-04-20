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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        // Töröljük a régi, nem relációs mezőt a courses táblából
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('course_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('course_users')->nullable();
        });
        Schema::dropIfExists('course_enrollments');
    }
};

