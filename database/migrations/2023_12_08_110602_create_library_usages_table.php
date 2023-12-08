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
        Schema::create('library_usage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Assuming this is a student
            $table->unsignedBigInteger('library_resource_id');
            $table->enum('usage_type', ['borrow', 'return']);
            $table->text('description')->nullable();
            $table->timestamps();
        
            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('library_resource_id')->references('id')->on('library_resources');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_usages');
    }
};
