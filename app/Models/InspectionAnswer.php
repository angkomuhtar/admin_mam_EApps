<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionAnswer extends Model
{
    use HasFactory;

    protected $table = 'inspection_answer';
    protected $fillable = [
        'answer',
        'note',
        'due_date',
        'question_slug',
        'inspection_card_id'
    ];

    public function inspection_card() {
        return $this->belongsTo(InspectionCard::class, 'inspection_card', 'id');
    }

    public function question() {
        return $this->belongsTo(InspectionQuest::class, 'question_slug', 'slug');
    }

}
