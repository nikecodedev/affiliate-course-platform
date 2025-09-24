<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the course that owns this module
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for this module
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Scope for ordered modules
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get total lessons count
     */
    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Get completed lessons count for a user
     */
    public function getCompletedLessonsCountForUser($userId)
    {
        return $this->lessons()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('completed', true);
            })
            ->count();
    }

    /**
     * Get progress percentage for a user
     */
    public function getProgressForUser($userId)
    {
        $totalLessons = $this->getLessonsCountAttribute();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->getCompletedLessonsCountForUser($userId);
        return round(($completedLessons / $totalLessons) * 100, 2);
    }
}