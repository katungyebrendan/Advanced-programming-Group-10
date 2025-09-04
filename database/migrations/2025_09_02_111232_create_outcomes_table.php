<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id('outcome_id');

            $table->foreignId('project_id')
                  ->constrained('projects', 'project_id')
                  ->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('artifact_link')->nullable();
            $table->string('outcome_type');
            $table->string('quality_certification')->nullable();
            $table->string('commercialization_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outcomes');
    }
};
