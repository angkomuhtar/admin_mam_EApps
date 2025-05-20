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
		DB::statement("DROP VIEW IF EXISTS user_profile_view");
        
        DB::statement('
        CREATE VIEW user_profile_view AS
            select `empapps`.`users`.`id` AS `id`,`empapps`.`users`.`username` AS `username`,`empapps`.`users`.`phone_id` AS `phone_id`,`empapps`.`employees`.`status` AS `emp_sts`,`empapps`.`employees`.`contract_status` AS `contract_status`,`empapps`.`users`.`status` AS `status`,`empapps`.`profiles`.`name` AS `name`,`empapps`.`divisions`.`division` AS `division`,`empapps`.`positions`.`position` AS `position`,`empapps`.`employees`.`nip` AS `nrp`,`empapps`.`position_class`.`class` AS `class`,`empapps`.`position_class`.`class_name` AS `class_name` from (((((`empapps`.`users` join `empapps`.`employees` on((`empapps`.`users`.`id` = `empapps`.`employees`.`user_id`))) join `empapps`.`profiles` on((`empapps`.`users`.`id` = `empapps`.`profiles`.`user_id`))) join `empapps`.`positions` on((`empapps`.`employees`.`position_id` = `empapps`.`positions`.`id`))) left join `empapps`.`position_class` on((`empapps`.`positions`.`class_id` = `empapps`.`position_class`.`id`))) join `empapps`.`divisions` on((`empapps`.`employees`.`division_id` = `empapps`.`divisions`.`id`)))
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
