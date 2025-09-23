<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCourseProgress extends Model
{
    use HasFactory;

    protected $table = 'client_course_progress';

    protected $fillable = [
        'client_id',
        'course_id',
        'lesson_id',
        'module_id',
        'completed',
        'completed_at',
        'progress_percentage',
        'time_spent',
        'last_position',
        'notes',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
        'time_spent' => 'integer',
    ];

    /**
     * Get the client that owns the progress
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson
     */
    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }

    /**
     * Get the module
     */
    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    /**
     * Scope for completed progress
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope for incomplete progress
     */
    public function scopeIncomplete($query)
    {
        return $query->where('completed', false);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);
    }

    /**
     * Update progress
     */
    public function updateProgress($percentage, $timeSpent = 0)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'time_spent' => $this->time_spent + $timeSpent,
            'completed' => $percentage >= 100,
            'completed_at' => $percentage >= 100 ? now() : null
        ]);
    }

    /**
     * Get formatted time spent
     */
    public function getFormattedTimeSpentAttribute()
    {
        $hours = floor($this->time_spent / 3600);
        $minutes = floor(($this->time_spent % 3600) / 60);
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        
        return $minutes . 'm';
    }

    /**
     * Get progress badge color
     */
    public function getProgressBadgeColorAttribute()
    {
        if ($this->completed) {
            return 'success';
        }
        
        if ($this->progress_percentage >= 75) {
            return 'info';
        }
        
        if ($this->progress_percentage >= 50) {
            return 'warning';
        }
        
        return 'secondary';
    }
}
