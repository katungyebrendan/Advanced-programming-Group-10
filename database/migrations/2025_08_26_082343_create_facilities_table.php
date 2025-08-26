<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id('facility_id'); // primary key
            $table->string('name');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('partner_organization')->nullable();
            $table->string('facility_type')->nullable();
            $table->json('capabilities')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('facilities');
    }
};
