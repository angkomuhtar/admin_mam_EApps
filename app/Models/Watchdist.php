<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchdist extends Model
{
    use HasFactory;
    protected $table = 'watch_dist';

    protected $fillable = [
        'user_id',
        'tgl_terima',
        'ket'
    ];
}
