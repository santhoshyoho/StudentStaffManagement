<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institute_course_classes', function (Blueprint $table) {
            $table->id();
            $table->date('class_date');
            $table->time('class_time');
            $table->string('class_duration');
            $table->enum('class_platform', ['google_meet', 'zoom','other']);
            $table->enum('created_by', ['teacher', 'admin']);
            $table->enum('class_status', ['completed', 'ongoing', 'upcoming'])->default('upcoming');
           
            $table->string('institute_course_id');
            $table->enum('is_deleted', ['yes', 'no']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('institute_course_id')->references('institute_course_id')->on('institute_courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_course_classes');
    }
};
