<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id'); // Primary key
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('program_id')->nullable();
            $table->string('innovation_focus')->nullable();
            $table->string('prototype_stage')->nullable();
            $table->text('commercialization_plan')->nullable();
            $table->json('participants')->nullable(); // JSON array of participant IDs
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('facility_id')
                  ->references('facility_id')
                  ->on('facilities')
                  ->onDelete('cascade');

            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('programs')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
