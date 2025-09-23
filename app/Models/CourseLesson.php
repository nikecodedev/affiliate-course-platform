<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'video_embed',
        'duration',
        'sort_order',
        'is_active',
        'is_free',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'duration' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Parent module
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    /**
     * Lesson attachments
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CourseLessonAttachment::class);
    }

    /**
     * Scope for active lessons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered lessons
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Scope for free lessons
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'Not specified';
        }

        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * Get lesson type based on content
     */
    public function getTypeAttribute()
    {
        if ($this->video_embed) {
            return 'video';
        }

        if ($this->content) {
            return 'text';
        }

        return 'mixed';
    }
}

