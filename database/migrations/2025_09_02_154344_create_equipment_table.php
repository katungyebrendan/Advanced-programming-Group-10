<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')
                  ->references('facility_id')
                  ->on('facilities')
                  ->onDelete('cascade');

            $table->string('name');
            $table->string('capabilities')->nullable();
            $table->text('description')->nullable();
            $table->string('inventory_code')->nullable();

        // Allow domain layer and FormRequests to enforce allowed values
        $table->string('usage_domain')->nullable();
        $table->string('support_phase')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
}
