<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;
    
    protected $table = 'companies';

    protected $fillable = [
        'company'
    ];

    public function division() : HasMany {
        return $this->hasMany(Division::class);
    }
    public function position() {
        return $this->belongsTo(Position::class);
    }
}
