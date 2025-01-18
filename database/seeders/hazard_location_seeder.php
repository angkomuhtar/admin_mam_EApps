<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class hazard_location_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hazard_location')->insert([
            ['id' => 1, 'location' => 'Office'],
            ['id' => 2, 'location' => 'Tambang / PIT'],
            ['id' => 3, 'location' => 'Workshop'],
            ['id' => 4, 'location' => 'Warehouse'],
            ['id' => 5, 'location' => 'Kantor'],
            ['id' => 999, 'location' => 'Lainya'],
        ]);
    }
}