<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportOperation extends Model
{
    use HasFactory;

    protected $table = 'report_operations';

    protected $fillable = ['report_param_id', 'report_param_detail_id', 'employee_id', 'point', 'date'];

    public function report_param()
    {
        return $this->belongsTo(ReportParam::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
