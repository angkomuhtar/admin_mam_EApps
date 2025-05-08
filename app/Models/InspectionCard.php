<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionCard extends Model
{
    use HasFactory;
    protected $table = 'inspection_card';

    protected $fillable = [
        'id_location',
        'other_location',
        'shift',
        'detail_location',
        'inspection_date',
        'recommended_action',
        'created_by',
        'supervised_by',
        'insoection_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id', 'id');
    }

    public function location()    {
        return $this->belongsTo(Hazard_location::class, 'location_id', 'id');  
    }

    public function answer() {
        return $this->hasMany(InspectionAnswer::class, 'inspection_card_id', 'id');
    }

    public function creator() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function supervisor(){
        return $this->belongsTo(User::class, 'id', 'supervised_by');  
    }
}
