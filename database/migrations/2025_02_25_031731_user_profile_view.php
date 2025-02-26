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
            select 
                `users`.`id` AS `id`,
                `users`.`username` AS `username`,
                `users`.`phone_id` AS `phone_id`,
                `employees`.`status` AS `emp_sts`,
                `employees`.`contract_status` AS `contract_status`,
                `users`.`status` AS `status`,
                `profiles`.`name` AS `name`,
                `divisions`.`division` AS `division`,
                `positions`.`position` AS `position`,
                `employees`.`nip` AS `nrp` 
            from 
            ((( 
                (`users` join `employees` on((`users`.`id` = `employees`.`user_id`))) join `profiles` on((`users`.`id` = `profiles`.`user_id`))) join `positions` on((`employees`.`position_id` = `positions`.`id`))) join `divisions` on((`employees`.`division_id` = `divisions`.`id`)))
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
