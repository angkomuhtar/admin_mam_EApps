<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PositionClass extends Model
{
    use HasFactory;
    protected $table = 'position_class';

    public function position() : HasMany {
        return $this->hasMany(Position::class, 'class_id');
    }
}
