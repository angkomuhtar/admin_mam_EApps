<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportParam extends Model
{
    use HasFactory;

    protected $table = 'report_params';

    public function report_param_details()
    {
        return $this->hasMany(ReportParamDetail::class);
    }
}
