<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'id' => 1,
                'class_name' => 'Pekerja Lapangan / Helper',
                'class' => '1',
            ],[
                'id' => 2,
                'class_name' => 'Operator / Teknisi / Staff / Administrator',
                'class' => '2',
            ],[
                'id' => 3,
                'class_name' => 'Skilled Worker / Senior Operator / Senior Teknisi',
                'class' => '2',
            ],[
                'id' => 4,
                'class_name' => 'Foreman / Officer',
                'class' => '3',
            ],[
                'id' => 5,
                'class_name' => 'Supervisor',
                'class' => '4',
            ],[
                'id' => 6,
                'class_name' => 'Superintendent',
                'class' => '5',
            ],[
                'id' => 7,
                'class_name' => 'Manager',
                'class' => '6',
            ],[
                'id' => 8,
                'class_name' => 'Top Management',
                'class' => '7',
            ]
        ];

        DB::table('position_class')->insert($data);
    }
}
