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
        Schema::create('history_sleeps', function (Blueprint $table) {
            $table->id();
            $table->integer('sleep_id');
            $table->datetime('start')->default(NULL)->nullable();
            $table->datetime('end')->default(NULL)->nullable();
            $table->integer('stage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_sleeps');
    }
};
