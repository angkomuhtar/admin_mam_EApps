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
        Schema::table('daily_activity', function (Blueprint $table) {
            $table->renameColumn('area', 'activity');
            $table->dropColumn('detail_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_activity', function (Blueprint $table) {
            $table->renameColumn('activity', 'area');
        });
    }
};
