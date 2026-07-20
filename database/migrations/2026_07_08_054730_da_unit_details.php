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
         Schema::create('daily_activity_unit_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('unit_code');
            $table->string('plate_number');
            $table->integer('unit_type_id');
            $table->string('unit_type');
            $table->string('unit_category');
            $table->string('unit_cat_code');
            $table->integer('unit_model_id');
            $table->string('brand');
            $table->string('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activity_unit_details');
    }
};
