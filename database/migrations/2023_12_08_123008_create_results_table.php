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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id');
            $table->string('student_id');
            $table->string('batch_id');
            $table->integer('marks_obtained')->nullable();
            $table->enum('result_status', ['pass', 'fail'])->default('fail');
            $table->timestamps();
        
            // Foreign key relationships
            $table->foreign('exam_id')->references('exam_id')->on('exams');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->foreign('batch_id')->references('batch_id')->on('institute_course_batches');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
