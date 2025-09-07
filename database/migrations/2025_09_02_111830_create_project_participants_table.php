<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
// database/migrations/xxxx_xx_xx_create_project_participants_table.php
public function up(): void
{
    Schema::create('project_participants', function (Blueprint $table) {
        $table->id(); // Auto-increment ID instead of composite key
       
        $table->unsignedBigInteger('project_id');
        $table->unsignedBigInteger('participant_id');
        $table->string('role_on_project')->nullable();
        $table->string('skill_role')->nullable();
        $table->timestamps();

        // Foreign keys
        $table->foreign('project_id')
              ->references('project_id')
              ->on('projects')
              ->onDelete('cascade');

        $table->foreign('participant_id')
              ->references('participant_id')
              ->on('participants')
              ->onDelete('cascade');

        // Unique constraint to prevent duplicate entries
        $table->unique(['project_id', 'participant_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('project_participants');
}
};
