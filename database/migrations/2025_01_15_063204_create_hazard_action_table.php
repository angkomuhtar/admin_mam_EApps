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
        Schema::create('hazard_action', function (Blueprint $table) {
            $table->id();
            $table->string('attachment')->nullable()->default(null);
            $table->integer('hazard_id');
            $table->integer('pic');
            $table->string('status');
            $table->string('notes')->nullable()->default(null);
            $table->integer('supervised_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_action');
    }
};
