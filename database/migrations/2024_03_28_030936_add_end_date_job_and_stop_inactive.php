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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('end_date')->default(NULL)->nullable()->after('doh');
            $table->enum('contract_status',['ACTIVE', 'RESIGN', 'EXPIRED', 'FIRED'])->default('ACTIVE')->after('doh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('end_date', 'contract_status');
        });
    }
};
