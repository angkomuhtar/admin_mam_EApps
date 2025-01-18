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
        Schema::create('hazard_report', function (Blueprint $table) {
            $table->id();
            $table->string('hazard_report_number');
            $table->datetime('date_time');
            $table->integer('id_location');
            $table->string('other_location')->nullable();
            $table->string('detail_location');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->integer('dept_id');
            $table->string('category');
            $table->string('reported_condition');
            $table->string('recomended_action');
            $table->string('action_taken');
            $table->string('report_attachment');
            $table->date('due_date');
            $table->string('status');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_report_tables');
    }
};
