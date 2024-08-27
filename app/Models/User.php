<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'email_verified_at',
        'roles',
        'phone_id',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAvatarUrlAttribute()
    {
        if($this->avatar !== null){
            return asset('storage/images/avatar/'.$this->avatar);
        }else{
            return null;
        }
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function leaves()
    {
        return $this->hasMany(LeaveBalance::class)->where('start_date', '<=', now())->where('exp_date','>=', now());
    }

    public function bawahan()
    {
        return $this->hasMany(Employee::class, 'id', 'atasan_id');
    }

    public function sleep()
    {
        return $this->hasMany(Sleep::class, 'user_id', 'id');
    }

    public function absen()
    {
        return $this->hasMany(Clock::class, 'user_id', 'id');
    }

    public function smartwatch()
    {
        return $this->hasOne(Watchdist::class, 'user_id', 'id');
    }


}

