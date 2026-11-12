<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSite extends Model
{
    use HasFactory;

    protected $table = 'project_sites';

    protected $fillable = ['site_id', 'project_id'];

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
