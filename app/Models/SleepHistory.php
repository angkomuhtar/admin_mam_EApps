<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepHistory extends Model
{
    use HasFactory;
    protected $table = 'history_sleeps';

    protected $fillable = [
        'sleep_id',
        'start',
        'end',
        'stage'
    ];
}
