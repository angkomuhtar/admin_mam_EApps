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
		DB::statement("DROP VIEW IF EXISTS v_clock");
        DB::statement('CREATE VIEW v_clock AS
            SELECT
	`clocks`.`user_id` AS `user_id`,
	`clocks`.`date` AS `date`,
	`clocks`.`clock_in` AS `clock_in`,
	`clocks`.`clock_out` AS `clock_out`,
	`clocks`.`work_hours_id` AS `work_hours_id`,
	`clocks`.`status` AS `status`,
	`profiles`.`name` AS `name`,
	`divisions`.`division` AS `division`,
	`positions`.`position` AS `position`,
	`options`.`value` AS `value`,
	`shifts`.`name` AS `shift`,
	`projects`.`name` AS `site` 
FROM
	(
        (
            (
                (
                    (
                        (
                            (`clocks`
								JOIN `profiles` ON ((
										`clocks`.`user_id` = `profiles`.`user_id` 
									)))
							JOIN `employees` ON ((
									`clocks`.`user_id` = `employees`.`user_id` 
								)))
						JOIN `divisions` ON ((
								`employees`.`division_id` = `divisions`.`id` 
							)))
					JOIN `positions` ON ((
							`employees`.`position_id` = `positions`.`id` 
						)))
				JOIN `options` ON ((
						`employees`.`category_id` = `options`.`kode` 
					)))
			JOIN `shifts` ON ((
					`clocks`.`work_hours_id` = `shifts`.`id` 
				)))
		JOIN `projects` ON ((
			`employees`.`project_id` = `projects`.`id` 
            )
        )
    )
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
