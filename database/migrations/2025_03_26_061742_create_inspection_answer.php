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
        Schema::create('inspection_answer', function (Blueprint $table) {
            $table->id();
            $table->enum('answer', ['yes', 'no']);
            $table->text('note')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('question_id')->constrained('inspection_question','id')->onDelete('cascade');
            $table->foreignId('inspection_card_id')->constrained('inspection_card','id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('inspection_answer', function (Blueprint $table) {
        //     $table->dropForeign(['question_id', 'inspection_card_id']);
        // });
        Schema::dropIfExists('inspection_answer');
    }
};
