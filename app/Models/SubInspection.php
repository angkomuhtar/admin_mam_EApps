<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubInspection extends Model
{
    use HasFactory;
    protected $table = 'sub_inspection';

    protected $fillable = [
        'sub_inspection_name',
        'status',
        'inspection_id'
    ];
    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id', 'id');
    }
    public function question()
    {
        return $this->hasMany(InspectionQuest::class, 'sub_inspection_id', 'id');
    }
}
