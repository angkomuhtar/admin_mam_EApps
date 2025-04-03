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
        Schema::create('inspection_card', function (Blueprint $table) {
            $table->id();
            $table->string('other_location')->nullable();
            $table->text('detail_location');
            $table->date('inspection_date');
            $table->text('recomended_action');
            $table->foreignId('created_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('supervised_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('hazard_location', 'id')->onDelete('cascade');
            $table->foreignId('inspection_id')->constrained('inspection', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('inspection_card', function (Blueprint $table) {
        //     if (Schema::hasColumn('inspection_card', 'created_by')) {
        //         $table->dropForeign(['created_by', 'supervised_by', 'location_id', 'inspection_id']);
        //     }
        //     // $table->dropForeign(['created_by', 'supervised_by', 'location_id', 'inspection_id']);
        // });
        Schema::dropIfExists('inspection_card');
    }
};
