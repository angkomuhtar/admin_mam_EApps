<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonEmployee extends Model
{
    protected $table = 'non_employees';

    use HasFactory;
}
