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
		DB::statement("DROP VIEW IF EXISTS hr_w_created_by");

        DB::statement('
        CREATE VIEW hr_w_created_by AS
            select `hazard_report`.`hazard_report_number` AS `hazard_report_number`,`hazard_report`.`date_time` AS `date_time`,`hazard_report`.`other_location` AS `other_location`,`hazard_location`.`location` AS `location`,`hazard_report`.`detail_location` AS `detail_location`,`projects`.`name` AS `name`,`divisions`.`division` AS `division`,`hazard_report`.`category` AS `category`,`hazard_report`.`reported_condition` AS `reported_condition`,`hazard_report`.`recomended_action` AS `recomended_action`,`hazard_report`.`action_taken` AS `action_taken`,`hazard_report`.`report_attachment` AS `report_attachment`,`hazard_report`.`status` AS `status`,`user_profile_view`.`name` AS `created_by`,`user_profile_view`.`division` AS `created_division`,`user_profile_view`.`position` AS `created_position`,`user_profile_view`.`nrp` AS `nrp`,`hazard_report`.`due_date` AS `due_date` from (((((`hazard_report` join `hazard_location` on((`hazard_report`.`id_location` = `hazard_location`.`id`))) join `companies` on((`hazard_report`.`company_id` = `companies`.`id`))) join `projects` on((`hazard_report`.`project_id` = `projects`.`id`))) join `divisions` on((`hazard_report`.`dept_id` = `divisions`.`id`))) join `user_profile_view` on((`hazard_report`.`created_by` = `user_profile_view`.`id`)))
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
