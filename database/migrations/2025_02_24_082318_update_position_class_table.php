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
        Schema::table('position_class', function (Blueprint $table) {
            //
            $table->string('class_name');
            $table->integer('min_class');
            $table->integer('max_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('position_class', function (Blueprint $table) {
            //
            $table->dropColumn('class_name');
            $table->dropColumn('min_class');
            $table->dropColumn('max_class');
        });
    }
};
