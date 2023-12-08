<?php
// database/migrations/2023_12_01_create_exams_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id('exam_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('staff_id');
            $table->date('exam_date');
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])->default('Upcoming');
            $table->text('feedback')->nullable();
            $table->integer('max_students')->nullable();
            $table->integer('enrolled_students')->default(0);
            $table->string('room_number', 20)->nullable();
            $table->text('materials_required')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('exam_mode', ['Online', 'Offline'])->default('Online');
            $table->text('instructions')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('passing_score')->nullable();
        
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
};

