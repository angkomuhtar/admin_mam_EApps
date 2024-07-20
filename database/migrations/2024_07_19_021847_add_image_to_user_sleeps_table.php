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
        Schema::table('users_sleeps', function (Blueprint $table) {
            $table->string('attachment')->default(NULL)->nullable()->after('stage');
            $table->enum('status', ['p','v','r'])->default('p')->after('stage'); //p:pending v:verified r:rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_sleeps', function (Blueprint $table) {
            //
            $table->dropColumn('attachment', 'status');
        });
    }
};
