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
        Schema::create('course_batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->date('class_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('is_deleted', ['yes', 'no'])->default('no');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('batch_id')->references('batch_id')->on('institute_course_batches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_batch_schedules');
    }
};






