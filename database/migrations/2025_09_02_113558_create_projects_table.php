<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('facility_id');
            $table->string('title');
            $table->string('nature_of_project'); // changed from enum to string
            $table->text('description');
            $table->string('innovation_focus')->nullable(); // IoT devices, smart home, renewable energy
            $table->string('prototype_stage'); // changed from enum to string
            $table->text('testing_requirements')->nullable();
            $table->text('commercialization_plan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
            $table->foreign('facility_id')->references('facility_id')->on('facilities')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('program_id');
            $table->index('facility_id');
            $table->index('nature_of_project');
            $table->index('prototype_stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
