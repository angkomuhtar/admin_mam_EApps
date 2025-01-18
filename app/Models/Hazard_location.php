<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard_location extends Model
{
    use HasFactory;
    protected $table = 'hazard_location';

    protected $fillable = [
        'location',
        // 'location_name',
        // 'location_description',
        // 'company_id',
        // 'project_id',
        // 'division_id',
        // 'created_at',
        // 'updated_at',
    ];
}
