<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;
    protected $table = 'inspection';
    protected $fillable = [
        'inspection_name',
        'status',
        'created_by',
        'updated_by',
    ];

    public function sub_inspection()
    {
        return $this->hasMany(SubInspection::class, 'inspection_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
