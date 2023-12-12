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
        Schema::create('institutes', function (Blueprint $table) {
           
$table->id(); 
$table->string('institute_id')->unique(); 
$table->string('name'); 
$table->string('email'); 
$table->string('logo'); 
$table->string('type')->default('institute'); 
$table->json('image')->nullable(); 
$table->json('gallery')->nullable(); 
$table->string('description'); 
$table->timestamp('registered_date'); 
$table->string('address_line_1')->nullable(); 
$table->string('address_line_2')->nullable(); 
$table->string('city')->nullable(); 
$table->string('state')->nullable(); 
$table->string('pincode')->nullable(); 
$table->string('phone'); 
$table->string('alternate_number')->nullable(); 
$table->string('official_website')->nullable(); 
$table->string('facebook')->nullable(); 
$table->string('linkedin')->nullable(); 
$table->string('instagram')->nullable(); 
$table->string('twitter')->nullable(); 
$table->string('pinterest')->nullable(); 
$table->enum('is_active', ['yes', 'no'])->default('no'); 
$table->enum('is_deleted', ['yes', 'no'])->default('no'); 
$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); 
$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON  UPDATE CURRENT_TIMESTAMP')); 
 });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};
