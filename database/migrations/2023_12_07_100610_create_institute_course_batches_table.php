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
        Schema::create('institute_course_batches', function (Blueprint $table) {
            $table->id();
            $table->string('institute_course_id');
            $table->string('institute_id');
            $table->string('batch_id')->unique();
            $table->string('batch_name')->unique();
            $table->string('batch_type');
            $table->string('batch_timing');
            $table->unsignedBigInteger('max_capacity');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('is_active', ['yes', 'no'])->default('no');
            $table->enum('is_deleted', ['yes', 'no'])->default('no');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('institute_id')->references('institute_id')->on('institutes');
            $table->foreign('institute_course_id')->references('institute_course_id')->on('institute_courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_course_batches');
    }
};
