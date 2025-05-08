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
        Schema::create('inspection_question', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('question');
            $table->enum('status', ['Y', 'N'])->default('Y');
            $table->foreignId('inspection_id')->constrained('inspection', 'id')->onDelete('cascade');
            $table->foreignId('sub_inspection_id')->constrained('sub_inspection','id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_question');
    }
};
