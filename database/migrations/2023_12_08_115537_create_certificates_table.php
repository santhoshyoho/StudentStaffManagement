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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming this is a student
            $table->unsignedBigInteger('institute_course_id')->nullable(); // Assuming this is the associated course
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('course_completed')->default(false); // New column for course completion status
            $table->string('certificate_file')->nullable(); // New column for certificate file
            $table->timestamps();
        
            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('institute_course_id')->references('id')->on('institute_courses')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
