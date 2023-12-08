<?php

// database/migrations/2023_12_05_create_question_papers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('question_paper', function (Blueprint $table) {
            $table->id('question_paper_id');
            $table->unsignedBigInteger('exam_id');
            $table->text('question_text');
            $table->string('option_1')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_4')->nullable();
            $table->string('correct_answer'); // Assuming a single correct answer
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('exam_id')->references('exam_id')->on('exams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_paper');
    }
};

