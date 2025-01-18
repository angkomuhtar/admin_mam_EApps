<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard_action extends Model
{
    use HasFactory;
    protected $table = 'hazard_action';
    protected $fillable = [
        'hazard_id',
        'attachment',
        'status',
        'notes',
        'pic',
        'supervised_by'
    ];

    public function hazard()
    {
        return $this->belongsTo(Hazard_Report::class, 'hazard_id', 'id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic');
    }
    public function supervisedBy()
    {
        return $this->belongsTo(User::class, 'supervised_by');
    }
}
