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
        Schema::create('answer_papers', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('question_paper_id');
            $table->string('selected_option');
            $table->integer('marks_scored')->nullable();
            $table->timestamps();
        
            // Foreign key relationships
            $table->foreign('exam_id')->references('exam_id')->on('exams');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('question_paper_id')->references('id')->on('question_papers');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
