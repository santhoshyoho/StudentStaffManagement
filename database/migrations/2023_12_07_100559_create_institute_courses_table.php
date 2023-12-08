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
        Schema::create('institute_courses', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id');
            $table->string('course_id');
            $table->string('institute_course_id')->unique();
            $table->string('title');
            $table->text('overview');
            $table->string('course_duration');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced', 'professional'])->default('beginner');
            $table->string('logo')->nullable();
            $table->string('type')->default('course');
            $table->enum('delivery_mode', ['online', 'offline', 'hybrid'])->default('online');
            $table->text('description')->nullable();
            $table->double('price', 10, 2)->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->enum('discount_rate', ['price', 'percentage'])->default('percentage');
            $table->enum('is_active', ['yes', 'no'])->default('no');
            $table->enum('is_deleted', ['yes', 'no'])->default('no');
            $table->string('brochure')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('institute_id')->references('institute_id')->on('institutes');
            $table->foreign('course_id')->references('course_id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_courses');
    }
};
