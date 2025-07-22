<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('inspection_answer', function (Blueprint $table) {
            DB::statement("ALTER TABLE inspection_answer MODIFY answer ENUM('yes', 'no', 'na') NOT NULL DEFAULT 'yes'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
