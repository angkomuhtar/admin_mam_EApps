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
        //

         Schema::create('daily_activity', function (Blueprint $table) {
            $table->id();
            $table->integer('id_location');
            $table->string('other_location')->nullable();
            $table->string('detail_location');
            $table->integer('creator');
            $table->string('job_type');
            $table->integer('unit')->nullable();
            $table->string('sts_unit')->nullable();
            $table->string('area')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration');
            $table->string('desc');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('daily_activity');
    }
};
