<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Course modules
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    /**
     * Plans that include this course
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_courses');
    }

    /**
     * Scope for active courses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered courses
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get total lessons count
     */
    public function getTotalLessonsAttribute()
    {
        return $this->modules()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Get total duration (if available)
     */
    public function getTotalDurationAttribute()
    {
        return $this->modules()->with('lessons')->get()->sum(function ($module) {
            return $module->lessons->sum('duration');
        });
    }

    /**
     * Get status attribute (alias for is_active)
     */
    public function getStatusAttribute()
    {
        return $this->is_active;
    }

    /**
     * Set status attribute (alias for is_active)
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['is_active'] = $value;
    }
}

