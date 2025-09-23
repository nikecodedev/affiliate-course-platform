<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCourse extends Model
{
    use HasFactory;

    protected $table = 'plan_courses';

    protected $fillable = [
        'plan_id',
        'course_id',
    ];

    /**
     * Associated plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Associated course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

