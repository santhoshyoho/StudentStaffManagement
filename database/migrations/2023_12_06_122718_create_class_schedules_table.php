<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id('class_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('staff_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location', 255);
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])->default('Upcoming');
            $table->text('feedback')->nullable();
            $table->integer('max_students')->nullable();
            $table->integer('enrolled_students')->default(0);
            $table->string('room_number', 20)->nullable();
            $table->text('materials_required')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
};

