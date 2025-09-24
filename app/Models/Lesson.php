<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'video_url',
        'attachment',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the module that owns this lesson
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the users who have access to this lesson
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot(['completed', 'completed_at'])
                    ->withTimestamps();
    }

    /**
     * Scope for ordered lessons
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Check if user has completed this lesson
     */
    public function isCompletedByUser($userId)
    {
        return $this->users()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->exists();
    }

    /**
     * Mark lesson as completed for user
     */
    public function markAsCompletedForUser($userId)
    {
        $this->users()->syncWithoutDetaching([
            $userId => [
                'completed' => true,
                'completed_at' => now(),
            ]
        ]);
    }

    /**
     * Get video embed URL
     */
    public function getVideoEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // Handle YouTube URLs
        if (strpos($this->video_url, 'youtube.com') !== false || strpos($this->video_url, 'youtu.be') !== false) {
            $videoId = $this->extractYouTubeId($this->video_url);
            if ($videoId) {
                return "https://www.youtube.com/embed/{$videoId}";
            }
        }

        // Handle Vimeo URLs
        if (strpos($this->video_url, 'vimeo.com') !== false) {
            $videoId = $this->extractVimeoId($this->video_url);
            if ($videoId) {
                return "https://player.vimeo.com/video/{$videoId}";
            }
        }

        return $this->video_url;
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Extract Vimeo video ID
     */
    private function extractVimeoId($url)
    {
        preg_match('/vimeo\.com\/(?:.*#|.*/videos/)?([0-9]+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Get attachment download URL
     */
    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment) {
            return null;
        }

        return asset('storage/' . $this->attachment);
    }

    /**
     * Get attachment file name
     */
    public function getAttachmentNameAttribute()
    {
        if (!$this->attachment) {
            return null;
        }

        return basename($this->attachment);
    }

    /**
     * Get lesson duration estimate (placeholder)
     */
    public function getDurationAttribute()
    {
        // This could be calculated based on content length or video duration
        // For now, return a placeholder
        return '5-10 min';
    }
}