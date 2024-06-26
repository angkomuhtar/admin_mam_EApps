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
        Schema::create('version', function (Blueprint $table) {
            $table->id();
            $table->enum('device', ['ANDROID','IOS'])->default('ANDROID');
            $table->string('version');
            $table->string('download');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version');
    }
};
