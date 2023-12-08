<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('ticket_title');
            $table->text('ticket_description');
            $table->enum('ticket_status', ['open', 'in-progress', 'closed'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('priority')->default('normal'); // Options: low, normal, high
            $table->string('category')->nullable(); // E.g., technical issue, general inquiry
            $table->string('attachment')->nullable(); // File attachment for additional information
            $table->timestamps();
        
            // Foreign key relationships
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('assigned_to')->references('id')->on('users')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
