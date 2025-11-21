<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sleep extends Model
{
    use HasFactory;

    protected $table = 'users_sleeps';

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'date',
        'stage',
        'attachment',
        'status'
    ];

    public function user(){
        return $this->hasMany(User::class);
    }

    public function history()
    {
        return $this->hasOne(SleepHistory::class, 'sleep_id', 'id');
    }

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $model->created_at = now();
        });
    }

    public function getImagesUrlAttribute()
    {
        if($this->attachment !== null){
            return asset('storage/'.$this->attachment);
        }else{
            return null;
        }
    }

    public function getAttachmentAttribute($value)
    {
        if (!$value) return null;

        // Menghapus https://
        $path = str_replace('https://res.cloudinary.com/empapps/image/upload/', '', $value);

        return url('/api/v2/cdn/' . $path);
    }

}
