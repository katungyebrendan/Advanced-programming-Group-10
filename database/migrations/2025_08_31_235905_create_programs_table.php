<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
        $table->id('program_id');  // custom PK to match Program.php
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('national_alignment')->nullable();
        $table->json('focus_areas')->nullable();
        $table->json('phases')->nullable();
        $table->timestamps();
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
