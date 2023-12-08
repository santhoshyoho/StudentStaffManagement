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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id'); // Reference to the author user ID
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['exam', 'event', 'other']);
            $table->unsignedBigInteger('related_id')->nullable(); // Reference to related model ID (e.g., exam or event ID)
            $table->unsignedBigInteger('course_id')->nullable(); // Reference to the course ID
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users'); // Assuming you have a 'users' table
            $table->foreign('course_id')->references('id')->on('courses'); // Assuming you have a 'courses' table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
