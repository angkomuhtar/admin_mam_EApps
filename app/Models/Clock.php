<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Clock extends Model
{
    use HasFactory;

    protected $table = 'clocks';

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function sleep(){
        return $this->hasOne(Sleep::class, 'user_id', 'user_id');
    }

    public function location() {
        return $this->belongsTo(Clock_location::class);
    }

    public function employee() {

        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }

    public function profile() {
        return $this->belongsTo(Profile::class, 'user_id', 'user_id');
    }

    public function shift() {
        return $this->belongsTo(Shift::class, 'work_hours_id', 'id');
    }

    public function getLateAttribute()
    {
        $time1 = $this->shift ? Carbon::parse($this->date.' '.$this->shift->start) : '';
        if ($this->clock_in) {
            $time2 = Carbon::parse($this->clock_in);
            if ($time2->greaterThan($time1)) {
                $data = $time2->diff($time1);
                return ['hours'=>$data->h, 'minutes'=>$data->i, 'seconds'=>$data->s,];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    public function getEarlyAttribute()
    {
        $time1 = $this->shift ? Carbon::parse($this->date.' '.$this->shift->end) : '';
        if ($this->clock_out) {
            $time2 = Carbon::parse($this->clock_out);
            if ($time2->lessThan($time1)) {
                $data = $time2->diff($time1);
                return ['hours'=>$data->h, 'minutes'=>$data->i, 'seconds'=>$data->s,];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }


}
