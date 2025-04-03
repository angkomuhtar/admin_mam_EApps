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
        Schema::create('sub_inspection', function (Blueprint $table) {
            $table->id();
            $table->string('sub_inspection_name');
            $table->enum('status' , ['Y', 'N'])->default('Y');
            $table->foreignId('inspection_id')->constrained('inspection', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('sub_inspection', function (Blueprint $table) {
        //     $table->dropForeign(['inspection_id']);
        // });
        Schema::dropIfExists('sub_inspection');
    }
};
