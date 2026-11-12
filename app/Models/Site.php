<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table = 'sites';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function project_sites()
    {
        return $this->hasMany(ProjectSite::class);
    }

    public function parent_site()
    {
        return $this->belongsTo(Site::class, 'parent_site_id', 'id');
    }
}
