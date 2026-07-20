<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitDetails extends Model
{
    use HasFactory;

    protected $table ='daily_activity_unit_details';

    public function daily_activity(){
        return $this->BelongsTo(DailyActivity::class);
    }
}
