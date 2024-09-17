<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
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
        'wh_code'
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

    // public static function boot(){
    //     parent::boot();

    //     static::creating(function($model){
    //         $prefix = $model->company_id == 1 ? 'MAM' : 'AT';
    //         $number = Employee::where('nip','like', $prefix.'%')->max('number')+1;
    //         $model->number = $number;
    //         $date= Carbon::parse($model->doh);
    //         $model->nip = $prefix.'.'.$date->format('ym').'.'.str_pad($number,4, '0',STR_PAD_LEFT);
    //     });
    // }
}
