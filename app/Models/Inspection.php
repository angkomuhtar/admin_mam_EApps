<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Inspection extends Model
{
    use HasFactory;
    protected $table = 'inspection';
    protected $fillable = [
        'inspection_name',
        'status',
        'slug',
    ];

    public function sub_inspection()
    {
        return $this->hasMany(SubInspection::class, 'inspection_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->inspection_name);
        });

    }

    
}
