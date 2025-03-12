<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'contract_code',
        'doh',
        'company_id',
        'division_id',
        'position_id',
        'category_id',
        'status',
        'shift_id',
        'nip',
        'user_id',
        'atasan_id',
        'project_id',
        'wh_code',
        'absen_location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'atasan_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function division() {
        return $this->belongsTo(Division::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function work_schedule()
    {
        return $this->hasOne(WorkSchedule::class, 'code', 'wh_code');
    }

    public function category () {
        return $this->belongsTo(Options::class, 'category_id', 'kode');
    }

    public function getLokasiAttribute()
    {
        $locArray = explode(',',$this->absen_location);
        $data = ClockLocation::whereIn('id', $locArray)->where('status', 'Y')->get();
        return $data;
    }

    public function scopeOfLevel(Builder $query): void
    {
        $user = Auth::guard('web')->user();
        if ($user->user_roles == 'ALL') {

        }elseif ($user->user_roles == 'COMP') {
            $query->where('company_id', $user->employee->company_id);
        }elseif ($user->user_roles == 'PROJ') {
            $query->where('company_id', $user->employee->company_id)->where('project_id', $user->employee->project_id);
        }elseif ($user->user_roles == 'DEPT') {
            $query->where('company_id', $user->employee->company_id)
            ->where('division_id', $user->employee->division_id);
        }elseif ($user->user_roles == 'TEAM') {
            $query->where('company_id', $user->employee->company_id)
            ->where('project_id', $user->employee->project_id);
        }else{
            $query->where('company_id', $user->employee->company_id)
            ->where('id', $user->employee->id);
        }
    }

}
