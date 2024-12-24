<?php

namespace Database\Seeders;

use App\Models\Clock;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class normalize_clock extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $limit = 1000;
        $all = Clock::count() / $limit;
        $page= ceil($all);
        for ($i=0; $i < $page; $i++) { 
            $data = Clock::offset($i*$limit)->limit($limit)->get();
            echo "complete ".ceil($i/$page*100)."%\n";
            foreach ($data as $item) {
                if($item->clock_in == NULL){
    
                }elseif ($item->clock_out == NULL) {
                    $newClockIn = Carbon::parse($item->clock_in);
                    $item->clock_in = $item->date.' '.$newClockIn->format('H:i:s');
                    $item->save();
                }elseif ($item->clock_out > $item->clock_in) {
                    $newClockIn = Carbon::parse($item->clock_in);
                    $newClockOut = Carbon::parse($item->clock_out);
                    $item->clock_in = $item->date.' '.$newClockIn->format('H:i:s');
                    $item->clock_out = $item->date.' '.$newClockOut->format('H:i:s');
                    $item->save();
                }else{
                    $newClockIn = Carbon::parse($item->clock_in);
                    $newClockOut = Carbon::parse($item->clock_out);
                    $newDate = Carbon::parse($item->date." ".$newClockOut->format('H:i:s'));
                    $item->clock_in = $item->date.' '.$newClockIn->format('H:i:s');
                    $item->clock_out = $newDate->addDays()->format('Y-m-d H:i:s');
                    $item->save();
                }
            }

        }

    }
}
