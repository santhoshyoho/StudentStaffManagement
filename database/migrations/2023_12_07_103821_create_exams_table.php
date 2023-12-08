<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id')->unique();
            $table->string('batch_id');
            $table->string('exam_name');
            $table->enum('exam_mode', ['online', 'offline'])->default('offline');
            $table->text('question_paper_pdf')->nullable();
            $table->timestamp('exam_date');
            $table->integer('passing_percentage')->default(35); // Minimum percentage to pass
            $table->integer('total_questions')->default(60); // Default total number of questions in the exam
            $table->enum('is_deleted', ['yes', 'no'])->default('no');
            $table->timestamps();
        
            // Foreign key relationship
            $table->foreign('batch_id')->references('batch_id')->on('institute_course_batches');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
