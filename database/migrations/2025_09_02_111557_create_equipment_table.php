<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id('equipment_id'); // Primary key
            $table->unsignedBigInteger('facility_id'); // Foreign key
            $table->string('name');
            $table->json('capabilities')->nullable();
            $table->text('description')->nullable();
            $table->string('inventory_code')->unique();
            $table->enum('usage_domain', ['Electronics', 'Mechanical', 'IoT'])->nullable();
            $table->enum('support_phase', ['Training', 'Prototyping', 'Testing', 'Commercialization'])->nullable();
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
        Schema::dropIfExists('equipment');
    }
};
