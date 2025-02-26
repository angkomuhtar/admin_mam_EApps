<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Position extends Model
{
    use HasFactory;
    
    protected $table = 'positions';

    protected $fillable = [
        'company_id',
        'division_id',
        'position',
    ];

    public function company() : BelongsTo {
        return $this->belongsTo(Company::class);
    }

    public function division() : BelongsTo {
        return $this->belongsTo(Division::class);
    }

    public function position_class() : BelongsTo {
        return $this->belongsTo(PositionClass::class, 'class_id');
    }
    

}
