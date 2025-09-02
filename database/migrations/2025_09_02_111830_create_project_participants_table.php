<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('project_id');
            $table->string('role_on_project')->nullable();
            $table->string('skill_role')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('participant_id')
                  ->references('participant_id')
                  ->on('participants')
                  ->onDelete('cascade');

            $table->foreign('project_id')
                  ->references('project_id')
                  ->on('projects')
                  ->onDelete('cascade');

            // Optional: prevent duplicate entries
            $table->unique(['participant_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_participants');
    }
};
