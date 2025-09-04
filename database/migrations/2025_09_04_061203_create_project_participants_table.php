<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_participants', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                  ->constrained('projects', 'project_id')
                  ->onDelete('cascade');

            $table->foreignId('participant_id')
                  ->constrained('participants', 'participant_id')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['project_id', 'participant_id']); // prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_participants');
    }
};
