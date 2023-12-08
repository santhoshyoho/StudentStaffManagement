<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('institute_id'); // Foreign key reference to institutes table
            $table->string('department_name');
            $table->string('department_code')->unique();
            $table->text('department_description')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('institute_id')->references('institute_id')->on('institutes')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
