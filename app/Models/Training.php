<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'specialist_id',
        'training_type_id',
        'course_name',
        'institution',
        'end_date',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function trainingType()
    {
        return $this->belongsTo(TrainingType::class);
    }
}
