<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->enum('affiliation', ['CS', 'SE', 'Engineering', 'Other']);
            $table->enum('specialization', ['Software', 'Hardware', 'Business']);
            $table->text('description')->nullable();
            $table->boolean('cross_skill_trained')->default(false);
            $table->enum('institution', ['SCIT', 'CEDAT', 'UniPod', 'UIRI', 'Lwera']);

            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
