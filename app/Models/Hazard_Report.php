<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Hazard_Report extends Model
{
    use HasFactory;
    protected $table = 'hazard_report';
    protected $fillable = [
        'hazard_report_number',
        'date_time',
        'id_location',
        'other_location',
        'detail_location',
        'company_id',
        'project_id',
        'dept_id',
        'category',
        'reported_condition',
        'recomended_action',
        'action_taken',
        'report_attachment',
        'due_date',
        'status',
        'created_by'
    ];


    public function location()
    {
        return $this->belongsTo(Hazard_location::class, 'id_location');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'dept_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function creator()
    {
        return $this->belongsTo(UserProfileView::class, 'created_by');
    }
    
    public function hazardAction()
    {
        return $this->hasOne(Hazard_action::class, 'hazard_id', 'id');
    }

    public function scopeByDept(Builder $query): void
    {
        $user = Auth::guard('api')->user();
        $allowed = $user->employee->division_id == '8' || $user->id == '4' || $user->id == '4482' || $user->id == '6071' ;
        if (!$allowed) {
            $query->where('dept_id', $user->employee->division_id);
            if ($user->employee->position->position_class->class < 4) {
                $query->where('dept_id', '');
            }
        }


    }

    public function getReportAttachmentAttribute($value)
    {
        if (!$value) return null;

        // Menghapus https://
        $path = str_replace('https://res.cloudinary.com/empapps/image/upload/', '', $value);

        return url('/api/v2/cdn/' . $path);
    }
}
