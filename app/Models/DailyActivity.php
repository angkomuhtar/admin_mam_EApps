<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DailyActivity extends Model
{
    use HasFactory;

    protected $table = 'daily_activity';

    public function unit_detail() : HasOne {
        return $this->hasOne(UnitDetails::class, 'id', 'unit');
    }

    public function created_by() : HasOne {
        return $this->hasOne(User::class, 'id', 'creator');
    }

    public function location() : HasOne {
        return $this->hasOne(Hazard_location::class, 'id', 'id_location');
    }

}
