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
        Schema::table('hazard_action', function (Blueprint $table) {
            //
            $table->unique(['hazard_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazard_action', function (Blueprint $table) {
            //
            $table->dropUnique(['hazard_id']);
        });
    }
};
