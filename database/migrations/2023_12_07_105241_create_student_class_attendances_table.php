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
        Schema::create('student_class_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_course_class_id');
            $table->string('student_id');
            $table->enum('attendance_status', ['present', 'absent'])->default('absent');
            $table->enum('is_deleted', ['yes', 'no']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('institute_course_class_id')->references('id')->on('institute_course_classes');
            $table->foreign('student_id')->references('student_id')->on('students'); // Assuming you have a 'students' table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_attendances');
    }
};
