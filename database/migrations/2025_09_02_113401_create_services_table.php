<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id'); // Primary key
            $table->unsignedBigInteger('facility_id'); // Foreign key
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['Machining', 'Testing', 'Training'])->nullable();
            $table->enum('skill_type', ['Hardware', 'Software', 'Integration'])->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('facility_id')
                  ->references('facility_id')
                  ->on('facilities')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
