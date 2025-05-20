<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InspectionCard extends Model
{
    use HasFactory;
    protected $table = 'inspection_card';

    protected $fillable = [
        'id_location',
        'other_location',
        'shift',
        'detail_location',
        'inspection_date',
        'departement_id',
        'recommended_action',
        'created_by',
        'supervised_by',
        'insoection_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id', 'id');
    }

    public function location()    {
        return $this->belongsTo(Hazard_location::class, 'location_id', 'id');  
    }

    public function answer() {
        return $this->hasMany(InspectionAnswer::class, 'inspection_card_id', 'id');
    }

    public function creator() {
        return $this->hasOne(UserProfileView::class, 'id', 'created_by');
    }

    public function supervisor(){
        return $this->belongsTo(UserProfileView::class, 'supervised_by', 'id');  
    }

    public function scopeByDept(Builder $query): void
    {
        $user = Auth::guard('api')->user();
        $allowed = ($user->employee->division_id == '8' && $user->employee->position->position_class->class >= 4) || $user->id == '4' || $user->id == '4482' ;
        if (!$allowed) {
            $query->where('departement_id', $user->employee->division_id)
            ->whereHas('creator', function($query) use ($user){
                $query->where('class', '<', $user->employee->position->position_class->class);   
            });
            // if ($user->employee->position->position_class->class < 4) {
            //     $query->where('departement_id', '');
            // }
        }
    }
}
