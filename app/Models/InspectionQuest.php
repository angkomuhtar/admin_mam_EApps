<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionQuest extends Model
{
    use HasFactory;

    protected $table = 'inspection_question';
    protected $fillable = [
        'slug',
        'question',
        'type',
        'status',
        'inspection_id',
        'sub_inspection_id',
    ];

    public function sub_inspection()
    {
        return $this->belongsTo(SubInspection::class, 'sub_inspection_id', 'id');
    }
    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id', 'id');
    }
}
