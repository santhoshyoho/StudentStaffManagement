<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id('batch_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('staff_id'); // Foreign key reference to staff table
            $table->string('batch_code')->unique();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
};

